<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="healthcare">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

    <meta name="description" content="healthcare">
    <meta name="keywords" content="healthcare">
    <title>{{__('DPO Pay')}}</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Readex+Pro:wght@160..700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/DPO_payment_images/DPOfavicon.png') }}" type="image/x-icon">
    <style>
        .h5 {
            font: normal 500 26px/1.2 "Noto Sans", sans-serif;
        }
        .payment{
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .payment-form {
            max-width: 600px;
            width: 100%;
            margin: 0 auto ;
            background-color: #ffffff;
            color: #222222;
            box-shadow: rgba(100, 100, 111, 0.4) 0px 7px 29px 0px;
            border-radius: 15px;
            overflow: hidden;
            -webkit-border-radius: 15px;
            -moz-border-radius: 15px;
            -ms-border-radius: 15px;
            -o-border-radius: 15px;
        }
        .payment-form .payment-logo{
            background-color: #EEF2F4;
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
            padding: 10px;
            gap: 10px;
        }
        .payment-form .payment-logo h1{
            font: normal 500 26px/1.2 "Noto Sans", sans-serif;
            color: #222222;
        }
        .payment-form .payment-logo img,
        .payment-form .payment-logo svg{
            width: 50px;
            height: 50px;
            object-fit: scale-down;
        }
        .payment-form .payment-logo svg path{
        fill: #54C7C3
        }
        .payment-form .user{
            gap: 10px;
        }
        .payment-form .form-group {
            margin-bottom: 20px;
        }
        .payment-form .form-group label {
            display: inline-block;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 500;
            color: #46545C;
            text-transform: capitalize;
        }
        .form-control, input:not([type="submit"]),
        input:not([type="checkbox"]), input:not([type="radio"]), input:not([type="time"]), input:not([type="date"]), select, textarea {
            position: relative;
            display: block;
            width: 100%;
            padding: 12px;
            background: #ffffff;
            font-size: 16px;
            border: 1px solid transparent;
            box-shadow: 0px 5px 20px rgb(0 0 0 / 13%);
            font-weight: 400;
            border: 1px solid  #e7e7e7;
            line-height: 1;
            color: #46545C;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
            outline: none;
        }
        .payment-form form{
            padding: 20px;
        }
        .payment-form .btn{
            max-width: 150px;
            width: 100%;
            font-size: 16px;
            font-weight: 500;
            text-transform: capitalize;
            padding: 10px 15px;
            background-color: #54C7C3;
            color: #ffffff;
            border: 1px solid #54C7C3;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
            transition: all 500ms ease-in-out 0s;
            -webkit-transition: all 500ms ease-in-out 0s;
            -moz-transition: all 500ms ease-in-out 0s;
            -ms-transition: all 500ms ease-in-out 0s;
            -o-transition: all 500ms ease-in-out 0s;
        }
        .payment-form .btn:hover{
            border-color: #54C7C3;
            color: #54C7C3;
            background-color: #ffffff;
        }
        .Payment-amount span{
            /* display: block; */
            font-weight: 600;
            font-size: large;
        }

        .payment-form .form-control:focus {
            border-color: #54C7C3;
        }
        .user-name h3{
            text-transform: capitalize;
        }
        .payment-image-wrapper{
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px 0 0;
            gap: 8px;
        }
        .payment-image-wrapper .payment-image{
            width: 50px;
            height: 30px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
            cursor: pointer;
        }
        .payment-image-wrapper .payment-image img{
            width: 100%;
            height: 100%;
            object-fit: scale-down;
        }

        @media screen and (max-width:1199px) {
            :root {
                --h1: normal 500 40px/1.2 "Noto Sans", sans-serif;
                --h2: normal 500 36px/1.2 "Noto Sans", sans-serif;
                --h3: normal 500 28px/1.2 "Noto Sans", sans-serif;
                --h4: normal 500 24px/1.2 "Noto Sans", sans-serif;
                --h5: normal 500 22px/1.2 "Noto Sans", sans-serif;
                --h6: normal 500 20px/1.2 "Noto Sans", sans-serif;
            }
        }

        @media screen and (max-width:991px) {
            :root {
                --h1: normal 500 34px/1.2 "Noto Sans", sans-serif;
                --h2: normal 500 30px/1.2 "Noto Sans", sans-serif;
                --h3: normal 500 24px/1.2 "Noto Sans", sans-serif;
                --h4: normal 500 22px/1.2 "Noto Sans", sans-serif;
                --h5: normal 500 20px/1.2 "Noto Sans", sans-serif;

            }
        }

        @media screen and (max-width:767px){
            :root {
                --h1: normal 500 30px/1.2 "Noto Sans", sans-serif;
                --h2: normal 500 28px/1.2 "Noto Sans", sans-serif;
                --h3: normal 500 26px/1.2 "Noto Sans", sans-serif;
                --common-text: normal 500 14px/1.4 "Readex Pro", sans-serif;
            }
            .payment-form {
                max-width: calc(100% - 30px);
            }

            .payment-form .form-group  input,
            .payment-form .form-group label{
                font-size: 14px;
            }
        }
        @media screen and (max-width:575px) {
            :root {
                --h1: normal 500 30px/1.2 "Noto Sans", sans-serif;
                --h2: normal 500 24px/1.2 "Noto Sans", sans-serif;
                --h3: normal 500 22px/1.2 "Noto Sans", sans-serif;
            }
            .payment-form form{
                padding:20px 15px;
            }
            .payment-form .payment-logo img{
                width: 40px;
                height: 40px;
            }
        }
    </style>

<script src="{{ asset('public/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('public/js/jquery.form.js') }}"></script>
<script src="{{ asset('js/custom.js') }}" defer="defer"></script>
    <script>
        document.getElementById('expiryDate').addEventListener('input', function (e) {
            var input = e.target.value.replace(/\D/g, '').substring(0, 4);
            var month = input.substring(0, 2);
            var year = input.substring(2, 4);

            if (input.length > 2) {
                e.target.value = month + '/' + year;
            } else {
                e.target.value = month;
            }
        });

        function formatExpiryDate(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 4) {
                value = value.substring(0, 4);
            }
            const formattedValue = value.substring(0, 2) + (value.length >= 2 ? '/' : '') + value.substring(2);
            input.value = formattedValue;
        }

        function stripSlashBeforeSubmit() {
            const expiryDateInput = document.getElementById('expiryDate');
            expiryDateInput.value = expiryDateInput.value.replace('/', '');
        }
    </script>
</head>

<body>

    <div class="payment">
        <div class="payment-form">
            <div class="payment-logo">
                <img src="{{ asset('assets/images/DPO_payment_images/payment-logo.png') }}" alt="logo" loading="lazy">
                <h1>Plan Pay</h1>
            </div>
            <div class="payment-image-wrapper">
                <div class="payment-image">
                    <img src="{{ asset('assets/images/DPO_payment_images/payment1.png') }}" alt="payment-image" loading="lazy">
                </div>
                <div class="payment-image">
                    <img src="{{ asset('assets/images/DPO_payment_images/payment2.png') }}" alt="payment-image" loading="lazy">
                </div>
                <div class="payment-image">
                    <img src="{{ asset('assets/images/DPO_payment_images/payment3.png') }}" alt="payment-image" loading="lazy">
                </div>
                <div class="payment-image">
                    <img src="{{ asset('assets/images/DPO_payment_images/payment4.png') }}" alt="payment-image" loading="lazy">
                </div>
            </div>
            <form method="POST" id="payment-form" action="{{ $action }}" onsubmit="stripSlashBeforeSubmit()">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group user d-flex align-items-center justify-content-between">
                            <div class="user-name">
                                <h3 class="h5">{{__('DPO Pay')}}</h3>
                            </div>
                            <div class="amount">
                                <label>{{__('Total Amount')}}</label>
                                <div class="Payment-amount">
                                    <span>{{ $admin_payment_setting['CURRENCY_NAME'] ? $admin_payment_setting['CURRENCY_NAME'].' ' : 'USD'.' ' }}</span><span>{{ $data['total_price'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="hiden" name="response_data" value= "{{ json_encode($data) }}">
                    {{-- <input type="hidden" name="plan_id" value="{{ $data['plan_id'] }}">
                    <input type="hidden" name="total_price" value="{{ $data['total_price'] }}"> --}}
                    <div class="col-12">
                        <div class="form-group">
                            <label>{{__('Name')}}</label>
                            <input type="text" class="form-control" placeholder="Name" required="" maxlength="10"
                                name="name">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>{{__('Card number')}}</label>
                            <input type="text" class="form-control" placeholder="Card Number" required=""
                                name="card_number">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>{{__('Expiry date')}}</label>
                            <input type="text" id="expiryDate" class="form-control" placeholder="mm/yy"
                                maxlength="5" name="expiry" oninput="formatExpiryDate(this)">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>{{__('cvv')}}</label>
                            <input type="number" class="form-control" placeholder="Cvv" pattern="\d{3,4}"
                                maxlength="3" name="cvv">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>{{__('pin code')}}</label>
                            <input type="number" class="form-control" placeholder="Pin Code" required=""
                                name="pin_code">
                        </div>
                    </div>
                    <div class="text-center col-12">
                        <button class="btn" type="submit">
                            {{__('Pay')}}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>



</body>

</html>
