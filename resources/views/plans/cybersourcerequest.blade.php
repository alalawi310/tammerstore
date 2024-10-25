<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="cybersource">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>{{ __('Cybersource Plan Payment') }}</title>
    <meta name="description" content="cybersource">
    <meta name="keywords" content="cybersource">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/images/cybersource_payment_images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
    <style>
        .expiration input {
            border: 0;
        }
    </style>
</head>

<body class="theme-1">

    <section class="payment-sec">
        <div class="container">
            <div class="card">
                <div class="card-body w-100">
                    <div class="payment-logo text-center pb-3">
                        <img src="{{ asset('assets/images/cybersource_payment_images/payment-logo.png')}}" alt="payment-logo"
                            loading="lazy" style="width: 80px;">
                    </div>
                    <form class="payment-form" method="post" action="{{ route($callback_url) }}">
                        @csrf
                        <input type="hidden" name="data" value="{{ $data }}">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="d-block mb-1">{{ __('Owner Name') }} <sup aria-hidden="true"
                                            class="text-danger">*</sup></label>
                                    <input class="form-control" type="text" placeholder="Name"
                                        value="{{ $name }}" disabled>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="d-block mb-1">{{ __('Amount') }}<sup aria-hidden="true"
                                            class="text-danger">*</sup></label>
                                    <div class="input-group">
                                        <span class="input-group-prepend"><span
                                                class="input-group-text">{{ admin_setting('defult_currancy') ? admin_setting('defult_currancy') : 'USD' }}</span></span>
                                        <input class="form-control" type="number" placeholder="Amount"
                                            value="{{ $price }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="d-block mb-1">{{ __('Card Number') }}<sup aria-hidden="true"
                                            class="text-danger">*</sup></label>
                                    <input class="form-control" type="text" placeholder="Enter Card Number"
                                        name="cardNumber" required>
                                    <small
                                        class="form-text text-muted">{{ __('Please enter a 16-digit card number.') }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group ">
                                    <label class="d-block mb-1">{{ __('CVV') }}<sup aria-hidden="true"
                                            class="text-danger" class="text-danger">*</sup></label>
                                    <input class="form-control" type="text" placeholder="Enter CVV" name="cvv"
                                        maxlength="3" size="3" required>
                                </div>
                            </div>
                            <div class="col-6 form-group">
                                <label class="d-block mb-1">{{ __('Expiration') }}<sup aria-hidden="true"
                                        class="text-danger">*</sup></label>
                                <div class=" expiration form-control">
                                    <input type="text" class="" name="month" placeholder="MM" maxlength="2"
                                        size="2" />
                                    <span>/</span>
                                    <input type="text" class="" name="year" placeholder="YYYY"
                                        maxlength="4" size="4" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label class="d-block mb-1">{{ __('First Name') }}<sup aria-hidden="true"
                                            class="text-danger">*</sup></label>
                                    <input class="form-control" type="text" placeholder="Enter First Name"
                                        name="first_name" required>

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label class="d-block mb-1">{{ __('Last Name') }}<sup aria-hidden="true"
                                            class="text-danger">*</sup></label>
                                    <input class="form-control" type="text" placeholder="Enter Last Name"
                                        name="last_name" required>

                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="d-block mb-1">{{ __('Address') }}<sup aria-hidden="true"
                                            class="text-danger">*</sup></label>
                                    <input class="form-control" type="text" placeholder="Enter Address"
                                        name="address" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label class="d-block mb-1">{{ __('Locality') }}<sup aria-hidden="true"
                                            class="text-danger">*</sup></label>
                                    <input class="form-control" type="text" placeholder="Enter locality"
                                        name="locality" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label class="d-block mb-1">{{ __('AdministrativeArea') }}<sup aria-hidden="true"
                                            class="text-danger">*</sup></label>
                                    <input class="form-control" type="text" placeholder="Enter AdministrativeArea"
                                        name="administrativearea" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label class="d-block mb-1">{{ __('PostalCode') }}<sup aria-hidden="true"
                                            class="text-danger">*</sup></label>
                                    <input class="form-control" type="text" placeholder=" Enter Postalcode"
                                        name="postalcode" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label class="d-block mb-1">{{ __('Country') }}<sup aria-hidden="true"
                                            class="text-danger">*</sup></label>
                                    <input class="form-control" type="text" placeholder="Enter Country"
                                        name="country" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="d-block mb-1">{{ __('Phone No') }}<sup aria-hidden="true"
                                            class="text-danger">*</sup></label>
                                    <input class="form-control" type="number" placeholder="Enter Phone No"
                                        name="phone_no" required>
                                </div>
                            </div>
                            <div class="col-12 w-50 m-auto">
                                <button class="btn btn-primary text-uppercase w-100">{{ __('pay') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="{{ asset('js/jquery-3.6.0.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            /*********  Multi-level accordion nav  ********/
            $('.acnav-label').click(function() {
                var label = $(this);
                var parent = label.parent('.has-children');
                var list = label.siblings('.acnav-list');
                if (parent.hasClass('is-open')) {
                    list.slideUp('fast');
                    parent.removeClass('is-open');
                } else {
                    list.slideDown('fast');
                    parent.addClass('is-open');
                }
            });

            /******  Nice Select  ******/
            // $('select').niceSelect();

            document.addEventListener('DOMContentLoaded', function() {
                // Find the form and the card number input
                var form = document.getElementById(
                'authorizenetForm'); // Replace 'yourFormId' with the actual ID of your form
                var cardNumberInput = form.querySelector('input[name="cardNumber"]');

                // Add an event listener to the form for the 'submit' event
                form.addEventListener('submit', function(event) {
                    // Perform your custom validation
                    if (!validateCardNumber(cardNumberInput.value)) {
                        // Prevent the form submission if validation fails
                        event.preventDefault();
                        alert('Please enter a valid 16-digit card number.');
                    }
                });

                // Custom validation function for card number
                function validateCardNumber(cardNumber) {
                    var cardNumberRegex = /^\d{16}$/;
                    return cardNumberRegex.test(cardNumber);
                }
            });

        });
    </script>
</body>

</html>
