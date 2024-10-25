<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Exception;
use \Paynow\Payments\Paynow;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\PlanCoupon;
use App\Models\PlanOrder;
use Illuminate\Support\Facades\Auth;
use App\Models\PlanUserCoupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class PaynowController extends Controller
{
    public static function paynowgetstatus($pay)
    {
        $paynow = new Paynow($pay['integration_id'], $pay['integration_key'], $pay['success_url'], $pay['faild_url']);
        $pollUrl = Session::get('pollUrl');
        Session::forget('pollUrl');
        $status = $paynow->pollTransaction($pollUrl);
        $result = $status->paid();
        return $result;
    }
    public static function paynowPayment($pay)
    {
        $paynow = new Paynow($pay['integration_id'], $pay['integration_key'], $pay['success_url'], $pay['faild_url']);
        $payment = $paynow->createPayment($pay['order_id'], $pay['email']);
        $payment->add($pay['item'], $pay['amount']);
        $response = $paynow->send($payment);

        if ($response->success()) {
            $link = $response->redirectUrl();
            $pollUrl = $response->pollUrl();
            return redirect()->to($link)->with('pollUrl', $pollUrl);
        } else {
            return redirect()->route('plan.index')->with('error', __('Payment initiation failed.'));
        }

    }

    public function planPayWithPaynow(Request $request)
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
                if (!empty($request->coupon)) {
                    $coupons = PlanCoupon::whereRaw('BINARY `code` = ?', [$request->coupon])->where('is_active', '1')->first();
                    if ($coupons) {
                        $coupon_code = $coupons->code;
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit == $usedCoupun) {
                            $res_data['error'] = __('This coupon code has expired.');
                        } else {
                            $discount_value = ($plan->price / 100) * $coupons->discount;
                            $price = $price - $discount_value;
                            if ($price < 0) {
                                $price = $plan->price;
                            }
                            $coupon_id = $coupons->id;
                        }
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }

                $integration_id = isset($payment_setting['paynow_pay_integration_id']) ? $payment_setting['paynow_pay_integration_id'] : '';
                $integration_key = isset($payment_setting['paynow_pay_integration_key']) ? $payment_setting['paynow_pay_integration_key'] : '';
                $merchant_email = isset($payment_setting['paynow_pay_merchant_email']) ? $payment_setting['paynow_pay_merchant_email'] : '';
                $mode = isset($payment_setting['paynow_mode']) && $payment_setting['paynow_mode'] == 'sandbox' ? 'sandbox' : 'production';
                $plan_id = $plan->id;
                $success_url = route('plan.get.Paynow.status', [
                    $plan_id,
                    'amount' => $price,
                    'coupon_code' => $request->coupon,
                    'order_id' => $orderID,
                ]);
                $faild_url = route('plan.get.Paynow.status', [
                    $plan_id,
                    'amount' => $price,
                    'coupon_code' => $request->coupon,
                    'order_id' => $orderID,
                ]);

                if ($mode == 'sandbox') {
                    $email = $merchant_email;
                } elseif ($mode == 'production') {
                    $email = $user->email;
                }

                $pay = [
                    'integration_id' => $integration_id,
                    'integration_key' => $integration_key,
                    'success_url' => $success_url,
                    'faild_url' => $faild_url,
                    'email' => $email,
                    'order_id' => $orderID,
                    'item' => 'plan-' . $plan->name,
                    'amount' => $price,
                ];

                $response = $this->paynowPayment($pay);
                return new RedirectResponse($response->getTargetUrl());
            } catch (\Exception $e) {
                return redirect()->route('plan.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }

    }

    public function planGetPaynowStatus(Request $request, $plan_id)
    {
        $plan = Plan::find($plan_id);
        if ($plan) {
            try {
                $user = Auth::user();
                $payment_setting = getSuperAdminAllSetting();
                $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
                $integration_id = isset($payment_setting['paynow_pay_integration_id']) ? $payment_setting['paynow_pay_integration_id'] : '';
                $integration_key = isset($payment_setting['paynow_pay_integration_key']) ? $payment_setting['paynow_pay_integration_key'] : '';
                $merchant_email = isset($payment_setting['paynow_pay_merchant_email']) ? $payment_setting['paynow_pay_merchant_email'] : '';
                $mode = isset($payment_setting['paynow_mode']) && $payment_setting['paynow_mode'] == 'sandbox' ? 'sandbox' : 'production';

                $success_url = route('plan.get.Paynow.status', [
                    $plan_id,
                    'amount' => $request->amount,
                    'coupon_code' => $request->coupon,
                    'order_id' => $request->order_id,
                ]);
                $faild_url = route('plan.get.Paynow.status', [
                    $plan_id,
                    'amount' => $request->amount,
                    'coupon_code' => $request->coupon,
                    'order_id' => $request->order_id,
                ]);

                if ($mode == 'sandbox') {
                    $email = $merchant_email;
                } elseif ($mode == 'production') {
                    $email = $user->email;
                }

                $pay = [
                    'integration_id' => $integration_id,
                    'integration_key' => $integration_key,
                    'success_url' => $success_url,
                    'faild_url' => $faild_url,
                    'order_id' => $request->order_id,
                ];

                $result = $this->paynowgetstatus($pay);

                if ($result) {

                    $order = new PlanOrder();
                    $order->order_id = $request->order_id;
                    $order->name = $user->name;
                    $order->card_number = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year = '';
                    $order->plan_name = $plan->name;
                    $order->plan_id = $plan->id;
                    $order->price = !empty($request->amount) ? $request->amount : 0;
                    $order->price_currency = $currency;
                    $order->txn_id = time();
                    $order->payment_type = __('Paynow');
                    $order->payment_status = 'Succeeded';
                    $order->txn_id = '';
                    $order->receipt = '';
                    $order->user_id = $user->id;
                    $order->save();

                    if ($request->coupon_code) {
                        $coupons = PlanCoupon::whereRaw('BINARY `code` = ?', [$request->coupon_code])->where('is_active', '1')->first();
                        if (!empty($coupons)) {
                            $userCoupon = new PlanUserCoupon();
                            $userCoupon->user_id = $user->id;
                            $userCoupon->coupon_id = $coupons->id;
                            $userCoupon->order = $order->order_id;
                            $userCoupon->save();
                            $usedCoupun = $coupons->used_coupon();
                            if ($coupons->limit <= $usedCoupun) {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }
                    }

                    $assignPlan = $user->assignPlan($plan->id);

                    if ($assignPlan['is_success']) {
                        return redirect()->route('plan.index')->with('success', __('Plan activated Successfully.'));
                    } else {
                        return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
                    }
                } else {
                    return redirect()->route('plan.index')->with('error', __('Transaction has been failed.'));
                }
            } catch (\Exception $e) {
                return redirect()->route('plan.index')->with('error', __('Oops something went wrong.'));
            }
        } else {
            return redirect()->route('plan.index')->with('error', __('Oops something went wrong.'));
        }
    }

    public function storeGetPaynowStatus(Request $request)
    {
        $data = (object) $request->data;

        if ($data->slug) {

            try {
                $integration_id =  \App\Models\Utility::GetValueByName('paynow_pay_integration_id',$data->theme_id, $data->store_id) ? \App\Models\Utility::GetValueByName('paynow_pay_integration_id',$data->theme_id, $data->store_id) : '';
                $integration_key = \App\Models\Utility::GetValueByName('paynow_pay_integration_key',$data->theme_id, $data->store_id) ? \App\Models\Utility::GetValueByName('paynow_pay_integration_key',$data->theme_id, $data->store_id) : '';
                $merchant_email = \App\Models\Utility::GetValueByName('paynow_pay_merchant_email',$data->theme_id, $data->store_id) ? \App\Models\Utility::GetValueByName('paynow_pay_merchant_email',$data->theme_id, $data->store_id) : '';
                $mode = \App\Models\Utility::GetValueByName('paynow_mode',$data->theme_id, $data->store_id) && \App\Models\Utility::GetValueByName('paynow_mode',$data->theme_id, $data->store_id) == 'sandbox'  ? 'sandbox' : 'production';

                $tem_data =[
                    'slug'=>$data->slug,
                    'amount' => $data->amount,
                    'theme_id' =>  $data->theme_id,
                    'store_id' =>  $data->store_id,
                    'order_id' => $data->order_id,
                    'email' => $data->email,
                ];

                $success_url = route('store.get.Paynow.status',['data'=> $tem_data]);

                $faild_url = route('store.get.Paynow.status',['data'=> $tem_data]);

                if($mode == 'sandbox'){
                    $email = $merchant_email;
                }elseif($mode == 'production'){
                    $email = $data->email;
                }

                $pay = [
                    'integration_id' => $integration_id,
                    'integration_key' => $integration_key,
                    'success_url' => $success_url,
                    'faild_url' => $faild_url,
                    'order_id' => $data->order_id,
                ];

                $result = $this->paynowgetstatus($pay);

                if ($result) {
                    return redirect()->route('store.payment.Paynow', $data->slug);
                } else {
                    return redirect()->route('checkout', $data->slug)->with('error', __('Transaction has been failed.'));
                }
            } catch (\Exception $e) {
                return redirect()->route('checkout', $data->slug)->with('error', __('Oops something went wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Oops something went wrong.'));
        }
    }
}
