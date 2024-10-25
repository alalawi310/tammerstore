<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanCoupon;
use App\Models\PlanOrder;
use App\Models\PlanUserCoupon;
use Illuminate\Http\Request;

class DPOPayController extends Controller
{
    public function create(Request $request)
    {
        $action = $request->action;
        $data = $request->all();

        $admin_payment_setting = getAdminAllSetting();

        return view('DPO.pay', compact('data', 'admin_payment_setting', 'action'));
    }

    public function planPayWithDPOPay(Request $request)
    {
        $data = json_decode($request->input('response_data'), true);

        $carddata = $request->all();
        $payment_setting = getSuperAdminAllSetting();

        $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($data['plan_id']);

        // Find the plan using the decrypted plan ID
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $user = \Auth::user();

        if ($plan) {
            try {

                $price = $plan->price;
                $coupon = $data['coupon'];
                if (! empty($coupon)) {
                    $coupons = PlanCoupon::where('code', $coupon)->where('is_active', '1')->first();
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
                $company_token = ! empty($payment_setting['dpo_pay_Company_Token']) ? $payment_setting['dpo_pay_Company_Token'] : '';
                $service_type = ! empty($payment_setting['dpo_pay_Service_Type']) ? $payment_setting['dpo_pay_Service_Type'] : '';
                $date = new \DateTime();
                $formattedDate = $date->format('Y/m/d H:i');

                $postXml = '<?xml version="1.0" encoding="utf-8"?>
                    <API3G>
                        <CompanyToken>'.$company_token.'</CompanyToken>
                        <Request>createToken</Request>
                        <Transaction>
                            <PaymentAmount>'.$price.'</PaymentAmount>
                            <PaymentCurrency>'.$currency.'</PaymentCurrency>
                            <RedirectURL>http://www.domain.com/payurl.php</RedirectURL>
                            <BackURL>http://www.domain.com/payurl.php</BackURL>
                            <CompanyRefUnique>0</CompanyRefUnique>
                            <PTL>5</PTL>

                        </Transaction>
                        <Services>
                            <Service>

                                <ServiceType>'.$service_type.'</ServiceType>
                                <ServiceDescription>Purchase Plan</ServiceDescription>
                                <ServiceDate>'.$formattedDate.'</ServiceDate>
                            </Service>
                        </Services>
                    </API3G>';

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://secure.3gdirectpay.com/API/v6/',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $postXml,
                    CURLOPT_HTTPHEADER => [
                        'cache-control: no-cache',
                    ],
                ]);

                $response = curl_exec($curl);
                $error = curl_error($curl);

                curl_close($curl);

                if ($response != '') {
                    $xml = new \SimpleXMLElement($response);

                    $result = $xml->xpath('Result')[0]->__toString();
                    $resultExplanation = $xml->xpath('ResultExplanation')[0]->__toString();
                    $returnResult = [
                        'result' => $result,
                        'resultExplanation' => $resultExplanation,
                    ];

                    // Check if token was created successfully
                    if ($xml->xpath('Result')[0] != '000') {
                        $returnResult['success'] = 'false';
                    } else {

                        $transToken = $xml->xpath('TransToken')[0]->__toString();
                        $transRef = $xml->xpath('TransRef')[0]->__toString();
                        $returnResult['success'] = 'true';
                        $returnResult['transToken'] = $transToken;
                        $returnResult['transRef'] = $transRef;

                    }
                    $postXml = trim($postXml);

                    // Load the XML string into a SimpleXMLElement object
                    $xml = simplexml_load_string($postXml);

                    // Convert the SimpleXMLElement object to JSON
                    $json = json_encode($xml);

                    // Decode the JSON into an associative array
                    $array = json_decode($json, true);
                    $data = [
                        'carddata' => $carddata,
                        'array' => $array,
                        'returnResult' => $returnResult,
                    ];
                    $payment = $this->payment($data);
                    $result = $this->checkToken($data);
                    $response1 = $this->checkToken($data); // Assuming this function returns the response data
                    $response = simplexml_load_string($response1);
                    $plan_id = [
                        'order_id' => $orderID,
                        'amount' => $price,
                        'planID' => $planID,
                        'plan_id' => $planID,
                        'coupon_code' => $coupon,
                        'response' => $response,
                    ];

                    return redirect()->route('plan.get.dpo.status', $plan_id);

                }

            } catch (\Exception $e) {
                return redirect()->route('plan.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function planGetDPOPayStatus(Request $request)
    {

        $payment_setting = getSuperAdminAllSetting();
        $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
        $orderID = $request->input('order_id');
        $getAmount = $request->input('amount');
        $coupanCode = $request->input('coupon_code');
        $user = \Auth::user();
        try {

            $plan = Plan::find($request->input('planID'));
            $result = $request['response']['Result'];
            if ($result == 000) {
                $order = new PlanOrder();
                $order->order_id = $orderID;
                $order->name = $user->name;
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->plan_name = $plan->name;
                $order->plan_id = $plan->id;
                $order->price = $getAmount;
                $order->price_currency = $currency;
                $order->txn_id = time();
                $order->payment_type = __('DPO Pay');
                $order->payment_status = 'success';
                $order->txn_id = '';
                $order->receipt = '';
                $order->user_id = $user->id;
                $order->save();

                $coupons = PlanCoupon::where('code', $coupanCode)->where('is_active', '1')->first();
                if (! empty($coupons)) {
                    $userCoupon = new PlanUserCoupon();
                    $userCoupon->user_id = $user->id;
                    $userCoupon->coupon_id = $coupons->id;
                    $userCoupon->order = $orderID;
                    $userCoupon->save();
                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit <= $usedCoupun) {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                }

                $assignPlan = $user->assignPlan($plan->id);
                if ($assignPlan['is_success']) {
                    return redirect()->route('plan.index')->with('success', __('Plan activated Successfully.'));
                } else {
                    return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
                }
            } else {
                return redirect()->route('plan.index')->with('error', 'Payment failed.');
            }

        } catch (Exception $e) {
            return redirect()->route('plan.index')->with('error', __($e->getMessage()));

        }
    }

    public function checkToken(array $data)
    {
        $companyToken = $data['array']['CompanyToken'];
        $transToken = $data['returnResult']['transToken'];

        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://secure.3gdirectpay.com/API/v6/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => "
                <?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n<API3G>\r\n <CompanyToken>".$companyToken."</CompanyToken>\r\n
                    <Request>verifyToken</Request>\r\n <TransactionToken>".$transToken."</TransactionToken>\r\n
                </API3G>",

                CURLOPT_HTTPHEADER => [
                    'cache-control: no-cache',
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if (strlen($err) > 0) {
                echo 'cURL Error #:'.$err;
            } else {

                return $response;
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));

        }
    }

    public function payment(array $data)
    {
        $companyToken = $data['array']['CompanyToken'];
        $transToken = $data['returnResult']['transToken'];
        $CreditCardNumber = $data['carddata']['card_number'];
        $Expiration = $data['carddata']['expiry'];
        $cvv = $data['carddata']['cvv'];
        $name = $data['carddata']['name'];

        try {
            $curl = curl_init();
            curl_setopt_array(
                $curl,
                [
                    CURLOPT_URL => 'https://secure.3gdirectpay.com/API/v6/',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => "
                        <?xml version='1.0' encoding='utf-8'?>
                        <API3G>
                            <CompanyToken>".$companyToken.'</CompanyToken>
                            <Request>chargeTokenCreditCard</Request>
                            <TransactionToken>'.$transToken.'</TransactionToken>
                            <CreditCardNumber>'.$CreditCardNumber.'</CreditCardNumber>
                            <CreditCardExpiry>'.$Expiration.'</CreditCardExpiry>
                            <CreditCardCVV>'.$cvv.'</CreditCardCVV>
                            <CardHolderName>'.$name.'</CardHolderName>
                            <ChargeType></ChargeType>
                            <ThreeD>
                                <Enrolled>Y</Enrolled>
                                <Paresstatus>Y</Paresstatus>
                                <Eci>05</Eci>
                                <Xid>DYYVcrwnujRMnHDy1wlP1Ggz8w0=</Xid>
                                <Cavv>mHyn+7YFi1EUAREAAAAvNUe6Hv8=</Cavv>
                                <Signature>_</Signature>
                                <Veres>AUTHENTICATION_SUCCESSFUL</Veres>
                                <Pares>eAHNV1mzokgW/isVPY9GFSCL0EEZkeyg7</Pares>
                            </ThreeD>
                        </API3G>',
                    ],
                );

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if (strlen($err) > 0) {
                echo 'cURL Error #:'.$err;
            } else {

                return $response;
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));

        }
    }

    public function storePayWithDPO(Request $request)
    {
        $responseData = json_decode($request->input('response_data'), true);
        $slug = $responseData['slug'];
        $price = $responseData['total_price'];
        $theme_id = $responseData['theme_id'];
        $store_id = $responseData['store_id'];
        $user = (object) $responseData['user'];
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $date = new \DateTime();
        $formattedDate = $date->format('Y/m/d H:i');
        $carddata = $request->all();
        if ($slug) {

            try {
                $currency = \App\Models\Utility::GetValueByName('CURRENCY_NAME', $theme_id, $store_id) ?
                \App\Models\Utility::GetValueByName('CURRENCY_NAME', $theme_id, $store_id) : 'USD';

                $company_token = \App\Models\Utility::GetValueByName('dpo_pay_Company_Token', $theme_id, $store_id) ?
                \App\Models\Utility::GetValueByName('dpo_pay_Company_Token', $theme_id, $store_id) : '';
                $service_type = \App\Models\Utility::GetValueByName('dpo_pay_Service_Type', $theme_id,
                    $store_id) ? \App\Models\Utility::GetValueByName('dpo_pay_Service_Type', $theme_id, $store_id) : '';
               
                if ($currency != 'USD') {
                    return redirect()->route('checkout', $slug)->with('error', __('Currency not supported'));
                }
                $postXml = '<?xml version="1.0" encoding="utf-8"?>
                        <API3G>
                            <CompanyToken>'.$company_token.'</CompanyToken>
                            <Request>createToken</Request>
                            <Transaction>
                                <PaymentAmount>'.$price.'</PaymentAmount>
                                <PaymentCurrency>'.$currency.'</PaymentCurrency>
                                <RedirectURL>http://www.domain.com/payurl.php</RedirectURL>
                                <BackURL>http://www.domain.com/payurl.php</BackURL>
                                <CompanyRefUnique>0</CompanyRefUnique>
                                <PTL>5</PTL>

                            </Transaction>
                            <Services>
                                <Service>

                                    <ServiceType>'.$service_type.'</ServiceType>
                                    <ServiceDescription>Purchase Iteams</ServiceDescription>
                                    <ServiceDate>'.$formattedDate.'</ServiceDate>
                                </Service>
                            </Services>
                        </API3G>';

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://secure.3gdirectpay.com/API/v6/',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $postXml,
                    CURLOPT_HTTPHEADER => [
                        'cache-control: no-cache',
                    ],
                ]);

                $response = curl_exec($curl);
                $error = curl_error($curl);

                curl_close($curl);

                if ($response != '') {
                    $xml = new \SimpleXMLElement($response);

                    $result = $xml->xpath('Result')[0]->__toString();
                    $resultExplanation = $xml->xpath('ResultExplanation')[0]->__toString();
                    $returnResult = [
                        'result' => $result,
                        'resultExplanation' => $resultExplanation,
                    ];

                    // Check if token was created successfully
                    if ($xml->xpath('Result')[0] != '000') {
                        $returnResult['success'] = 'false';
                    } else {

                        $transToken = $xml->xpath('TransToken')[0]->__toString();
                        $transRef = $xml->xpath('TransRef')[0]->__toString();
                        $returnResult['success'] = 'true';
                        $returnResult['transToken'] = $transToken;
                        $returnResult['transRef'] = $transRef;

                    }
                    $postXml = trim($postXml);

                    // Load the XML string into a SimpleXMLElement object
                    $xml = simplexml_load_string($postXml);

                    // Convert the SimpleXMLElement object to JSON
                    $json = json_encode($xml);

                    // Decode the JSON into an associative array
                    $array = json_decode($json, true);
                    $data = [
                        'carddata' => $carddata,
                        'array' => $array,
                        'returnResult' => $returnResult,
                    ];
                    $payment = $this->payment($data);
                    $result = $this->checkToken($data);
                    $response1 = $this->checkToken($data); // Assuming this function returns the response data
                    $response = simplexml_load_string($response1);

                    $Mainslug = [
                        'slug' => $slug,
                        'carddata' => $carddata,
                        'order_id' => $orderID,
                        'amount' => $price,

                        'response' => $response,
                    ];

                    $serializedData = json_encode($Mainslug);

                    return redirect()->route('store.get.dpo.status', ['data' => $serializedData]);
                }

            } catch (\Exception $e) {
                return redirect()->route('checkout', ['storeSlug' => $slug])->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function storeGetDPOStatus(Request $request)
    {
        $data = json_decode($request->all()['data'], true);
        $result = $data['response']['Result'];
        $slug = $data['slug'];
        if ($slug) {
            try {
                if ($result == 000) {
                    return redirect()->route('store.payment.dpo', $slug);
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
