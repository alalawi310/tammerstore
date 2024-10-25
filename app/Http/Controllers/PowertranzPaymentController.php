<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Exception;
use Http;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\PlanCoupon;
use App\Models\PlanOrder;
use Illuminate\Support\Facades\Auth;
use App\Models\PlanUserCoupon;

class PowertranzPaymentController extends Controller
{

    private $currencyArray = [
        "USD" => "840",
        "EUR" => "978",
        "GBP" => "826",
        "CAD" => "124",
        "AUD" => "036",
        "JPY" => "392",
        "CHF" => "756",
        "SEK" => "752",
        "NOK" => "578",
        "DKK" => "208",
        "NZD" => "554",
        "SGD" => "702",
        "HKD" => "344",
        "ZAR" => "710",
        "MXN" => "484",
        "BRL" => "986",
        "INR" => "356",
        "CNY" => "156",
        "RUB" => "643",
    ];

    public function create(Request $request)
    {
        $response = $request->all();
        if ($request->plan_id) {
            return view('plans.Powertranz-planpayment', compact('response'));
        } elseif ($request->slug) {

            $currentTheme = $request->currentTheme;
            $slug = $request->slug;
            $currantLang = $request->currantLang;

            return view('plans.Powertranz-payment', compact('response', 'currentTheme', 'slug', 'currantLang'));
        } else {
            return redirect()->back()->with('error', __('Oops something went wrong.'));
        }
    }
    public function planPayWithPowertranz(Request $request)
    {

        $request->validate([
            'PowerTranz_cardholder_name' => 'required|string',
            'PowerTranz_card_number' => 'required|string',
            'PowerTranz_expiration_date' => 'required|string',
            'PowerTranz_cvv_code' => 'required|string',
        ]);

        $responseData = json_decode($request->input('response_data'), true);
        $payment_setting = getSuperAdminAllSetting();

        $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($responseData['plan_id']);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $user = \Auth::user();
        if ($plan) {

            try {
                $price = $plan->price;
                if (!empty($responseData['coupon'])) {
                    $coupons = PlanCoupon::where('code', $responseData['coupon'])->where('is_active', '1')->first();
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
                        return redirect()->route('plan.index')->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }

                if (array_key_exists($currency, $this->currencyArray)) {
                    $currency = $this->currencyArray[$currency];
                } else {
                    return redirect()->route('plan.index')->with('error', __('Currency not supported'));
                }

                $PowerTranzmode = isset($payment_setting['powertranz_mode']) ? $payment_setting['powertranz_mode'] : '';
                $PowerTranzPasswordId = isset($payment_setting['powertranz_pay_merchant_id']) ? $payment_setting['powertranz_pay_merchant_id'] : '';
                $PowerTranzPassword = isset($payment_setting['powertranz_pay_processing_password']) ? $payment_setting['powertranz_pay_processing_password'] : '';
                $PowerTranzurl = isset($payment_setting['powertranz_pay_production_url']) ? $payment_setting['powertranz_pay_production_url'] : '';
                $plan_id = $plan->id;

                $PowerTranz_expiration_date = explode('/', $request->PowerTranz_expiration_date);
                $PowerTranz_expiration_date = $PowerTranz_expiration_date[1] . $PowerTranz_expiration_date[0];

                if($PowerTranzmode == 'sandbox'){
                    $url = 'https://staging.ptranz.com/api/spi/sale';
                }elseif($PowerTranzmode == 'production'){
                    $url = $PowerTranzurl.'/api/spi/sale';
                }

                $headers = [
                    "Accept: application/json",
                    "PowerTranz-PowerTranzId: $PowerTranzPasswordId",
                    "PowerTranz-PowerTranzPassword: $PowerTranzPassword",
                    "Content-Type: application/json; charset=utf-8",
                    "Host: staging.ptranz.com",
                    "Connection: Keep-Alive"
                ];

                $fields = [
                    "TotalAmount" => $price,
                    "CurrencyCode" => $currency,
                    "ThreeDSecure" => true,
                    "Source" => [
                        "CardPan" => $request->PowerTranz_card_number,
                        "CardCvv" => $request->PowerTranz_cvv_code,
                        "CardExpiration" => $PowerTranz_expiration_date,
                        "CardholderName" => $request->PowerTranz_cardholder_name,
                    ],
                    "OrderIdentifier" => "$orderID",
                    "BillingAddress" => [
                        "FirstName" => $user->name,
                        "EmailAddress" => $user->email,
                        "PhoneNumber" => $user->mobile,
                    ],
                    "AddressMatch" => false,
                    "ExtendedData" => [
                        "ThreeDSecure" => [
                            "ChallengeWindowSize" => 4,
                            "ChallengeIndicator" => "01"
                        ],
                        "MerchantResponseUrl" => route('plan.get.Powertranz.status', [
                            $plan_id,
                            'amount' => $price,
                            'duration' => $plan->duration,
                            'coupon_code' => $responseData['coupon'],
                        ])
                    ]
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $response = curl_exec($ch);
                curl_close($ch);
                $json_response = json_decode($response, true);

                if (isset($json_response['Approved']) && $json_response['Approved'] === false) {
                    if (isset($json_response['RedirectData'])) {
                        return response($json_response['RedirectData']);
                    } else {
                        return redirect()
                            ->route('plan.index', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))
                            ->with('error', $response['message'] ?? 'Something went wrong.');
                    }
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

    public function planGetPowertranzStatus(Request $request, $plan_id)
    {

        $user = Auth::user();
        $plan = Plan::find($plan_id);
        $payment_setting = getSuperAdminAllSetting();
        $PowerTranzmode = isset($payment_setting['powertranz_mode']) ? $payment_setting['powertranz_mode'] : '';
        $PowerTranzPasswordId = isset($payment_setting['powertranz_pay_merchant_id']) ? $payment_setting['powertranz_pay_merchant_id'] : '';
        $PowerTranzPassword = isset($payment_setting['powertranz_pay_processing_password']) ? $payment_setting['powertranz_pay_processing_password'] : '';
        $PowerTranzurl = isset($payment_setting['powertranz_pay_production_url']) ? $payment_setting['powertranz_pay_production_url'] : '';
        $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';

        if($PowerTranzmode == 'sandbox'){
            $url = 'https://staging.ptranz.com/api/spi/Payment';
        }elseif($PowerTranzmode == 'production'){
            $url = $PowerTranzurl.'/api/spi/Payment';
        }

        if ($plan) {
            try {
                $response = Http::withHeaders([
                    "Accept: text/plain",
                    "Content-Type: application/json-patch+json",
                    "Host: staging.ptranz.com",
                    "Connection: Keep-Alive"
                ])->post($url, $request->SpiToken);
                $response_data = $response->json();
                if (isset($response_data['Approved']) && $response_data['Approved'] == true && isset($response_data['IsoResponseCode']) && $response_data['IsoResponseCode'] == "00") {

                    $order = new PlanOrder();
                    $order->order_id = $response_data['OrderIdentifier'];
                    $order->name = $user->name;
                    $order->card_number = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year = '';
                    $order->plan_name = $plan->name;
                    $order->plan_id = $plan->id;
                    $order->price = !empty($request->amount) ? $request->amount : 0;
                    $order->price_currency = $currency;
                    $order->txn_id = time();
                    $order->payment_type = __('PowerTranz');
                    $order->payment_status = 'Succeeded';
                    $order->txn_id = '';
                    $order->receipt = '';
                    $order->user_id = $user->id;
                    $order->save();

                    if ($request->coupon_code) {
                        $coupons = PlanCoupon::where('code', $request->coupon_code)->where('is_active', '1')->first();
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

    public function storePayWithPowertranz(Request $request)
    {
        $request->validate([
            'PowerTranz_cardholder_name' => 'required|string',
            'PowerTranz_card_number' => 'required|string',
            'PowerTranz_expiration_date' => 'required|string',
            'PowerTranz_cvv_code' => 'required|string',
        ]);

        $responseData = json_decode($request->input('response_data'), true);
        $slug = $responseData['slug'];
        $amount = $responseData['amount'];
        $theme_id = $responseData['theme_id'];
        $store_id = $responseData['store_id'];
        $user = (object) $responseData['user'];
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        if ($slug) {

            try {
                $currency = \App\Models\Utility::GetValueByName('CURRENCY_NAME', $theme_id, $store_id) ? \App\Models\Utility::GetValueByName('CURRENCY_NAME', $theme_id, $store_id) : 'USD';
                $PowerTranzmode = \App\Models\Utility::GetValueByName('powertranz_mode', $theme_id, $store_id) ? \App\Models\Utility::GetValueByName('powertranz_mode', $theme_id, $store_id) : '';
                $PowerTranzPasswordId = \App\Models\Utility::GetValueByName('powertranz_pay_merchant_id', $theme_id, $store_id) ? \App\Models\Utility::GetValueByName('powertranz_pay_merchant_id', $theme_id, $store_id) : '';
                $PowerTranzPassword = \App\Models\Utility::GetValueByName('powertranz_pay_processing_password', $theme_id, $store_id) ? \App\Models\Utility::GetValueByName('powertranz_pay_processing_password', $theme_id, $store_id) : '';
                $PowerTranzurl = \App\Models\Utility::GetValueByName('powertranz_pay_production_url', $theme_id, $store_id) ? \App\Models\Utility::GetValueByName('powertranz_pay_production_url', $theme_id, $store_id) : '';

                $PowerTranz_expiration_date = explode('/', $request->PowerTranz_expiration_date);
                $PowerTranz_expiration_date = $PowerTranz_expiration_date[1] . $PowerTranz_expiration_date[0];

                if (array_key_exists($currency, $this->currencyArray)) {
                    $currency = $this->currencyArray[$currency];
                } else {
                    return redirect()->route('checkout', $slug)->with('error', __('Currency not supported'));
                }

                if($PowerTranzmode == 'sandbox'){
                    $url = 'https://staging.ptranz.com/api/spi/sale';
                }elseif($PowerTranzmode == 'production'){
                    $url = $PowerTranzurl.'/api/spi/sale';
                }

                $headers = [
                    "Accept: application/json",
                    "PowerTranz-PowerTranzId: $PowerTranzPasswordId",
                    "PowerTranz-PowerTranzPassword: $PowerTranzPassword",
                    "Content-Type: application/json; charset=utf-8",
                    "Host: staging.ptranz.com",
                    "Connection: Keep-Alive"
                ];

                $fields = [
                    "TotalAmount" => $amount,
                    "CurrencyCode" => $currency,
                    "ThreeDSecure" => true,
                    "Source" => [
                        "CardPan" => $request->PowerTranz_card_number,
                        "CardCvv" => $request->PowerTranz_cvv_code,
                        "CardExpiration" => $PowerTranz_expiration_date,
                        "CardholderName" => $request->PowerTranz_cardholder_name,
                    ],
                    "OrderIdentifier" => "$orderID",
                    "BillingAddress" => [
                        "FirstName" => $user->name,
                        "EmailAddress" => $user->email,
                        "PhoneNumber" => $user->contact_number,
                    ],
                    "AddressMatch" => false,
                    "ExtendedData" => [
                        "ThreeDSecure" => [
                            "ChallengeWindowSize" => 4,
                            "ChallengeIndicator" => "01"
                        ],
                        "MerchantResponseUrl" => route('store.get.Powertranz.status', [
                            $slug,
                            'amount' => $amount,
                            'theme_id' => $theme_id,
                            'store_id' => $store_id,
                        ])
                    ]
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $response = curl_exec($ch);
                curl_close($ch);
                $json_response = json_decode($response, true);
                if (isset($json_response['Approved']) && $json_response['Approved'] === false) {
                    if (isset($json_response['RedirectData'])) {
                        return response($json_response['RedirectData']);
                    } else {
                        return redirect()
                            ->route('checkout', $slug)
                            ->with('error', $response['message'] ?? 'Something went wrong.');
                    }
                } else {
                    return redirect()
                        ->routeroute('checkout', $slug)
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }

            } catch (\Exception $e) {
                return redirect()->route('checkout', $slug)->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }

    }

    public function storeGetPowertranzStatus(Request $request,$slug)
    {
        if ($slug) {
            $PowerTranzmode = \App\Models\Utility::GetValueByName('powertranz_mode', $request->theme_id, $request->store_id) ? \App\Models\Utility::GetValueByName('powertranz_mode', $request->theme_id, $request->store_id) : '';
            $PowerTranzPasswordId = \App\Models\Utility::GetValueByName('powertranz_pay_merchant_id', $request->theme_id, $request->store_id) ? \App\Models\Utility::GetValueByName('powertranz_pay_merchant_id', $request->theme_id, $request->store_id) : '';
            $PowerTranzPassword = \App\Models\Utility::GetValueByName('powertranz_pay_processing_password', $request->theme_id, $request->store_id) ? \App\Models\Utility::GetValueByName('powertranz_pay_processing_password', $request->theme_id, $request->store_id) : '';
            $PowerTranzurl = \App\Models\Utility::GetValueByName('powertranz_pay_production_url', $request->theme_id, $request->store_id) ? \App\Models\Utility::GetValueByName('powertranz_pay_production_url', $request->theme_id, $request->store_id) : '';

            if($PowerTranzmode == 'sandbox'){
                $url = 'https://staging.ptranz.com/api/spi/Payment';
            }elseif($PowerTranzmode == 'production'){
                $url = $PowerTranzurl.'/api/spi/Payment';
            }

            try {
                $response = Http::withHeaders([
                    "Accept: text/plain",
                    "Content-Type: application/json-patch+json",
                    "Host: staging.ptranz.com",
                    "Connection: Keep-Alive"
                ])->post($url, $request->SpiToken);
                $response_data = $response->json();
                if (isset($response_data['Approved']) && $response_data['Approved'] == true && isset($response_data['IsoResponseCode']) && $response_data['IsoResponseCode'] == "00") {
                    return redirect()->route('store.payment.Powertranz', $slug);
                } else {
                    return redirect()->route('checkout', $slug)->with('error', __('Transaction has been failed.'));
                }
            } catch (\Exception $e) {
                return redirect()->route('checkout', $slug)->with('error', __('Oops something went wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Oops something went wrong.'));
        }
    }
}
