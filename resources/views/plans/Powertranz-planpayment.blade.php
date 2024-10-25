<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<title>{{ __('PowerTranz Payment') }}</title>
<link rel="icon" href="{{ asset('assets/images/powertranz_payment_images/powertranz.png') }}">
@if (isset($setting['cust_darklayout']) && isset($setting['SITE_RTL']) && $setting['cust_darklayout'] == 'on' && $setting['SITE_RTL'] == 'on')
<link rel="stylesheet" href="{{ asset('public/assets/css/rtl-style-dark.css') }}" id="main-style-link">
<link rel="stylesheet" href="{{ asset('css/rtl-custom.css') }}{{ '?v=' . time() }}"  id="main-style-custom-link">
@elseif(isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
<link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}" id="main-style-link">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}{{ '?v=' . time() }}"  id="main-style-custom-link">
@elseif(isset($setting['SITE_RTL']) && $setting['SITE_RTL'] == 'on')
<link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
<link rel="stylesheet" href="{{ asset('css/rtl-custom.css') }}{{ '?v=' . time() }}"  id="main-style-custom-link">
@else
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}{{ '?v=' . time() }}"  id="main-style-custom-link">
@endif
<style>
    .payment-info-form{
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #ffffff;
        padding: 20px 0;
        color: #000000;
    }
    .payment-info-form .payment-inner{
        max-width: 800px;
        width: 100%;
        margin: auto;
        padding: 15px;
        box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    }
    .payment-info-form .section-title{
        text-align: center;
        margin-bottom: 26px;
    }
    .payment-info-form .form-group label{
        padding: 0;
    }
    .payment-info-form .all-submit-btn{
        justify-content: center;
        gap: 15px;
    }
    .payment-info-form .all-submit-btn .btn{
        width: auto;
    }
    .payment-info-form .payment-inner .form-control{
        background-color: #ffffff;
        color: #000000;
    }
</style>
<div class="container">
    <div class="payment-info-form">
        <div class="payment-inner">
            {{ Form::open(['route' => array('plan.pay.with.Powertranz'), 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
            <div class="section-title">
                <h2>{{__('please enter payment info')}}</h2>
            </div>
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="PowerTranz_cardholder_name"
                            class="col-form-label">{{ __('Cardholder Name') }}</label>
                        <input class="form-control"
                            placeholder="Enter Cardholder Name"
                            name="PowerTranz_cardholder_name" type="text"
                            value="" required>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="PowerTranz_card_number"
                            class="col-form-label">{{ __('Credit Card Number') }}</label>
                        <input class="form-control"
                            placeholder="Enter Credit Card Number"
                            name="PowerTranz_card_number" type="text"
                            value="" required maxlength="16">
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="PowerTranz_expiration_date"
                            class="col-form-label">{{ __('Expiration Date') }}</label>
                        <input class="form-control"
                            placeholder="MM/YY"
                            name="PowerTranz_expiration_date" type="text"
                            pattern="\d{2}/\d{2}" maxlength="5" value="" required>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="PowerTranz_cvv_code" class="col-form-label">{{ __('CVV') }}</label>
                        <input class="form-control cvv_code" placeholder="Enter CVV" name="PowerTranz_cvv_code" type="number" required>
                    </div>
                </div>
                <input type="hidden" class="hiden" name="response_data" value= "{{ json_encode($response) }}">
                <div class="col-12 d-flex align-items-center all-submit-btn">
                    <input type="button" value="Cancel" class="btn" onclick="window.location.href = '{{ route('plan.index') }}';">
                    <input type="submit" value="Submit" class="btn">
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/plugins/imask.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var cvvElement = document.querySelector('.cvv_code');
        var cvvMask = IMask(cvvElement, {
            mask: '0000'
        });
    });
</script>
