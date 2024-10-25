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
use Illuminate\Http\RedirectResponse;

class SofortController extends Controller
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

                Stripe\Stripe::setApiKey($admin_payment_setting['sofort_secret_key']);
                $planID = \Illuminate\Support\Facades\Crypt::encrypt($request->plan_id);
                if ($price > 0.0) {
                    $session = Session::create([
                        'payment_method_types' => ['sofort'],
                        'line_items' => [[
                            'price_data' => [
                                'currency' => $currency,
                                'product_data' => [
                                    'name' => 'Example Product',
                                ],
                                'unit_amount' => $price
                            ],
                            'quantity' => 1,
                        ]],
                        'mode' => 'payment',
                        'success_url' => route('plan.payment.success', ['plan_id' => $planID,'coupon_code'=>$request->coupon_code,'amount' => $price,'plan' => $plan->id]),
                        'cancel_url' => route('plan.payment.failure'),
                    ]);
                }
                return new RedirectResponse($session->url);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('error', __('Plan is deleted.'));
        }
    }

    public function success(Request $request){
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $admin_payment_setting = getSuperAdminAllSetting();
        $plan           = Plan::find($request->plan);
        $user = \Auth::user();
        $couponCode = $request->coupon;
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
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
        $order->payment_type   = __('Sofort');
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
    }

    public function failure(){
        return redirect()->route('plan.index')->with('error', __('Your payment has failed.'));
    }
}
