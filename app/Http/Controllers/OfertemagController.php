<?php

namespace App\Http\Controllers;

use Cookie;
use Illuminate\Http\Request;
use App\Models\{Product ,Store,Country,Utility,Cart,City,User,Plan,Customer,Tax,Setting,Order,OrderBillingDetail,TaxMethod,OrderTaxDetail,ActivityLog,ProductVariant,OrderNote,AppSetting,MainCategory,FlashSale,ShippingMethod,TaxOption};
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Session;
use Stripe;
use Illuminate\Http\RedirectResponse;

class OfertemagController extends Controller
{
    //
    public function MakeCheckout(Request $request, $slug,$product_id)
    {
        if($slug)
        {
            $store = Store::where('slug',$slug)->first();
            if($product_id == 0)
            {
                $response = Cart::cart_list_cookie($request->all(), $store->id);
                $response = json_decode(json_encode($response));
                $cartlist = (array)$response->data;
                if (empty($cartlist['product_list'])) {
                    return redirect()->back()->with('error', 'Cart is empty.');
                }
                $products = $cartlist['product_list'];
                $productIds = [];
                foreach ($products as $product) {
                    $productIds[] = $product->product_id;
                }
                $products = Product::whereIn('id', $productIds)->where('store_id',$store->id)->where('theme_id',$store->theme_id)->get();
            }else{
                $products = Product::where('id',$product_id)->where('store_id',$store->id)->where('theme_id',$store->theme_id)->get();
            }
            if($products)
            {
                $param = [
                    'theme_id' => $store->theme_id,
                    'customer_id' => !empty(\Auth::guard('customers')->user()) ? \Auth::guard('customers')->user()->id : 0,
                    'slug' => $slug,
                    'store_id' => $store->id,
                ];
                $request->merge($param);
                $payment_list_data = $this->payment_list($request, $slug);
                $filtered_payment_list = json_decode(json_encode($payment_list_data));
                $payment_list = $payment_list_data;
                $country_option = Country::orderBy('name','ASC')->pluck('name', 'id')->prepend('Select country', ' ');
                return view('front_end.sections.payment.payment',compact('store','products','country_option','payment_list','slug'));
            }
        }
    }

    public function payment_list(Request $request, $slug = '')
    {
        $slug = !empty($slug) ? $slug : '';
        $store = Store::where('slug', $slug)->first();
        $theme_id = !empty($store) ? $store->theme_id  : $request->theme_id;
        $storage = 'storage/';
        $Setting_array = [];
        if (auth('customers')->guest()) {
            $response = Cart::cart_list_cookie($request->all(), $request->store_id);
            $response = json_decode(json_encode($response));
        } else {
            $api = new ApiController();
            $store = Store::find($request->store_id);
            $data = $api->cart_list($request, $store->slug);
            $response = $data->getData();
        }

        // COD
        $is_cod_enabled = Utility::GetValueByName('is_cod_enabled', $theme_id, $store->id);
        $cod_info = Utility::GetValueByName('cod_info', $theme_id, $store->id);
        $cod_image = Utility::GetValueByName('cod_image', $theme_id, $store->id);
        if (empty($cod_image)) {
            $cod_images = asset(Storage::url('uploads/cod.png'));
        }
        $Setting_array[0]['status'] = (!empty($is_cod_enabled) && $is_cod_enabled == 'on') ? 'on' : 'off';
        $Setting_array[0]['name_string'] = 'COD';
        $Setting_array[0]['name'] = 'cod';
        if (!empty($cod_images)) {
            $Setting_array[0]['image'] = $cod_images;
        } else {
            $Setting_array[0]['image'] = $cod_image;
        }
        $Setting_array[0]['detail'] = $cod_info;

        // Bank Transfer
        $bank_transfer_info = Utility::GetValueByName('bank_transfer', $theme_id, $store->id);
        $is_bank_transfer_enabled = Utility::GetValueByName('is_bank_transfer_enabled', $theme_id, $store->id);
        $bank_transfer_image = Utility::GetValueByName('bank_transfer_image', $theme_id, $store->id);
        if (empty($bank_transfer_image)) {
            $bank_transfer_images = asset(Storage::url('uploads/bank.png'));
        }
        $Setting_array[1]['status'] = (!empty($is_bank_transfer_enabled) && $is_bank_transfer_enabled == 'on') ? 'on' : 'off';
        $Setting_array[1]['name_string'] = 'Bank Transfer';
        $Setting_array[1]['name'] = 'bank_transfer';
        if (!empty($bank_transfer_images)) {
            $Setting_array[1]['image'] = $bank_transfer_images;
        } else {
            $Setting_array[1]['image'] = $bank_transfer_image;
        }

        $Setting_array[1]['detail'] = !empty($bank_transfer_info) ? $bank_transfer_info : '';

        $Setting_array[2]['status'] = 'off';
        $Setting_array[2]['name_string'] = 'other_payment';
        $Setting_array[2]['name'] = 'Other Payment';
        $Setting_array[2]['image'] = '';
        $Setting_array[2]['detail'] = '';

        // Stripe ( Creadit card )
        $is_Stripe_enabled = Utility::GetValueByName('is_stripe_enabled', $theme_id, $store->id);
        $publishable_key = Utility::GetValueByName('publishable_key', $theme_id, $store->id);
        $stripe_secret = Utility::GetValueByName('stripe_secret', $theme_id, $store->id);
        $Stripe_image = Utility::GetValueByName('stripe_image', $theme_id, $store->id);
        if (empty($Stripe_image)) {
            $Stripe_image = asset(Storage::url('upload/stripe.png'));
        }
        $stripe_unfo = Utility::GetValueByName('stripe_unfo', $theme_id, $store->id);

        $Setting_array[3]['status'] = !empty($is_Stripe_enabled) ? $is_Stripe_enabled : 'off';
        $Setting_array[3]['name_string'] = 'Stripe';
        $Setting_array[3]['name'] = 'stripe';
        $Setting_array[3]['detail'] = $stripe_unfo;
        $Setting_array[3]['image'] = $Stripe_image;
        $Setting_array[3]['stripe_publishable_key'] = $publishable_key;
        $Setting_array[3]['stripe_secret_key'] = $stripe_secret;
        if ( !empty( $Setting_array ) ) {
            return Utility::success( $Setting_array );
        } else {
            return Utility::error(['message' => 'Payment not found.']);
        }
    }


    public function ofertemag_place_order_guest(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        $theme_id = $store->theme_id;
        $user = User::where('id', $store->created_by)->first();
        if ($user->type == 'admin') {
            $plan = Plan::find($user->plan_id);
        }
        $customer = Customer::where('email', $request->email)->where('regiester_date', null)->get();
        if ($customer->count() == 0) {
            $insert_array['first_name'] = $request->customer_name;
            $insert_array['last_name'] = $request->customer_name;
            $insert_array['email'] = $request->email;
            $insert_array['register_type'] = 'email';
            $insert_array['type'] = 'customer';
            $insert_array['mobile'] = !empty($request->customer_number) ? $request->customer_number : '';
            $insert_array['last_active'] = date('Y-m-d');
            $insert_array['theme_id'] = !empty($store->theme_id) ? $store->theme_id : '';
            $insert_array['store_id'] = !empty($store->id) ? $store->id : null;
            $insert_array['created_by'] = !empty($store->created_by) ? $store->created_by : null;

            $customer = Customer::create($insert_array);

            $request->merge([
                'store_id' => $store->id,
                'slug' => $slug,
                'customer_id' => $customer->id,
                'default_address' => 1,
                'first_name' => $request->customer_name,
                'address'    => $request->address,
                'country'    => $request->country_id,
                'state'    => $request->state_id,
                'city'    => $request->city_id,
                'postcode'    => $request->post_code ?? '',
                'title' => strtolower($request->customer_name),

            ]);

        } else {
            $customer = Customer::where('email', $request->email)->where('regiester_date', null)->first();
            $customer->last_active = date('Y-m-d');
            $customer->save();
        }
        $cart = Cookie::get('cart');
        $cart_array = json_decode($cart);
        $new_array = [];
        // Product array
        $i = 0;
        if($cart_array)
        {
            foreach ($cart_array as $key => $value) {
                $new_array['product'][$i]['product_id'] = $value->product_id;
                $new_array['product'][$i]['variant_id'] = $value->variant_id;
                $new_array['product'][$i]['qty'] = $value->qty;
                $i++;
            }
        }else{
            $product = Product::find($request['product_info']['id'] ?? 0);
            $new_array['product'][$i]['product_id'] = $product->id;
            $new_array['product'][$i]['variant_id'] = 0;
            $new_array['product'][$i]['qty'] = 1;
        }

        $new_array['tax_info'] = [];

        // TAX array
        $param['theme_id'] = $theme_id;
        $param['slug'] = $slug;
        $param['store_id'] = $store->id;

        $request->merge($param);
        $ApiController = new ApiController();


        if (!empty($request->coupon_code) && !isset($request['coupon_info'])) {
            $new_array['coupon_info'] = [];
            $apply_coupon_data = $ApiController->apply_coupon($request, $slug);
            $apply_coupon = $apply_coupon_data->getData();
            if ($apply_coupon->status == 1) {
                $new_array['coupon_info']['coupon_id'] = $apply_coupon->data->id;
                $new_array['coupon_info']['coupon_name'] = $apply_coupon->data->name;
                $new_array['coupon_info']['coupon_code'] = $apply_coupon->data->code;
                $new_array['coupon_info']['coupon_discount_type'] = $apply_coupon->data->coupon_discount_type;
                $new_array['coupon_info']['coupon_discount_number'] = $apply_coupon->data->amount;
                $new_array['coupon_info']['coupon_discount_amount'] = $apply_coupon->data->coupon_discount_amount;
                $new_array['coupon_info']['coupon_final_amount'] = $apply_coupon->data->final_price;
            }
            if (!empty($request->coupon_code)) {
                $cart_price = [
                    'sub_total' => $apply_coupon->data->final_price
                ];
                $request->request->add($cart_price);
            }
        }

        // coupon array
        if ($request->register == 'on') {
            $new_array['customer_id'] = $user->id;
        } else {
            $new_array['customer_id'] = 0;
        }

        // $new_array['user_id'] = 0;
        $new_array['shipping_id'] = $request->delivery_id;
        $new_array['slug'] = $slug;
        $new_array['store_id'] = $store->id;
        $request->merge($new_array);

        if ($request->payment_type == 'stripe' || $request->payment_type == 'cod' || $request->payment_type == 'bank_transfer') {
            $billing = is_string($request->billing_info) ? (array) json_decode($request->billing_info) : $request->billing_info;

            if (empty($request->customer_name)) {
                return redirect()->back()->with('error', __('Billing name not found.'));
            }
            if (empty($request->email)) {
                return redirect()->back()->with('error', __('Billing email not found.'));
            }
            if (empty($request->customer_number)) {
                return redirect()->back()->with('error', __('Billing telephone not found.'));
            }
            if (empty($request->address)) {
                return redirect()->back()->with('error', __('Billing address not found.'));
            }
            if (empty($request->country_id)) {
                return redirect()->back()->with('error', __('Billing country not found.'));
            }
            if (empty($request->state_id)) {
                return redirect()->back()->with('error', __('Billing state not found.'));
            }
            if (empty($request->city_id)) {
                return redirect()->back()->with('error', __('Billing city not found.'));
            }

            $cartlist_final_price = 0;
            $final_price = 0;
            if (!auth('customers')->user()) {
                $response = Cart::cart_list_cookie($request->all(),$store->id);
                $response = json_decode(json_encode($response));
                $cartlist = (array)$response->data;
                if (empty($cartlist['product_list'])) {
                    $product = Product::find($request['product_info']['id'] ?? 0);
                    if ( empty( $product->variant_id ) && $product->variant_id == 0 ) {
                        $per_product_discount_price = !empty( $product->product_data->discount_price ) ? $product->product_data->discount_price : 0;
                        $product_discount_price = $per_product_discount_price * 1;

                        $final_price = Product::ProductPrice($store->theme_id, $store->slug, $product->id,$product->variant_id);
                        $final_price = $final_price * 1;

                        $product_orignal_price = !empty($product->sale_price) ? $product->sale_price : 0;
                        $total_product_orignal_price = $product_orignal_price * 1;
                    } else {
                        $ProductVariant = ProductVariant::find($product->variant_id);

                        $per_product_discount_price = !empty($ProductVariant->discount_price) ? $ProductVariant->discount_price : 0;
                        $product_discount_price = $ProductVariant->discount_price * 1;
                        $final_price = Product::ProductPrice($store->theme_id, $store->slug, $product->product_id,$ProductVariant->id);
                        $final_price = $final_price * 1;

                        $product_orignal_price = !empty($ProductVariant->original_price) ? $ProductVariant->original_price : 0;
                        $total_product_orignal_price = $product_orignal_price * 1;
                    }
                    if(empty($product))
                    {
                        return redirect()->back()->with('error', 'Cart is empty.');
                    }
                }
                if (empty($cartlist['product_list'])) {
                    $data['theme_id'] =  $store->theme_id;
                    $data['store_id'] = $store->id;
                    $data['sub_total'] = $final_price;
                    $data['product_original_price'] = $product_orignal_price;
                    $taxes  = Tax::TaxCount($data);
                    $data['tax_info']['tax_price'] = $taxes['total_tax_price'];
                    $data['tax_info']['tax_rate'] = $taxes['tax_rate'] ?? 0;
                    $data['tax_info']['tax_name'] = $taxes['tax_name'] ?? null;
                    // cart list api call

                    $cartlist_final_price = !empty($final_price) ? $final_price : 0;
                    $product_price = !empty($product_orignal_price) ? $product_orignal_price : 0;
                    $final_price = $final_price + $taxes['total_tax_price'];
                    $total_sub_price = $final_price;
                    $tax_price = !empty($taxes['total_tax_price']) ? $taxes['total_tax_price'] : ($taxes['total_tax_price'] ?? 0);
                    $products = $request['product_info']['id'];
                }else{
                    $cartlist_final_price = !empty($cartlist['final_price']) ? $cartlist['final_price'] : 0;
                    $final_sub_total_price = !empty($cartlist['total_sub_price']) ? $cartlist['total_sub_price'] : 0;
                    $final_price = $response->data->total_final_price;
                    $tax_price = !empty($cartlist['total_tax_price']) ? $cartlist['total_tax_price'] : '';
                    $products = $cartlist['product_list'];
                }
            } elseif (!empty($request->customer_id)) {
                $cart_list['customer_id']   = $customer->id;
                $request->request->add($cart_list);

                if ($request->register == 'on') {
                    Cart::cookie_to_cart($customer->id, $store->id, $theme_id);
                }

                $cart_lists = new ApiController();
                $cartlist_response = $cart_lists->cart_list($request, $slug);
                $cartlist = (array)$cartlist_response->getData()->data;

                if (empty($cartlist['product_list'])) {
                    return redirect()->back()->with('error', 'Cart is empty.');
                }
                $cartlist_final_price = !empty($cartlist['final_price']) ? $cartlist['final_price'] : 0;
                $final_sub_total_price = !empty($cartlist['total_sub_price']) ? $cartlist['total_sub_price'] : 0;
                $final_price = $cartlist['total_final_price'];
                $billing = is_string($request->billing_info) ? (array) json_decode($request->billing_info) : $request->billing_info;
                $tax_price = $cartlist['total_tax_price'] ?? '';
                $products = $cartlist['product_list'];
            } else {
                return redirect()->back()->with('error', 'User not found.');
            }

            $coupon_price = 0;
            // coupon api call
            if (!empty($request['coupon_code'])) {

                    if (isset($request['coupon_info']) && $request['coupon_info']) {
                        $cartlist_final_price = $coupon_price = $request['coupon_info']['coupon_discount_amount'] ?? 0;
                    } else {
                        $coupon_data = $request->coupon_info;
                        $apply_coupon = [
                            'coupon_code' => $coupon_data['coupon_code'],
                            'sub_total' => $cartlist_final_price

                        ];
                        $request->request->add($apply_coupon);
                        $coupon_apply = new ApiController();
                        $apply_coupon_response = $coupon_apply->apply_coupon($request, $slug);
                        $apply_coupon = (array)$apply_coupon_response->getData()->data;

                        $order_array['coupon']['message'] = $apply_coupon['message'];
                        $order_array['coupon']['status'] = false;
                        if (!empty($apply_coupon['final_price'])) {
                            $cartlist_final_price = $apply_coupon['final_price'];
                            $coupon_price = $apply_coupon['amount'];
                            $order_array['coupon']['status'] = true;
                        }
                }
            }

            // dilivery api call
            $delivery_price = 0;
            if ($plan->shipping_method == 'on') {
                if (!empty($request->method_id)) {
                    $del_charge = new CartController();
                    $delivery_charge = $del_charge->get_shipping_method($request, $slug);
                    $content = $delivery_charge->getContent();

                    $data = json_decode($content, true);

                    $delivery_price = $data['shipping_final_price'];

                    $tax_price = $data['final_tax_price'];

                }
            } else {
                if (!empty($tax_price)) {
                    $tax_price = $tax_price;
                }else{
                    $tax_price = 0;
                }
            }
            $new_array['shipping_final_price'] = $delivery_price;
            $new_array['tax_price'] = $tax_price;
            $request->merge($new_array);
            if (!empty($prodduct_id_array)) {
                $prodduct_id_array = $prodduct_id_array = array_unique($prodduct_id_array);
                $prodduct_id_array = implode(',', $prodduct_id_array);
            } else {
                $prodduct_id_array = '';
            }

            //$new_array['cartlist_final_price'] = $cartlist_final_price - $coupon_price + $delivery_price + $tax_price;
            $new_array['cartlist_final_price'] = $cartlist_final_price;
            $request->merge($new_array);

            $product_reward_point = 1;

            $new_array['billing_info'] = is_string($request->billing_info) ? (array) json_decode($request->billing_info) : $request->billing_info;
            $request->merge($new_array);
            $paymentMethod = $request->payment_type;
            if($request->payment_type == 'stripe' )
            {
                $data = $this->processStripe($request->all(), $store);
                return new RedirectResponse($data);
            }elseif($request->payment_type == 'cod' || $request->payment_type == 'bank_transfer'){
                $data = $this->SaveOrder($store->slug,$request->all());
                return $data;
            }else{
                return new RedirectResponse($response);
            }

        }else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function cities_list(Request $request)
    {
        $state_id = $request->state_id;
        $cities_list = City::where('state_id',$state_id)->orderBy('name','ASC')->pluck('name', 'id')->prepend('Select option',0)->toArray();
        return response()->json($cities_list);

    }
    public function SaveOrder($slug,$data = null)
    {
        $slug = !empty($slug) ? $slug : '';
        $store = Store::where('slug', $slug)->first();
        if(empty($data))
        {
            $data = Session::get('request_data');
        }
        $customer_id = $data['customer_id'] ?? 0;

        $user = User::where('type', 'admin')->where('id',$store->created_by)->first();
        if ($user->type == 'admin') {
            $plan = Plan::find($user->plan_id);
        }
        $theme_id = !empty($data->theme_id) ? $data->theme_id : $store->theme_id;

        if (!auth('customers')->user()) {
            $rules = [
                'payment_type' => 'required',
            ];
        } else {
            $rules = [
                'payment_type' => 'required',
            ];
        }

        $product = Product::find($data['product']['product_id'] ?? 0);

        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            Utility::error([
                'message' => $messages->first()
            ]);
        }

        $cartlist_final_price = 0;
        $final_price = 0;
        $response = Cart::cart_list_cookie($data,$store->id);
        $response = json_decode(json_encode($response));
        $cartlist = (array)$response->data;

        if (empty($cartlist['product_list'])) {
            $product = Product::find($data['product_info']['id'] ?? 0);
            if ( empty( $product->variant_id ) && $product->variant_id == 0 ) {
                $per_product_discount_price = !empty( $product->product_data->discount_price ) ? $product->product_data->discount_price : 0;
                $product_discount_price = $per_product_discount_price * 1;

                $final_price = Product::ProductPrice($store->theme_id, $store->slug, $product->id,$product->variant_id);
                $final_price = $final_price * 1;

                $product_orignal_price = !empty($product->sale_price) ? $product->sale_price : 0;
                $total_product_orignal_price = $product_orignal_price * 1;
            } else {
                $ProductVariant = ProductVariant::find($product->variant_id);

                $per_product_discount_price = !empty($ProductVariant->discount_price) ? $ProductVariant->discount_price : 0;
                $product_discount_price = $ProductVariant->discount_price * 1;
                $final_price = Product::ProductPrice($store->theme_id, $store->slug, $product->product_id,$ProductVariant->id);
                $final_price = $final_price * 1;

                $product_orignal_price = !empty($ProductVariant->original_price) ? $ProductVariant->original_price : 0;
                $total_product_orignal_price = $product_orignal_price * 1;
            }
            $data['theme_id'] =  $store->theme_id;
            $data['store_id'] = $store->id;
            $data['sub_total'] = $final_price;
            $data['product_original_price'] = $product_orignal_price;
            $taxes  = Tax::TaxCount($data);
            $data['tax_info']['tax_price'] = $taxes['total_tax_price'];
            $data['tax_info']['tax_rate'] = $taxes['tax_rate'] ?? 0;
            $data['tax_info']['tax_name'] = $taxes['tax_name'] ?? null;
            // cart list api call

            $cartlist_final_price = !empty($final_price) ? $final_price : 0;
            $product_price = !empty($product_orignal_price) ? $product_orignal_price : 0;
            $final_price = $final_price + $taxes['total_tax_price'];
            $total_sub_price = $final_price;
            $tax_price = !empty($taxes['total_tax_price']) ? $taxes['total_tax_price'] : ($taxes['total_tax_price'] ?? 0);
            $product = Product::where('id', $data['product_info']['id'])->first();
            $data['product_list'][$data['product_info']['id']]['cart_id'] = $data['product_info']['id'];
            $data['product_list'][$data['product_info']['id']]['cart_created'] = $product->created_at;
            $data['product_list'][$data['product_info']['id']]['product_id'] = $data['product_info']['id'];
            $data['product_list'][$data['product_info']['id']]['image'] = $product->cover_image_path;
            $data['product_list'][$data['product_info']['id']]['name'] = $product->name;
            $data['product_list'][$data['product_info']['id']]['orignal_price'] = $product_orignal_price;
            $data['product_list'][$data['product_info']['id']]['total_orignal_price'] = $total_product_orignal_price;
            $data['product_list'][$data['product_info']['id']]['final_price'] = $final_price;
            $data['product_list'][$data['product_info']['id']]['qty'] = 1;
            $data['product_list'][$data['product_info']['id']]['variant_id'] = 0;
            $data['product_list'][$data['product_info']['id']]['variant_name'] = '';
            $data['product_list'][$data['product_info']['id']]['return'] = 0;
            $products = $data['product_list'];
        }else{
            $cartlist_final_price = !empty($cartlist['final_price']) ? $cartlist['final_price'] : 0;
            $product_price = !empty($cartlist['total_final_price']) ? $cartlist['total_final_price'] : 0;
            $final_price = $cartlist['total_final_price'];
            $total_sub_price = $cartlist['total_sub_price'];
            $tax_price = !empty($requests_data['tax_price']) ? $requests_data['tax_price'] : ($cartlist['tax_price'] ?? 0);
            $products = $cartlist['product_list'];
        }

        if (!empty($tax_price)) {
            $tax_price = $tax_price;
        }else{
            $tax_price = 0;
        }

        $settings = Setting::where('theme_id', $theme_id)->where('store_id', $store->id)->pluck('value', 'name')->toArray();

        // Order stock decrease start
        $prodduct_id_array = [];
        if (!empty($products)) {
            foreach ($products as $key => $product) {
                if(empty($product->product_id))
                {
                    $prodduct_id_array[] = $product['product_id'];
                    $product = Product::where('id', $product)->first();
                    $product_id = $product['product_id'];
                    $variant_id = 0;
                    $qtyy = 1;
                }else{
                    $prodduct_id_array[] = $product->product_id;
                    $product_id = $product->product_id;
                    $variant_id = $product->variant_id;
                    $qtyy = !empty($product->qty) ? $product->qty : 0;
                }


                $Product = Product::where('id', $product_id)->first();
                $datas = Product::find($product_id);
                if(isset($settings['stock_management']) && $settings['stock_management'] == 'on')
                {
                    if (!empty($product_id) && !empty($variant_id) && $product_id != 0 && $variant_id != 0) {
                        $ProductStock = ProductVariant::where('id', $variant_id)->where('product_id', $product_id)->first();
                        $variationOptions = explode(',', $ProductStock->variation_option);
                        $option = in_array('manage_stock', $variationOptions);
                        if (!empty($ProductStock)) {
                            if($option == true)
                            {
                                $remain_stock = $ProductStock->stock - $qtyy;
                                $ProductStock->stock = $remain_stock;
                                $ProductStock->save();

                                if($ProductStock->stock <= $ProductStock->low_stock_threshold)
                                {
                                    if (!empty(json_decode($settings['notification'])) && in_array("enable_low_stock",json_decode($settings['notification'])))
                                    {
                                        if(isset($settings['twilio_setting_enabled']) && $settings['twilio_setting_enabled'] =="on")
                                        {
                                            Utility::variant_low_stock_threshold($product,$ProductStock,$theme_id,$settings);
                                        }

                                    }
                                }
                                if(isset($settings['out_of_stock_threshold']) && $ProductStock->stock <= $settings['out_of_stock_threshold'])
                                {
                                    if (!empty(json_decode($settings['notification'])) && in_array("enable_out_of_stock",json_decode($settings['notification'])))
                                    {
                                        if(isset($settings['twilio_setting_enabled']) && $settings['twilio_setting_enabled'] =="on")
                                        {
                                            Utility::variant_out_of_stock($product,$ProductStock,$theme_id,$settings);
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $remain_stock = $datas->product_stock - $qtyy;
                                $datas->product_stock = $remain_stock;
                                $datas->save();
                                if($datas->product_stock <= $datas->low_stock_threshold)
                                {
                                    if (!empty(json_decode($settings['notification'])) && in_array("enable_low_stock",json_decode($settings['notification'])))
                                    {
                                        if(isset($settings['twilio_setting_enabled']) && $settings['twilio_setting_enabled'] =="on")
                                        {
                                            Utility::variant_low_stock_threshold($product,$datas,$theme_id,$settings);
                                        }

                                    }
                                }
                                if(isset($settings['out_of_stock_threshold']) && $datas->product_stock <= $settings['out_of_stock_threshold'])
                                {
                                    if (!empty(json_decode($settings['notification'])) && in_array("enable_out_of_stock",json_decode($settings['notification'])))
                                    {
                                        if(isset($settings['twilio_setting_enabled']) && $settings['twilio_setting_enabled'] =="on")
                                        {
                                            Utility::variant_out_of_stock($product,$datas,$theme_id,$settings);
                                        }
                                    }
                                }
                                if(isset($settings['out_of_stock_threshold']) && $datas->product_stock <= $settings['out_of_stock_threshold'] && $datas->stock_order_status == 'notify_customer')
                                {
                                    //Stock Mail
                                    $order_email = $billing['email'];
                                    $owner=User::find($store->created_by);
                                    $ProductId    = '';

                                    try
                                    {
                                        $dArr = [
                                            'item_variable' => $Product->id,
                                            'product_name' => $Product->name,
                                            'customer_name' => $billing['firstname'],
                                        ];

                                        // Send Email
                                        $resp = Utility::sendEmailTemplate('Stock Status', $order_email, $dArr, $owner,$store, $ProductId);
                                    }
                                    catch(\Exception $e)
                                    {
                                        $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                                    }
                                    try
                                    {
                                        $mobile_no =$request['billing_info']['billing_user_telephone'];
                                        $customer_name =$request['billing_info']['firstname'];
                                        $msg =   __("Dear,$customer_name .Hi,We are excited to inform you that the product you have been waiting for is now back in stock.Product Name: :$Product->name. ");
                                        $resp  = Utility::SendMsgs('Stock Status', $mobile_no, $msg);
                                    }
                                    catch(\Exception $e)
                                    {
                                        $smtp_error = __('Invalid OAuth access token - Cannot parse access token');
                                    }
                                }
                            }
                        } else {
                            return $this->error(['message' => 'Product not found .']);
                        }
                    } elseif (!empty($product_id) && $product_id != 0) {

                        if (!empty($Product)) {
                            $remain_stock = $Product->product_stock - $qtyy;
                            $Product->product_stock = $remain_stock;
                            $Product->save();
                            if($Product->product_stock <= $Product->low_stock_threshold)
                            {
                                if (!empty(json_decode($settings['notification'])) && in_array("enable_low_stock",json_decode($settings['notification'])))
                                {
                                    if(isset($settings['twilio_setting_enabled']) && $settings['twilio_setting_enabled'] =="on")
                                    {
                                        Utility::low_stock_threshold($Product,$theme_id,$settings);
                                    }
                                }
                            }

                            if(isset($settings['out_of_stock_threshold']) && $Product->product_stock <= $settings['out_of_stock_threshold'])
                            {
                                if (!empty(json_decode($settings['notification'])) && in_array("enable_out_of_stock",json_decode($settings['notification'])))
                                {
                                    if(isset($settings['twilio_setting_enabled']) && $settings['twilio_setting_enabled'] =="on")
                                    {
                                        Utility::out_of_stock($Product,$theme_id,$settings);
                                    }
                                }
                            }

                            if(isset($settings['out_of_stock_threshold']) && $Product->product_stock <= $settings['out_of_stock_threshold'] && $Product->stock_order_status == 'notify_customer')
                            {
                                //Stock Mail
                                $order_email = $billing['email'];
                                $owner=User::find($store->created_by);
                                $ProductId    = '';

                                try
                                {
                                $dArr = [
                                'item_variable' => $Product->id,
                                'product_name' => $Product->name,
                                'customer_name' => $billing['firstname'],
                                ];

                                // Send Email
                                $resp = Utility::sendEmailTemplate('Stock Status', $order_email, $dArr, $owner,$store, $ProductId);
                                }
                                catch(\Exception $e)
                                {
                                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                                }
                                try
                                {
                                    $mobile_no =$request['billing_info']['billing_user_telephone'];
                                    $customer_name =$request['billing_info']['firstname'];
                                    $msg =   __("Dear,$customer_name .Hi,We are excited to inform you that the product you have been waiting for is now back in stock.Product Name: :$Product->name. ");
                                    $resp  = Utility::SendMsgs('Stock Status', $mobile_no, $msg);
                                }
                                catch(\Exception $e)
                                {
                                    $smtp_error = __('Invalid OAuth access token - Cannot parse access token');
                                }
                            }

                        } else {
                            return $this->error(['message' => 'Product not found .']);
                        }
                    } else {
                        return $this->error(['message' => 'Please fill proper product json field .']);
                    }
                }
                // remove from cart
                Cart::where('customer_id', $customer_id)->where('product_id', $product_id)->where('variant_id', $variant_id)->where('theme_id', $theme_id)->where('store_id',$store->id)->delete();
            }
        }
        // Order stock decrease end
        if (!empty($prodduct_id_array)) {
            $prodduct_id_array = $prodduct_id_array = array_unique($prodduct_id_array);
            $prodduct_id_array = implode(',', $prodduct_id_array);
        } else {
            $prodduct_id_array = '';
        }
        $product_reward_point = 1;

        $product_order_id  = '0' . date('YmdHis');
        $is_guest = 1;
        if (auth('customers')->check()) {
            $product_order_id  = $customer_id . date('YmdHis');
            $is_guest = 0;
        }
        $delivery_price =0;
        $coupon_price =0;
        // add in  Order table  start
        $order = new Order();
        $order->product_order_id = $product_order_id;
        $order->order_date = date('Y-m-d H:i:s');
        $order->customer_id = !empty($customer_id) ? $customer_id : 0;
        $order->is_guest = $is_guest;
        $order->product_id = $prodduct_id_array;
        $order->product_json = json_encode($products);
        $order->product_price = $final_price;
        $order->coupon_price = $coupon_price;
        $order->delivery_price = $delivery_price;
        $order->tax_price = $tax_price;
        if (!auth('customers')->user()) {
            if ($plan->shipping_method == "on") {
                $order->final_price = $final_sub_total_price;
            } else {
                $order->final_price = $final_price + $tax_price - $coupon_price;
            }

        }else{
            if ($plan->shipping_method == "on") {
                $order->final_price = $total_sub_price + $delivery_price;
            } else {
                $order->final_price = $total_sub_price;
            }
        }
        $order->payment_comment = !empty($data['payment_comment']) ? $data['payment_comment'] : '';
        $order->payment_type = $data['payment_type'];
        $order->payment_status = 'Paid';
        $order->delivery_id = 0;
        $order->delivery_comment = !empty($data['delivery_comment']) ? $data['delivery_comment'] : '';
        $order->delivered_status = 0;
        $order->reward_points = SetNumber($product_reward_point);
        $order->additional_note = !empty($data['additional_note']) ? $data['additional_note']  : '';
        $order->theme_id = $store->theme_id;
        $order->store_id = $store->id;
        $order->save();

        $billing_city_id = 0;
        if (!empty($data['city_id'])) {
            $cityy = City::where('name', $data['city_id'])->first();
            if (!empty($cityy)) {
                $billing_city_id = $cityy->id;
            } else {
                $new_billing_city = new City();
                $new_billing_city->name = $data['city_id'];
                $new_billing_city->state_id = $data['state_id'];
                $new_billing_city->country_id = $data['country_id'];
                $new_billing_city->save();
                $billing_city_id = $new_billing_city->id;
            }
        }

        $delivery_city_id = 0;
        if (!empty($data['city_id'])) {
            $d_cityy = City::where('name', $data['city_id'])->first();
            if (!empty($d_cityy)) {
                $delivery_city_id = $d_cityy->id;
            } else {
                $new_delivery_city = new City();
                $new_delivery_city->name = $data['city_id'];
                $new_delivery_city->state_id = $data['state_id'];
                $new_delivery_city->country_id = $data['country_id'];
                $new_delivery_city->save();
                $delivery_city_id = $new_delivery_city->id;
            }
        }

        $OrderBillingDetail = new OrderBillingDetail();
        $OrderBillingDetail->order_id = $order->id;
        $OrderBillingDetail->product_order_id = $order->product_order_id;
        $OrderBillingDetail->first_name = !empty($data['customer_name']) ? $data['customer_name'] : '';
        $OrderBillingDetail->last_name = !empty($data['customer_name']) ? $data['customer_name'] : '';
        $OrderBillingDetail->email = !empty($data['email']) ? $data['email'] : '';
        $OrderBillingDetail->telephone = !empty($data['customer_number']) ? $data['customer_number'] : '';
        $OrderBillingDetail->address = !empty($data['address']) ? $data['address'] : '';
        $OrderBillingDetail->postcode = !empty($data['post_code']) ? $data['post_code'] : '';
        $OrderBillingDetail->country = !empty($data['country_id']) ? $data['country_id'] : '';
        $OrderBillingDetail->state = !empty($data['state_id']) ? $data['state_id'] : '';
        $OrderBillingDetail->city = $billing_city_id;
        $OrderBillingDetail->theme_id = $theme_id;
        $OrderBillingDetail->delivery_address = !empty($data['address']) ? $data['address'] : '';
        $OrderBillingDetail->delivery_city = $delivery_city_id;
        $OrderBillingDetail->delivery_postcode = !empty($data['post_code']) ? $data['post_code'] : '';
        $OrderBillingDetail->delivery_country = !empty($data['country_id']) ? $data['country_id'] : '';
        $OrderBillingDetail->delivery_state = !empty($data['state_id']) ? $data['state_id'] : '';
        $OrderBillingDetail->save();

        $taxes  = Tax::TaxCount($data);
        // add in Order Coupon Detail table end
        if($taxes['tax_info'] != '[]'){
            $taxes = TaxMethod::where('name',$taxes['tax_name'])->where('theme_id', $theme_id)->where('store_id', $store->id)->orderBy('priority', 'asc')->get();

            $country = !empty($data['country_id']) ? $data['country_id'] :$data['country_id'];
            $state_id = !empty($data['state_id']) ? $data['state_id'] : $data['state_id'];
            $city_id = !empty($data['city_id']) ? $data['city_id'] : $data['city_id'];
            foreach ($taxes as $tax) {
                $countryMatch = (!$tax->country_id || $country == $tax->country_id);
                $stateMatch = (!$tax->state_id || $state_id == $tax->state_id);
                $cityMatch = (!$tax->city_id || $city_id == $tax->city_id);

                if ($countryMatch && $stateMatch && $cityMatch) {
                    $OrderTaxDetail = new OrderTaxDetail();
                    $OrderTaxDetail->order_id = $order->id;
                    $OrderTaxDetail->product_order_id = $order->product_order_id;
                    $OrderTaxDetail->tax_id = $taxes['tax_id'];
                    $OrderTaxDetail->tax_name = $tax->name;
                    $OrderTaxDetail->tax_discount_amount = $tax->tax_rate;
                    $OrderTaxDetail->tax_final_amount = $requests_data['tax_price'];
                    $OrderTaxDetail->theme_id = $theme_id;
                    $OrderTaxDetail->save();
                }
            }
        }
        //activity log
        ActivityLog::order_entry(['customer_id'=>$order->customer_id ,
        'order_id'=> $order->product_order_id ,
        'order_date' => $order->order_date ,
        'products' =>$order->product_id,
        'final_price' =>$order->final_price,
        'payment_type' =>$order->payment_type,
        'theme_id'=>$order->theme_id,
        'store_id'=>$order->store_id]);
        //Order Mail
        //$order_email = !empty($other_info->email) ? $other_info->email : '';
        $order_email = $data['email'] ?? (!empty($data['email']) ? $data['email'] : '');
        $owner=User::find($store->created_by);
        $owner_email=$owner->email;
        $order_id    = Crypt::encrypt($order->id);

        try
        {
            $dArr = [
            'order_id' => $order->product_order_id,
            ];

            // Send Email
            $resp = Utility::sendEmailTemplate('Order Created', $order_email, $dArr, $owner,$store, $order_id);
            $resp1=Utility::sendEmailTemplate('Order Created For Owner', $owner_email, $dArr,$owner, $store, $order_id);
        }
        catch(\Exception $e)
        {
            $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
        }

        $product_data = Product::find($order->product_id);

        if ($product_data) {
            if ($product_data->variant_product == 0) {
                if ($product_data->track_stock == 1) {
                    OrderNote::order_note_data([
                        'customer_id' => !empty($customer_id) ? $customer_id : '0',
                        'order_id' => $order->id,
                        'product_name' => !empty($product_data->name)?$product_data->name: '',
                        'variant_product' => $product_data->variant_product,
                        'product_stock' => !empty($product_data->product_stock) ? $product_data->product_stock : '',
                        'status' => 'Stock Manage',
                        'theme_id' => $order->theme_id,
                        'store_id' => $order->store_id,
                    ]);
                }
            } else {
                $variant_data = ProductVariant::find($product->variant_id);
                $variationOptions = explode(',', $variant_data->variation_option);
                $option = in_array('manage_stock', $variationOptions);
                if ($option == true) {
                    OrderNote::order_note_data([
                        'customer_id' => !empty($customer_id) ? $customer_id : '0',
                        'order_id' => !empty($order->id) ? $order->id : '',
                        'product_name' => !empty($product_data->name)?$product_data->name: '',
                        'variant_product' => $product_data->variant_product,
                        'product_variant_name' => !empty($variant_data->variant) ? $variant_data->variant : '',
                        'product_stock' => !empty($variant_data->stock) ? $variant_data->stock : '',
                        'status' => 'Stock Manage',
                        'theme_id' => $order->theme_id,
                        'store_id' => $order->store_id,
                    ]);
                }
            }
        }

        OrderNote::order_note_data([
            'customer_id' => !empty($customer_id) ? $customer_id : '0',
            'order_id' => $order->id,
            'product_order_id' => $order->product_order_id,
            'delivery_status' => 'Pending',
            'status' => 'Order Created',
            'theme_id' => $order->theme_id,
            'store_id' => $order->store_id
        ]);

        try{
            $msg = __("Hello, Welcome to $store->name .Hi,your order id is $order->product_order_id, Thank you for Shopping We received your purchase request, we'll be in touch shortly!. ") ;
            $mess = Utility::SendMsgs('Order Created',$OrderBillingDetail->telephone, $msg);
        } catch(\Exception $e)
        {
            $smtp_error = __('Invalid OAuth access token - Cannot parse access token');
        }
        // add in Order Tax Detail table end
        if (!empty($order) && !empty($OrderBillingDetail)) {
            $order_array['order_id'] = $order->id;
            $cart_array = [];
            $cart_json = json_encode($cart_array);
            Cookie::queue('cart', $cart_json, 1440);
            Session::forget('activeProductNames');
            $order_id_value = \Illuminate\Support\Facades\Crypt::encrypt($order->product_order_id ?? '') ?? '';
            return redirect()->route('ofertemag.order.summary', [$slug,$order_id_value]);
        } else {
            return $this->error(['message' => 'Somthing went wrong.']);
        }
    }

    public function ofertemag_order_summary($slug,$order_id)
    {
        $store = Store::where('slug',$slug)->first();
        $theme_id = $store->theme_id;
        if($order_id)
        {
            $id =crypt::decrypt($order_id);
            if($id)
            {
                $currentTheme = GetCurrenctTheme($slug);
                $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
                $data = getThemeSections($currentTheme,$slug, true, true);
                // Get Data from database
                $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
                $currency = Utility::GetValueByName('CURRENCY_NAME', $store->theme_id, $store->id);
                $languages = Utility::languages();
                $section = (object) $data['section'];
                $order_id =  $id;
                $orders_data = Order::where('product_order_id',$order_id)->where('store_id',$store->id)->first();
                // Order jason
                $order_complete_json_path = base_path('/theme_json/order-complete.json');
                $order_complete_json = json_decode(file_get_contents($order_complete_json_path), true);

                $order_complate_title = $order_complete_json['section']['title']['text'];
                $order_complate_description = $order_complete_json['section']['description']['text'];

                $setting_order_complete_json = AppSetting::select('theme_json')
                ->where('theme_id', $theme_id)
                ->where('page_name', 'order_complate')
                ->where('store_id', getCurrentStore())
                ->first();
                if (!empty($setting_order_complete_json)) {
                    $order_complete_json_array_data = json_decode($setting_order_complete_json->theme_json, true);

                    $order_complate_title = $order_complete_json_array_data['section']['title']['text'];
                    $order_complate_description = $order_complete_json_array_data['section']['description']['text'];
                }
                $order_complete_json_array["order-complate"]["order-complate-title"] = $order_complate_title . ' #' . $order_id;
                $order_complete_json_array["order-complate"]["order-complate-description"] = $order_complate_description;
                $product = Product::find($orders_data->product_id);
                $order = Order::order_detail($orders_data->id);
                $customer = Customer::find($orders_data->customer_id);
                $billing = OrderBillingDetail::where('product_order_id',$orders_data->product_order_id)->where('theme_id',$store->theme_id)->first();

                $currentDateTime = Carbon::now();

                $flashsales = FlashSale::where('theme_id', $store->theme_id)
                ->where('store_id', $store->id)
                ->where('is_active', 1)
                ->get();
                $productIds = [];
                $flashSaleDetails = [];

                foreach ($flashsales as $flashsale) {
                    $startDate = \Carbon\Carbon::parse($flashsale->start_date . ' ' . $flashsale->start_time);
                    $endDate = \Carbon\Carbon::parse($flashsale->end_date . ' ' . $flashsale->end_time);

                    if ($endDate < $startDate) {
                        $endDate->addDay();
                    }

                    $currentDateTimeClone = clone $currentDateTime;
                    $currentDateTime->setTimezone($startDate->getTimezone());
                    if ($currentDateTime <= $endDate) {
                        if ($flashsale->sale_product) {
                            $productIds = array_merge($productIds, explode(',', $flashsale->sale_product));
                        }
                        $flashSaleDetails = [
                            'title' => $flashsale->name,
                            'discount_amount' => $flashsale->discount_amount,
                            'discount_type' => $flashsale->discount_type,
                        ];
                        break;
                    }
                }
                $productIds = array_unique($productIds);
                $sale_product = Product::where('store_id', $store->id)
                ->where('theme_id', $store->theme_id)
                ->whereIn('id', $productIds)
                ->get();
                $response = Cart::cart_list_cookie($data, $store->id);
                $response = json_decode(json_encode($response));
                $cartlist = (array)$response->data;

                return view('front_end.sections.payment.order-summary', compact('slug','store','order_id','orders_data','currentTheme','currantLang','currency','languages','section','order_complate_title','order_complate_description','product','billing','flashsales','sale_product','flashSaleDetails','response','order')+$sqlData+$data);

            }
            else{
                return redirect()->route('landing_page',$slug);
            }
        }
        else{
            return redirect()->route('landing_page',$slug);
        }
    }

    private function processStripe($datas, $store)
    {
        $theme_id =$store->theme_id;
        $slug = $store->slug;
        $store = Store::where('slug',$slug)->first();
        $stripe_secret = \App\Models\Utility::GetValueByName('stripe_secret_key', $store->theme_id, $store->id);
        $CURRENCY_NAME = \App\Models\Utility::GetValueByName('CURRENCY_NAME', $store->theme_id, $store->id);
        $CURRENCY = \App\Models\Utility::GetValueByName('CURRENCY', $store->theme_id, $store->id);

        $orderID = $datas['customer_id'] . date('YmdHis');
        $cartlist_final_price = $datas['cartlist_final_price'];
        $totalprice = str_replace(' ', '', str_replace(',', '', str_replace($CURRENCY, '', $cartlist_final_price)));

        if ($totalprice > 0.0) {
            $l_name = $theme_id;
            $stripe_formatted_price = in_array(
                $CURRENCY_NAME,
                [
                    'MGA',
                    'BIF',
                    'CLP',
                    'PYG',
                    'DJF',
                    'RWF',
                    'GNF',
                    'UGX',
                    'JPY',
                    'VND',
                    'VUV',
                    'XAF',
                    'KMF',
                    'KRW',
                    'XOF',
                    'XPF',
                ]
            ) ? number_format($totalprice, 2, '.', '') : number_format($totalprice, 2, '.', '') * 100;

            $return_url_parameters = function ($return_type) {
                return '&return_type=' . $return_type . '&payment_processor=stripe';
            };
            Stripe\Stripe::setApiKey($stripe_secret);
            $data = \Stripe\Checkout\Session::create(
                [
                    'payment_method_types' => ['card'],
                        'line_items' => [
                            [
                                'price_data' => [
                                    'currency' => $CURRENCY_NAME,
                                    'unit_amount' => (int)$stripe_formatted_price,
                                    'product_data' => [
                                        'name' => $store->name,
                                        'description' => 'Stipe payment',
                                    ],
                                ],
                                'quantity' => 1,
                            ],
                        ],

                    'mode' => 'payment',
                    'success_url' => route(
                        'ofertemag.order.save',[
                            $slug,
                            $return_url_parameters('success'),
                        ]
                    ),
                    'cancel_url' => route(
                        'ofertemag.order.save', [
                            $slug,
                                $return_url_parameters('cancel'),
                            ]
                    ),
                ]

            );
            if (is_array($data)) {
                // Convert the array to an Illuminate\Http\Request object
                $request = Request::create('/', 'POST', $data);
            }
            Session::put('request_data', $datas);

            try {
                $place_order_data = ($data);
                return $place_order_data->url;
            } catch (\Exception $e) {
                return redirect()->route('checkout', $slug)->with('error', __('Transaction has been failed!'));
            }
        }
        return response()->json(['message' => 'Payment processed using Stripe']);
    }

    public function Product_cart_list(Request $request, $storeSlug)
    {
        $store = Store::where('slug',$storeSlug)->first();
        $currentTheme = $store->theme_id;
        $slug = $store->slug;
        if (!$request->shipping_price) {
            $shippingMethod = ShippingMethod::find($request->method_id);

            if ($shippingMethod) {
                $request['shipping_final_price'] = $shippingMethod->cost;
            } else {
                $request['shipping_final_price'] = 0;
            }
        } else {
            $request['shipping_final_price'] = $request->shipping_price ?? 0;
        }

        if (auth('customers')->guest()) {
            $response = Cart::cart_list_cookie($request->all(),$store->id);
            $response = json_decode(json_encode($response));


        } else {
            $request->merge(['customer_id' => auth('customers')->user()->id, 'store_id' => $store->id, 'slug' => $slug, 'theme_id' => $currentTheme]);

            $api = new ApiController();
            $data = $api->cart_list($request, $storeSlug);
            $response = $data->getData();
        }
        $return['status'] = $response->status;
        $return['message'] = $response->message;
        $return['sub_total'] = 0;
        $tax_option = TaxOption::where('store_id',$store->id)
        ->where('theme_id',$store->theme_id)
        ->pluck('value', 'name')->toArray();
        if ($response->status == 1) {
            $currency = Utility::GetValueByName('CURRENCY',$currentTheme, $store->id);
            $currency_name = Utility::GetValueByName('CURRENCY_NAME', $currentTheme, $store->id);
            $return['cart_total_product'] = $response->data->cart_total_product;
            $return['html'] = view('front_end.sections.pages.product-cart-list', compact('slug', 'response', 'currency', 'currency_name','tax_option','currentTheme', 'store'))->render();
        }
        return response()->json($return);
    }
}
