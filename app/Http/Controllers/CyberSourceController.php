<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\PlanCoupon;
use App\Models\Coupon;
use App\Models\PlanUserCoupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth;
class CyberSourceController extends Controller
{
    public function planPayWithCyberSource(Request $request)
    {

        $payment_setting = getSuperAdminAllSetting();
        $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);

        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $user = \Auth::user();
        if ($plan) {
            try {
                $price = $plan->price;
                if (!empty($request->coupon))
                {
                    $coupons = PlanCoupon::whereRaw('BINARY `code` = ?', [$request->coupon])->where('is_active', '1')->first();
                    if ($coupons) {
                        $coupon_code = $coupons->code;
                        $usedCoupun     = $coupons->used_coupon();
                        if ($coupons->limit == $usedCoupun) {
                            $res_data['error'] = __('This coupon code has expired.');
                        } else {
                            $discount_value = ($plan->price / 100) * $coupons->discount;
                            $price  = $price - $discount_value;
                            if ($price < 0) {
                                $price = $plan->price;
                            }
                            $coupon_id = $coupons->id;
                        }
                    }else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                $name = \Auth::user()->name;
                $callback_url = 'plan.get.cybersource.status';
                $data = [
                    'id' =>  $plan->id,
                    'amount' =>  $price,
                    'duration' =>  $plan->duration,
                    'coupon_code' =>  $request->coupon,
                ];
                $data  =    json_encode($data);
                try {
                    return view('plans.cybersourcerequest', compact('callback_url', 'price', 'data','name'));
                } catch (\Exception $e) {
                    \Log::error($e->getMessage());
                }
            } catch (\Exception $e) {
                return redirect()->route('plan.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planPayWithCyberSourceData(Request $request)
    {
        $input          = $request->all();
        $admin_settings = getSuperAdminAllSetting();
        $data           = json_decode($input['data'], true);
        $duration       =  $data['duration'];
        $amount         =  $data['amount'];
        $coupon_code    = $data['coupon_code'];

        $plan           = Plan::find($data['id']);
        $authuser       = \Auth::user();
        $orderID        = strtoupper(str_replace('.', '', uniqid('', true)));
        $admin_currancy = !empty($admin_settings['defult_currancy']) ? $admin_settings['defult_currancy'] : 'USD';
        $apiKey = isset($admin_settings['cybersource_pay_api_key']) ? $admin_settings['cybersource_pay_api_key'] : '';
        $merchantId = isset($admin_settings['cybersource_pay_merchant_id']) ? $admin_settings['cybersource_pay_merchant_id'] : '';
        $apiSecret = isset($admin_settings['cybersource_pay_secret_key']) ? $admin_settings['cybersource_pay_secret_key'] : '';

        try {

            $apiEndpoint = 'https://apitest.cybersource.com/pts/v2/payments';

            $requestData = [
                "clientReferenceInformation" => [
                    "code" => time(),
                ],
                "paymentInformation" => [
                    "card" => [
                        "number" => !empty($input['cardNumber']) ? $input['cardNumber'] : 0,
                        "expirationMonth" => !empty($input['month']) ? $input['month'] : 0,
                        "expirationYear" => !empty($input['year']) ? $input['year'] : 0,
                    ],
                ],
                "orderInformation" => [
                    "amountDetails" => [
                        "totalAmount" => $amount,
                        "currency" => $admin_currancy,
                    ],

                    "billTo" => [
                        "firstName" => $authuser->name,
                        "lastName" => $request->last_name,
                        "address1" => $request->address,
                        "locality" => $request->locality,
                        "administrativeArea" => $request->administrativearea,
                        "postalCode" => $request->postalcode,
                        "country" => $request->country,
                        "email" => $authuser->email,
                        "phoneNumber" => $request->phone_no
                    ],
                ],
            ];

            $vCDate = gmdate('D, d M Y H:i:s T');
            $digest = 'SHA-256=' . base64_encode(hash('sha256', json_encode($requestData), true));
            $signatureString = '(request-target): post /pts/v2/payments' . "\n" .
                'host: apitest.cybersource.com' . "\n" .
                'digest: ' . $digest . "\n" .
                'v-c-merchant-id: ' . $merchantId;
            $signature = base64_encode(hash_hmac('sha256', $signatureString, base64_decode(trim($apiSecret)), true));

            // Headers
            $headers = [
                'host' => 'apitest.cybersource.com',
                'signature' => 'keyid="' . trim($apiKey) . '", algorithm="HmacSHA256", headers="(request-target) host digest v-c-merchant-id", signature="' . trim($signature) . '"',
                'digest' => $digest,
                'v-c-merchant-id' => trim($merchantId),
                'v-c-date' => $vCDate,
                'Content-Type' => 'application/json',
            ];

            // Make the API request
            $response = \Http::withHeaders($headers)->post($apiEndpoint, $requestData);

            if ($response->successful()) {
                $result = $response->json();
                if ($result['status'] == "AUTHORIZED") {

                    $order = PlanOrder::create([
                        'order_id'          => $orderID,
                        'name'              => !empty($input['owner']) ? $input['owner'] : 0,
                        'email'             => null,
                        'card_number'       => !empty($input['cardNumber']) ? $input['cardNumber'] : 0,
                        'card_exp_month'    => !empty($input['month']) ? $input['month'] : 0,
                        'card_exp_year'     => !empty($input['year']) ? $input['year'] : 0,
                        'plan_name'         => !empty($plan->name) ? $plan->name : 'Basic Package',
                        'plan_id'           => $plan->id,
                        'price'             => $amount,
                        'price_currency'    => $admin_currancy,
                        'txn_id'            => time(),
                        'payment_type'      => __('Cybersource'),
                        'payment_status'    => 'Succeeded',
                        'receipt'           => null,
                        'user_id'           => $authuser->id,
                    ]);

                    if (isset($coupon_code) && !empty($coupon_code)) {

                        $coupons = PlanCoupon::whereRaw('BINARY `code` = ?', [$coupon_code])->where('is_active', '1')->first();
                        if (!empty($coupons)) {
                            $userCoupon         = new PlanUserCoupon();
                            $userCoupon->user_id   = $authuser->id;
                            $userCoupon->coupon_id = $coupons->id;
                            $userCoupon->order  = $order->order_id;
                            $userCoupon->save();
                            $usedCoupun = $coupons->used_coupon();
                            if ($coupons->limit <= $usedCoupun) {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }
                    }

                    $assignPlan = $authuser->assignPlan($plan->id);

                    if ($assignPlan['is_success']) {
                        return redirect()->route('plan.index')->with('success', __('Plan activated Successfully!'));
                    } else {
                        return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
                    }
                }
            } else {
                return redirect()->route('plan.index')->with('error', __('Your Transaction is fail please try again'));
            }
        } catch (\Exception $e) {
            return redirect()->route('plan.index')->with('error', __('something Went wrong!'));
        }
    }

    public function storePayWithCyberSourceData(Request $request)
    {
        $input          = $request->all();
        $data           = json_decode($input['data'], true);
        $amount         =  $data['amount'];
        $id         =  $data['theme_id'];
        $slug = $data['slug'];
        $authuser       = $data['user'];
        $admin_currancy  = !empty(\App\Models\Utility::GetValueByName('defult_currancy',$id)) ?  \App\Models\Utility::GetValueByName('defult_currancy',$id) : '$';
        $apiKey        = ( \App\Models\Utility::GetValueByName('cybersource_api_key',$id)) ?  \App\Models\Utility::GetValueByName('cybersource_api_key',$id) : '';
        $merchantId     = ( \App\Models\Utility::GetValueByName('cybersource_merchant_id',$id)) ?  \App\Models\Utility::GetValueByName('cybersource_merchant_id',$id) : '';
        $apiSecret  =  ( \App\Models\Utility::GetValueByName('cybersource_secret_key',$id)) ?  \App\Models\Utility::GetValueByName('cybersource_secret_key',$id) : 'sandbox';


        try {

            $apiEndpoint = 'https://apitest.cybersource.com/pts/v2/payments';

            $requestData = [
                "clientReferenceInformation" => [
                    "code" => time(),
                ],
                "paymentInformation" => [
                    "card" => [
                        "number" => !empty($input['cardNumber']) ? $input['cardNumber'] : 0,
                        "expirationMonth" => !empty($input['month']) ? $input['month'] : 0,
                        "expirationYear" => !empty($input['year']) ? $input['year'] : 0,
                    ],
                ],
                "orderInformation" => [
                    "amountDetails" => [
                        "totalAmount" => $amount,
                        "currency" => $admin_currancy,
                    ],

                    "billTo" => [
                        "firstName" => $authuser['name'],
                        "lastName" => $request->last_name,
                        "address1" => $request->address,
                        "locality" => $request->locality,
                        "administrativeArea" => $request->administrativearea,
                        "postalCode" => $request->postalcode,
                        "country" => $request->country,
                        "email" => $authuser['email'],
                        "phoneNumber" => $request->phone_no
                    ],
                ],
            ];

            $vCDate = gmdate('D, d M Y H:i:s T');
            $digest = 'SHA-256=' . base64_encode(hash('sha256', json_encode($requestData), true));
            $signatureString = '(request-target): post /pts/v2/payments' . "\n" .
                'host: apitest.cybersource.com' . "\n" .
                'digest: ' . $digest . "\n" .
                'v-c-merchant-id: ' . $merchantId;
            $signature = base64_encode(hash_hmac('sha256', $signatureString, base64_decode($apiSecret), true));

            // Headers
            $headers = [
                'host' => 'apitest.cybersource.com',
                'signature' => 'keyid="' . $apiKey . '", algorithm="HmacSHA256", headers="(request-target) host digest v-c-merchant-id", signature="' . $signature . '"',
                'digest' => $digest,
                'v-c-merchant-id' => $merchantId,
                'v-c-date' => $vCDate,
                'Content-Type' => 'application/json',
            ];

            // Make the API request
            $response = \Http::withHeaders($headers)->post($apiEndpoint, $requestData);
            return redirect()->route('store.payment.senang-pay',$slug);
        } catch (\Exception $e) {
            return redirect()->route('checkout',$slug)->with('error', __('something Went wrong!'));
        }
    }
}
