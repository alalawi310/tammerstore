<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use Stripe\Checkout\Session;
use App\Models\User;
use App\Models\PlanOrder;
use App\Models\PlanUserCoupon;
use App\Models\PlanCoupon;
use Stripe;
use Dipesh79\LaravelEsewa\LaravelEsewa as eSewa;
use Illuminate\Http\RedirectResponse;

class ESewaPaymentController extends Controller
{
    //
    public function addpayment(Request $request){
        $objUser               = \Auth::user();
        $planID                = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan                  = Plan::find($planID);
        $admin_payment_setting = getSuperAdminAllSetting($objUser->id, getCurrentStore(), APP_THEME());
        $currency = $admin_payment_setting['CURRENCY_NAME'];
        if ($plan) {
            try {
                $price = $plan->price;
                if (!empty($request->coupon)) {
                    $coupons = PlanCoupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun     = $coupons->used_coupon();
                        $discount_value = ($plan->price / 100) * $coupons->discount;
                        $price          = $plan->price - $discount_value;

                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                config([
                    'esewa.scd' => isset($admin_payment_setting['esewa_merchant_key']) ? $admin_payment_setting['esewa_merchant_key'] : '',
                    'esewa.env' => ucfirst(isset($admin_payment_setting['esewa_mode']) ? $admin_payment_setting['esewa_mode'] : 'Sandbox'),
                ]);
                $payment    = new eSewa();
                $successURL = route('plan.esewa.payment.success', ['plan_id' => $planID,'coupon_code'=>$request->coupon_code,'amount' => $price,'plan' => $plan->id, 'status' => 'success']);
                $faildURL   = route('plan.esewa.payment.failure', ['status' => 'faild']);
                $pay        =  $payment->esewaCheckout($price, 0, 0, 0, $planID, $successURL, $faildURL);

                return redirect()->away($pay);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('error', __('Plan is deleted.'));
        }
    }

    public function success(Request $request){
        $admin_payment_setting = getSuperAdminAllSetting();
        $plan           = Plan::find($request->plan_id);
        $user = \Auth::user();
        $couponCode = $request->coupon;
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($request->status == 'success') {
            if($request->has('coupon_code') && $request->coupon_code != '')
            {
                    $coupons = PlanCoupon::where('code', strtoupper($couponCode))->where('is_active', '1')->first();
                    if(!empty($coupons))
                    {
                        $userCoupon         = new PlanUserCoupon();
                        $userCoupon->user_id   = $user->id;
                        $userCoupon->coupon_id = $coupons->id;
                        $userCoupon->order  = $orderID;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if($coupons->limit <= $usedCoupun)
                        {
                            $coupons->is_active = 0;
                            $coupons->save();
                    }
                }
            }
            $order                 = new PlanOrder();
            $order->order_id       = $orderID;
            $order->name           = $user->name;
            $order->card_number    = '';
            $order->card_exp_month = '';
            $order->card_exp_year  = '';
            $order->plan_name      = $plan->name;
            $order->plan_id        = $plan->id;
            $order->price          = $request->amount;
            $order->price_currency = $admin_payment_setting['CURRENCY_NAME'];
            $order->txn_id         = '';
            $order->payment_type   = __('ESewa');
            $order->payment_status = 'success';
            $order->receipt        = '';
            $order->user_id        = $user->id;
            $order->save();

            if(!empty($request->coupon))
            {
                $userCoupon         = new PlanUserCoupon();
                $userCoupon->user   = $user->id;
                $userCoupon->coupon = $coupons->id;
                $userCoupon->order  = $orderID;
                $userCoupon->save();

                $usedCoupun = $coupons->used_coupon();
                if($coupons->limit <= $usedCoupun)
                {
                    $coupons->is_active = 0;
                    $coupons->save();
                }

            }
            $assignPlan = $user->assignPlan($plan->id);
            if($assignPlan['is_success'])
            {
                return redirect()->route('plan.index')->with('success', __('Plan activated Successfully.'));
            }else {
                return redirect()->back()->with('error', __($assignPlan['error']));
            }
        } else {
            return redirect()->route('plan.index')->with('error', __('Your Payment has failed!'));
        }
    }

    public function failure(){
        return redirect()->route('plan.index')->with('error', __('Your payment has failed.'));
    }

    public function Transactionfailure($slug){
        return redirect()->route('checkout', $slug)->with('error', __('Transaction has been failed!'));
    }
}
