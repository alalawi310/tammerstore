<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Plan;
use App\Models\PlanCoupon;
use App\Models\PlanOrder;
use App\Models\PlanUserCoupon;
use Illuminate\Http\Request;
use App\Models\Senangpay;

class SenangPayController extends Controller
{
    public function planPayWithSenangpay(Request $request)
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

                try {
                    $transactionId = uniqid();

                    $senangPaySession = [
                        'order_id' => $orderID,
                        'plan_id' => $plan->id,
                        'price' => $price,
                        'coupon' => !empty($request->coupon) ? $request->coupon : '',
                    ];
                    $request->session()->put('senangPaySession', $senangPaySession);

                    $request = [
                        'full_name' => $user->name,
                        'email' => $user->email,
                        'contact_number' => $user->mobile_no
                    ];
                    $senangPay = new Senangpay();
                    $senangPay->setSendPaymentDetails($request, $user->name, $orderID, $price, $user->id);
                    return redirect($senangPay->processPayment());

                } catch (\Exception $e) {
                    \Log::debug($e->getMessage());
                    return redirect()->route('plan.index')->with('error', $e->getMessage());
                }

            } catch (\Exception $e) {
                return redirect()->route('plan.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function paymentCallback(Request $request)
    {
        $senangPaySession = $request->session()->get('senangPaySession');
        $request->session()->forget('senangPaySession');

        if ($request->status_id == 1) {
            if(isset($senangPaySession['plan_id']) && !empty($senangPaySession['plan_id'])){
                $payment_setting = getSuperAdminAllSetting();
                $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
                $user = \Auth::user();
                $plan = Plan::find($senangPaySession['plan_id']);

                $order = new PlanOrder();
                $order->order_id = $senangPaySession['order_id'];
                $order->name = $user->name;
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->plan_name = $plan->name;
                $order->plan_id = $plan->id;
                $order->price = !empty($senangPaySession['price']) ? $senangPaySession['price'] : 0;
                $order->price_currency = $currency;
                $order->txn_id = time();
                $order->payment_type = __('SenangPay');
                $order->payment_status = 'Succeeded';
                $order->receipt = '';
                $order->user_id = $user->id;
                $order->save();

                if ($senangPaySession['coupon']) {
                    $coupons = PlanCoupon::whereRaw('BINARY `code` = ?', [$senangPaySession['coupon']])->where('is_active', '1')->first();
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
                    return redirect()->route('plan.index')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
                }
            }elseif(isset($senangPaySession['slug']) && !empty($senangPaySession['slug'])){
                $slug=$senangPaySession['slug'];
                return redirect()->route('store.payment.senang-pay',$slug);
            }else{
                return redirect()->back()->with('error', 'Oops something went wrong.');
            }
        } else {
            return redirect()->back()->with('error', 'Transaction has been failed.');
        }
    }
}
