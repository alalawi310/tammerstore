<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

@if($currantLang == 'ar' || $currantLang == 'he')
    <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme .  '/assets/css/rtl-main-style.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme .  '/assets/css/rtl-responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rtl-custom.css') }}">
@else
    <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme .  '/assets/css/main-style.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/' . $currentTheme .  '/assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
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
    border-radius: 0;
    padding: 10px 12px;
    }
    .payment-info-form .payment-inner .form-control{
        background-color: #ffffff;
        color: #000000;
        border-radius: 0;
        padding: 15px 18px;
    }
</style>
<div class="container">
    <div class="payment-info-form">
        <div class="payment-inner">
            {{ Form::open(['route' => 'plan.pay.with.nmi', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
            <div class="section-title">
                <h2>{{__('please enter payment info')}}</h2>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nmi_cardholder_name"
                            class="col-form-label">{{ __('Cardholder Name') }}</label>
                        <input class="form-control"
                            placeholder="Enter Cardholder Name"
                            name="nmi_cardholder_name" type="text"
                            value="" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nmi_card_number"
                            class="col-form-label">{{ __('Credit Card Number') }}</label>
                        <input class="form-control"
                            placeholder="Enter Credit Card Number"
                            name="nmi_card_number" type="text"
                            value="" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nmi_expiration_date"
                            class="col-form-label">{{ __('Expiration Date') }}</label>
                        <input class="form-control"
                            placeholder="Enter Expiration Date"
                            name="nmi_expiration_date" type="month"
                            value="" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nmi_cvv_code"
                            class="col-form-label">{{ __('CVV') }}</label>
                        <input class="form-control cvv_code"
                            placeholder="Enter CVV"
                            name="nmi_cvv_code" type="number" data-mask="1234"
                            value="" required>
                    </div>
                </div>
                <input type="hidden" name="plan_id"
                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                <input type="hidden" name="total_price" value="{{ $plan->price }}"
                class="form-control final-price">
                <input type="hidden" name="coupon" value="{{ $couponCode }}"
                class="form-control">
                <div class="col-12 d-flex align-items-center all-submit-btn">
                    <input type="submit" value="Submit" class="btn">
                    <input type="button" value="Cancel" class="btn" onclick="window.location.href = '{{ route('plan.index') }}';">
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script src="{{asset('assets/js/plugins/imask.min.js')}}"></script>
<script>
    var regExpMask = IMask(document.querySelector('.cvv_code'),{ mask: '1234'});
</script>
