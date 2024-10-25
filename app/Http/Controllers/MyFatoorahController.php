<?php

namespace App\Http\Controllers;

use App\Events\AddAdditionalFields;
use App\Events\AddRewardClubPoint;
use App\Events\GetProductStatus;
use App\Http\Controllers\Api\ApiController;
use App\Models\ActivityLog;
use App\Models\Cart;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderBillingDetail;
use App\Models\OrderCouponDetail;
use App\Models\OrderNote;
use App\Models\OrderTaxDetail;
use App\Models\Plan;
use App\Models\PlanCoupon;
use App\Models\PlanOrder;
use App\Models\PlanUserCoupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\Store;
use App\Models\TaxMethod;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
use MyFatoorah\Library\MyFatoorah;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentEmbedded;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
use Exception;
use Illuminate\Support\Facades\Session;
use Nwidart\Modules\Facades\Module;

class MyFatoorahController extends Controller {

    public function planPayWithmyfatoorah(Request $request)
    {
        $payment_setting = getSuperAdminAllSetting();

        $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);

        $plan = Plan::find($planID);
        $orderId = strtoupper(str_replace('.', '', uniqid('', true)));
        $user = \Auth::user();

        $mfConfig = [
            'apiKey' => isset($payment_setting['myfatoorah_pay_api_key']) ? $payment_setting['myfatoorah_pay_api_key'] : '',
            'isTest' => isset($payment_setting['myfatoorah_mode']) && $payment_setting['myfatoorah_mode'] == 'sandbox' ? true : false,
            'countryCode' => isset($payment_setting['myfatoorah_pay_country_iso']) ? $payment_setting['myfatoorah_pay_country_iso'] : '',
        ];

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

                try {
                    $allowedCurrencies = ['SAR', 'QAR', 'OMR', 'KWD', 'BHD', 'JOD', 'AED', 'USD'];

                    if (!in_array($currency, $allowedCurrencies))
                    {
                      return redirect()->route('plan.index')->with('error', __('Selected currency is not supported for MyFatoorah payment.'));
                    }

                    $invoiceItems[] = [
                        'ItemName'  => 'Plan name-'.$plan->name,
                        'Quantity'  => '1',
                        'UnitPrice' => $price,
                        ];
                    //For example: pmid=0 for MyFatoorah invoice or pmid=1 for Knet in test mode
                    $paymentId = request('pmid') ?: 0;
                    $sessionId = request('sid') ?: null;

                    $callbackURL = route('myfatoorah.call_back');

                    $curlData = [
                        'CustomerName' => $user->name,
                        'InvoiceValue' => $price,
                        'DisplayCurrencyIso' => $currency,
                        'CustomerEmail' => $user->email,
                        'CallBackUrl' => $callbackURL,
                        'ErrorUrl' => $callbackURL,
                        'MobileCountryCode' => '+965',
                        'CustomerMobile' => $user->mobile_no,
                        'Language' => 'en',
                        'InvoiceItems' => $invoiceItems,
                        'CustomerReference' => $orderId,
                        'SourceInfo' => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION
                    ];

                    $mfObj = new MyFatoorahPayment($mfConfig);
                    $payment = $mfObj->getInvoiceURL($curlData, $paymentId, $orderId, $sessionId);

                    $MyFatoorahSession = [
                        'order_id' => $orderId,
                        'plan_id' => $plan->id,
                        'price' => $price,
                        'coupon' => !empty($request->coupon) ? $request->coupon : '',
                    ];
                    $request->session()->put('MyFatoorahSession', $MyFatoorahSession);

                    return redirect($payment['invoiceURL']);
                } catch (Exception $ex) {
                    $exMessage = __('myfatoorah.' . $ex->getMessage());
                    return response()->json(['IsSuccess' => 'false', 'Message' => $exMessage]);
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
        $MyFatoorahSession = $request->session()->get('MyFatoorahSession');
        $request->session()->forget('MyFatoorahSession');

        $payment_setting = getSuperAdminAllSetting();

        if(isset($MyFatoorahSession['plan_id']) && !empty($MyFatoorahSession['plan_id'])){
            $mfConfig = [
                'apiKey' => isset($payment_setting['myfatoorah_pay_api_key']) ? $payment_setting['myfatoorah_pay_api_key'] : '',
                'isTest' => isset($payment_setting['myfatoorah_mode']) && $payment_setting['myfatoorah_mode'] == 'sandbox' ? true : false,
                'countryCode' => isset($payment_setting['myfatoorah_pay_country_iso']) ? $payment_setting['myfatoorah_pay_country_iso'] : '',
            ];
        }elseif(isset($MyFatoorahSession['slug']) && !empty($MyFatoorahSession['slug'])){
            $theme_id=$MyFatoorahSession['theme_id'];
            $store_id=$MyFatoorahSession['store_id'];
            $mfConfig = [
                'apiKey' => \App\Models\Utility::GetValueByName('myfatoorah_pay_api_key',$theme_id, $store_id) ? \App\Models\Utility::GetValueByName('myfatoorah_pay_api_key',$theme_id, $store_id) : '',
                'isTest' => \App\Models\Utility::GetValueByName('myfatoorah_mode',$theme_id, $store_id) == 'sandbox'  ? true : false,
                'countryCode' => \App\Models\Utility::GetValueByName('myfatoorah_pay_country_iso',$theme_id, $store_id) ? \App\Models\Utility::GetValueByName('myfatoorah_pay_country_iso',$theme_id, $store_id) : '',
            ];
        }else{
            return redirect()->back()->with('error', 'Oops something went wrong.');
        }
        
        try {
            $paymentId = request('paymentId');

            $mfObj = new MyFatoorahPaymentStatus($mfConfig);
            $data = $mfObj->getPaymentStatus($paymentId, 'PaymentId');
            if ($data->InvoiceStatus == 'Paid' && empty($data->InvoiceError)) {

                if(isset($MyFatoorahSession['plan_id']) && !empty($MyFatoorahSession['plan_id'])){
                    $payment_setting = getSuperAdminAllSetting();
                    $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
                    $user = \Auth::user();
                    $plan = Plan::find($MyFatoorahSession['plan_id']);

                    $order = new PlanOrder();
                    $order->order_id = $MyFatoorahSession['order_id'];
                    $order->name = $user->name;
                    $order->card_number = isset($data->InvoiceTransactions['CardNumber']) ? $data->InvoiceTransactions['CardNumber'] : '';
                    $order->card_exp_month = '';
                    $order->card_exp_year = '';
                    $order->plan_name = $plan->name;
                    $order->plan_id = $plan->id;
                    $order->price = !empty($MyFatoorahSession['price']) ? $MyFatoorahSession['price'] : 0;
                    $order->price_currency = $currency;
                    $order->txn_id = time();
                    $order->payment_type = __('MyFatoorah');
                    $order->payment_status = 'Succeeded';
                    $order->receipt = '';
                    $order->user_id = $user->id;
                    $order->save();

                    if ($MyFatoorahSession['coupon']) {
                        $coupons = PlanCoupon::where('code', $MyFatoorahSession['coupon'])->where('is_active', '1')->first();
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
                }elseif(isset($MyFatoorahSession['slug']) && !empty($MyFatoorahSession['slug'])){
                    $slug=$MyFatoorahSession['slug'];
                    return redirect()->route('store.payment.myfatoorah',$slug);
                }else{
                    return redirect()->back()->with('error', 'Oops something went wrong.');
                }

            } else if ($data->InvoiceStatus == 'Failed') {
                return redirect()->back()->with('error', 'Transaction has been failed.');
            } else if ($data->InvoiceStatus== 'Expired') {
                return redirect()->back()->with('error', 'Transaction has been expired.');
            }else{
                return redirect()->back()->with('error', 'Oops something went wrong.');
            }
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Oops something went wrong.');
        }
    }
}
