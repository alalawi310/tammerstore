<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Senangpay extends Model
{
    use HasFactory;

    public $detail,$amount ,$orderId ,$name ,$email ,$phone ,$currancy, $secretKey, $merchantId, $payment_mode;


    protected static function newFactory()
    {
        return \Modules\SenangPay\Database\factories\SenangpayFactory::new();
    }

    public function payment_setting($id=null)
    {
        if (\Auth::check()) {

            $admin_settings = getSuperAdminAllSetting();
            $this->currancy  = !empty($admin_settings['CURRENCY_NAME']) ? $admin_settings['CURRENCY_NAME'] : '$';
            $this->merchantId = ($admin_settings['senang_pay_merchant_id']) ? $admin_settings['senang_pay_merchant_id'] : 'off';
            $this->secretKey        = ($admin_settings['senang_pay_secret_key']) ? $admin_settings['senang_pay_secret_key'] : '';
            $this->payment_mode  =  ($admin_settings['Senangpay_mode']) ? $admin_settings['Senangpay_mode'] : 'sandbox';

        } else {

            if (!empty($id) ) {
                $this->currancy  = !empty(\App\Models\Utility::GetValueByName('defult_currancy',$id)) ?  \App\Models\Utility::GetValueByName('defult_currancy',$id) : '$';
                $this->merchantId        = ( \App\Models\Utility::GetValueByName('senang_pay_merchant_id',$id)) ?  \App\Models\Utility::GetValueByName('senang_pay_merchant_id',$id) : '';
                $this->secretKey     = ( \App\Models\Utility::GetValueByName('senang_pay_secret_key',$id)) ?  \App\Models\Utility::GetValueByName('senang_pay_secret_key',$id) : '';
                $this->payment_mode  =  ( \App\Models\Utility::GetValueByName('Senangpay_mode',$id)) ?  \App\Models\Utility::GetValueByName('Senangpay_mode',$id) : 'sandbox';

            }  else {
                $company_settings = getSuperAdminAllSetting();
                $this->currancy  = !empty($company_settings['defult_currancy']) ? $company_settings['defult_currancy'] : '$';
                $this->merchantId        = ($company_settings['senang_pay_merchant_id']) ? $company_settings['senang_pay_merchant_id'] : '';
                $this->secretKey     = ($company_settings['senang_pay_secret_key']) ? $company_settings['senang_pay_secret_key'] : '';
                $this->payment_mode  =  ($company_settings['Senangpay_mode']) ? $company_settings['Senangpay_mode'] : 'sandbox';
            }

        }


    }


    public function setSendPaymentDetails( $request, $detail, $orderId, $amount ,$user_id)
    {
        self::payment_setting($user_id);


        $this->detail = $detail;
        $this->amount = $amount;
        $this->orderId = $orderId;

        if($request)
        {

            $this->name = $request['full_name'];
            $this->email = $request['email'];
            $this->phone = $request['contact_number'];
        }

        return $this;
    }

    public function generateHash()
    {

          return md5($this->secretKey.$this->detail.$this->amount.$this->orderId );
    }



    public function generateHttpQuery()
    {
        $httpQuery = http_build_query([
            'detail' => $this->detail,
            'amount' => $this->amount,
            'hash' => $this->generateHash(),
            'order_id' => $this->orderId,
            'phone'=> $this->phone,
            'email' => $this->email,
            'name' => $this->name,
        ]);


        return $httpQuery;
    }

    public function processPayment()
    {
        $payment_mode = $this->payment_mode;

        if ($payment_mode == 'live') {
            return 'https://app.senangpay.my/'.$this->merchantId.'?'.$this->generateHttpQuery();

        }else{
            return 'https://sandbox.senangpay.my/payment/'.$this->merchantId.'?'.$this->generateHttpQuery();
        }



    }

    protected function generateReturnHash($request)
    {

        $returnHash = md5($this->secretKey.'?status_id='.$request->status_id.'&order_id='.$request->order_id.'&transaction_id='.$request->transaction_id.'&message='.$request->message.'&hash=[HASH]');

      return $returnHash;
    }

    public function checkIfReturnHashCorrect( $request )
    {
        $parameterHash = $request->hash;
        if($this->generateReturnHash( $request) == $parameterHash )
        {
          return true;
        } else {
          return false;
        }
    }
}
