<?php

namespace App\Http\Controllers;
use App\Models\Plan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\PlanCoupon;
use App\Models\PlanOrder;
use Illuminate\Support\Facades\Auth;
use App\Models\PlanUserCoupon;
use Braintree\Gateway;


class BraintreeController extends Controller
{

    // Payment UI
    public function pay(Request $request)
    {
        $clientToken = $request->token;
        $orderID = $request->orderID;
        $action = $request->action;
        $return_url = $request->return_url;
        $user = $request->user;
        $amount = $request->amount;
        return view('plans.Braintreepayment', compact('clientToken', 'orderID', 'action', 'return_url', 'user', 'amount'));
    }

    // Plan Payment
    public function planPayWithBraintree(Request $request)
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
                    $coupons = PlanCoupon::where('code', $request->coupon)->where('is_active', '1')->first();
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

                $session = $request->toArray();
                $session['plan_id'] = $plan->id;
                $session['amount'] = $price;
                $session['order_id'] = $orderID;
                $session['coupon'] = $request->coupon != '' ? $request->coupon : '';
                $request->session()->put($orderID, $session);

                $config = new \Braintree\Configuration([
                    'environment' => $payment_setting['braintree_mode'] ?? 'sandbox',
                    'merchantId' => $payment_setting['braintree_pay_merchant_id'] ?? '',
                    'publicKey' => $payment_setting['braintree_pay_public_key'] ?? '',
                    'privateKey' => $payment_setting['braintree_pay_private_key'] ?? ''
                ]);
                $gateway = new \Braintree\Gateway($config);
                $clientToken = $gateway->clientToken()->generate();

                if (!empty($clientToken)) {

                    $url = route('braintree.pay', [
                        'token' => $clientToken,
                        'orderID' => $orderID,
                        'action' => route('plan.braintree.status'),
                        'return_url' => route('plan.index'),
                        'user' => ' ',
                        'amount' => $currency . ' ' . $price,
                    ]);
                    return redirect()->away($url);
                } else {
                    return redirect()->route('plan.index')->with('error', __('Transaction failed'));
                }
            } catch (\Exception $e) {
                return redirect()->route('plan.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetBraintreeStatus(Request $request)
    {

        try {

            if (isset($request->order_id) || isset($request->payment_method_nonce)) {

                $session = (object) $request->session()->get($request->order_id);
                $request->session()->forget($request->order_id);
                $user = Auth::user();
                $plan = Plan::find($session->plan_id);
                $payment_setting = getSuperAdminAllSetting();
                $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';

                $config = new \Braintree\Configuration([
                    'environment' => $payment_setting['braintree_mode'] ?? 'sandbox',
                    'merchantId' => $payment_setting['braintree_pay_merchant_id'] ?? '',
                    'publicKey' => $payment_setting['braintree_pay_public_key'] ?? '',
                    'privateKey' => $payment_setting['braintree_pay_private_key'] ?? ''
                ]);

                $gateway = new \Braintree\Gateway($config);

                $result = $gateway->transaction()->sale([
                    'amount' => $session->amount,
                    'paymentMethodNonce' => $request->payment_method_nonce,
                    'options' => ['submitForSettlement' => True]
                ]);
                if ($result->success) {

                    $order = new PlanOrder();
                    $order->order_id = $request->order_id;
                    $order->name = $user->name;
                    $order->card_number = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year = '';
                    $order->plan_name = $plan->name;
                    $order->plan_id = $plan->id;
                    $order->price = !empty($session->amount) ? $session->amount : 0;
                    $order->price_currency = $currency;
                    $order->txn_id = time();
                    $order->payment_type = __('Braintree');
                    $order->payment_status = 'Succeeded';
                    $order->txn_id = '';
                    $order->receipt = '';
                    $order->user_id = $user->id;
                    $order->save();

                    if ($session->coupon) {
                        $coupons = PlanCoupon::where('code', $session->coupon)->where('is_active', '1')->first();
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
                        \Session::flash('success', 'Plan activated Successfully!');
                        return response()->json(['success' => true, 'return_url' => route('plan.index')]);
                    } else {
                        \Session::flash('error', $assignPlan['error']);
                        return response()->json(['success' => false, 'return_url' => route('plan.index')]);
                    }
                } else if ($result->transaction) {
                    \Session::flash('error', $result->transaction->processorResponseText);
                    return response()->json(['success' => false, 'return_url' => route('plan.index')]);
                } else {
                    foreach ($result->errors->deepAll() as $error) {
                        \Session::flash('error', 'Your Payment has failed!');
                        return response()->json(['success' => false, 'return_url' => route('plan.index')]);
                    }
                }
            } else {
                \Session::flash('error', 'Your Payment has failed!');
                return response()->json(['success' => false, 'return_url' => route('plan.index')]);
            }
        } catch (\Exception $e) {
            \Session::flash('error', $e->getMessage());
            return response()->json(['success' => false, 'return_url' => route('plan.index')]);
        }
    }

    public function storeGetBraintreeStatus(Request $request)
    {
        if (isset($request->order_id) || isset($request->payment_method_nonce)) {

            $session = (object) $request->session()->get('\'' . $request->order_id . '\'');
            $request->session()->forget($request->order_id);
            $slug = $session->slug;
            try {
                $config = new \Braintree\Configuration([
                    'environment' => \App\Models\Utility::GetValueByName('braintree_mode', $session->theme_id, $session->store_id) ?? 'sandbox',
                    'merchantId' => \App\Models\Utility::GetValueByName('braintree_pay_merchant_id', $session->theme_id, $session->store_id) ?? '',
                    'publicKey' => \App\Models\Utility::GetValueByName('braintree_pay_public_key', $session->theme_id, $session->store_id) ?? '',
                    'privateKey' => \App\Models\Utility::GetValueByName('braintree_pay_private_key', $session->theme_id, $session->store_id) ?? '',
                ]);

                $gateway = new \Braintree\Gateway($config);
                $result = $gateway->transaction()->sale([
                    'amount' => $session->amount,
                    'paymentMethodNonce' => $request->payment_method_nonce,
                    'options' => ['submitForSettlement' => True]
                ]);

                if ($result->success) {
                    return response()->json(['success' => true, 'return_url' => route('store.payment.braintree', $slug)]);
                } else if ($result->transaction) {
                    \Session::flash('error', $result->transaction->processorResponseText);
                    return response()->json(['success' => false, 'return_url' => route('checkout', $slug)]);
                } else {
                    foreach ($result->errors->deepAll() as $error) {
                        \Session::flash('error', 'Your Payment has failed!');
                        return response()->json(['success' => false, 'return_url' => route('checkout', $slug)]);
                    }
                }
            } catch (\Exception $e) {
                \Session::flash('error', $e->getMessage());
                return response()->json(['success' => false, 'return_url' => route('checkout', $slug)]);
            }
        } else {
            \Session::flash('error', 'Your Payment has failed!');
            return response()->json(['success' => false, 'return_url' => back()]);
        }

    }
}
