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


class OzowController extends Controller
{
    public static function generate_request_hash_check($inputString)
    {
        $stringToHash = strtolower($inputString);
        return \App\Http\Controllers\OzowController::get_sha512_hash($stringToHash);
    }

    public static function get_sha512_hash($stringToHash)
    {
        return hash('sha512', $stringToHash);
    }

    public function planPayWithOzow(Request $request)
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

                $siteCode       = isset($payment_setting['ozow_pay_Site_key']) ? $payment_setting['ozow_pay_Site_key'] : '';
                $privateKey     = isset($payment_setting['ozow_pay_private_key']) ? $payment_setting['ozow_pay_private_key'] : '';
                $apiKey         = isset($payment_setting['ozow_pay_api_key']) ? $payment_setting['ozow_pay_api_key'] : '';
                $isTest         = isset($payment_setting['ozow_mode']) && $payment_setting['ozow_mode'] == 'sandbox'  ? true : false;
                $plan_id        = $plan->id;


                $countryCode    = "ZA";
                $currencyCode   = 'ZAR' ?? $currency;
                $amount         = $price;
                $bankReference  = time().'FKU';
                $transactionReference = time();

                if($currencyCode != 'ZAR'){
                    return redirect()->back()->with('error', __('currency not supported'));
                }

                $cancelUrl  = route('plan.get.ozow.status', [
                                    $plan_id,
                                    'amount' => $price,
                                    'duration' => $plan->duration,
                                    'coupon_code' => $request->coupon,
                                ]);
                $errorUrl   = route('plan.get.ozow.status', [
                                    $plan_id,
                                    'amount' => $price,
                                    'duration' => $plan->duration,
                                    'coupon_code' => $request->coupon,
                                ]);
                $successUrl = route('plan.get.ozow.status', [
                                    $plan_id,
                                    'amount' => $price,
                                    'duration' => $plan->duration,
                                    'coupon_code' => $request->coupon,
                                ]);
                $notifyUrl  = route('plan.get.ozow.status', [
                                    $plan_id,
                                    'amount' => $price,
                                    'duration' => $plan->duration,
                                    'coupon_code' => $request->coupon,
                                ]);

                // Calculate the hash with the exact same data being sent
                $inputString    = $siteCode . $countryCode . $currencyCode . $amount . $transactionReference . $bankReference . $cancelUrl . $errorUrl . $successUrl . $notifyUrl . $isTest . $privateKey;
                $hashCheck      = $this->generate_request_hash_check($inputString);

                $data = [
                    "countryCode"           => $countryCode,
                    "amount"                => $amount,
                    "transactionReference"  => $transactionReference,
                    "bankReference"         => $bankReference,
                    "cancelUrl"             => $cancelUrl,
                    "currencyCode"          => $currencyCode,
                    "errorUrl"              => $errorUrl,
                    "isTest"                => $isTest, // boolean value here is okay
                    "notifyUrl"             => $notifyUrl,
                    "siteCode"              => $siteCode,
                    "successUrl"            => $successUrl,
                    "hashCheck"             => $hashCheck,
                ];
                $curl = curl_init();
                if ($isTest) {
                    $paymentRequestUrl = 'https://stagingapi.ozow.com/PostPaymentRequest';                    
                } else {
                    $paymentRequestUrl = 'https://api.ozow.com/postpaymentrequest';
                }
                
                curl_setopt_array($curl, array(
                    CURLOPT_URL             => $paymentRequestUrl,
                    CURLOPT_RETURNTRANSFER  => true,
                    CURLOPT_ENCODING        => '',
                    CURLOPT_MAXREDIRS       => 10,
                    CURLOPT_TIMEOUT         => 0,
                    CURLOPT_FOLLOWLOCATION  => true,
                    CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST   => 'POST',
                    CURLOPT_POSTFIELDS      => json_encode($data),
                    CURLOPT_HTTPHEADER      => array(
                        'Accept: application/json',
                        'ApiKey: '.$apiKey,
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $json_attendance = json_decode($response, true);

                if (isset($json_attendance['url']) && $json_attendance['url'] != null) {
                    return redirect()->away($json_attendance['url']);

                } else {
                    return redirect()
                        ->route('plan.index', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }

            } catch (\Exception $e) {
                return redirect()->route('plan.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }

    }

    public function planGetOzowStatus(Request $request, $plan_id)
    {
        $user = Auth::user();
        $plan = Plan::find($plan_id);
        $payment_setting = getSuperAdminAllSetting();
        $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';

        if ($plan) {
            try {
                if (isset($request['Status']) && $request['Status'] == 'Complete') {

                    $order = new PlanOrder();
                    $order->order_id = $request->TransactionId;
                    $order->name = $user->name;
                    $order->card_number = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year = '';
                    $order->plan_name = $plan->name;
                    $order->plan_id = $plan->id;
                    $order->price = !empty($request->amount) ? $request->amount : 0;
                    $order->price_currency = $currency;
                    $order->txn_id = time();
                    $order->payment_type = __('Ozow');
                    $order->payment_status = 'Succeeded';
                    $order->txn_id = '';
                    $order->receipt = '';
                    $order->user_id = $user->id;
                    $order->save();

                    if ($request->coupon_code) {
                        $coupons = PlanCoupon::whereRaw('BINARY `code` = ?', [$request->coupon_code])->where('is_active', '1')->first();
                        if (!empty($coupons)) {
                            $userCoupon         = new PlanUserCoupon();
                            $userCoupon->user_id   = $user->id;
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

    public function storeGetOzowStatus(Request $request, $plan_id)
    {
        $OzowPaySession = $request->session()->get('OzowPaySession');
        $request->session()->forget('OzowPaySession');
        $slug = $OzowPaySession['slug'];
        if ($slug) {
            try {
                if (isset($request['Status']) && $request['Status'] == 'Complete') {
                    return redirect()->route('store.payment.ozow',$slug);
                } else {
                    return redirect()->back()>with('error', __('Transaction has been failed.'));
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Oops something went wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Oops something went wrong.'));
        }
    }
}
