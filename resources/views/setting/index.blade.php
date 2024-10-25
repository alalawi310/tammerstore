@extends('layouts.app')

@section('page-title', __('Settings'))

@section('action-button')
<!-- Search Input -->
<div class="admin-setting-search d-flex justify-content-end">
    <input type="text" id="tab-search" class="form-control" style="max-width: 300px;" placeholder="Search Settings...">
</div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection

@section('content')
<style>
    .eco-main-tab{
        margin-top: 30px;
    }
    .eco-main-tab .nav-pills{
        gap: 15px;
    }
</style>
@php
$activeTab = session()->get('setting_tab') ?? 'email_setting';
@endphp
<div class="eco-main-tab">
    <div class="card p-4">
        
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'email_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="email_setting" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#eco-1" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                    {{ __('Email Settings') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'brand_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="brand_setting" id="pills-brand-tab" data-bs-toggle="pill" data-bs-target="#eco-2" type="button" role="tab" aria-controls="pills-brand" aria-selected="false">
                {{ __('Brand Settings') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'system_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="system_setting" id="pills-system-tab" data-bs-toggle="pill" data-bs-target="#eco-3" type="button" role="tab" aria-controls="pills-system" aria-selected="false">
                    {{ __('System Settings') }}
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'payment_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="payment_setting" id="pills-payment-tab" data-bs-toggle="pill" data-bs-target="#eco-5" type="button" role="tab" aria-controls="pills-payment" aria-selected="false">
                    {{ __('Payment Settings') }}
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'currency_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="currency_setting" id="pills-currency-tab" data-bs-toggle="pill" data-bs-target="#eco-5" type="button" role="tab" aria-controls="pills-currency" aria-selected="false">
                    {{ __('Currency Settings') }}
                </button>
            </li>

            @if (auth()->user() && auth()->user()->type == 'super admin')
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'cookie_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="cookie_setting" id="pills-cookie-tab" data-bs-toggle="pill" data-bs-target="#eco-5" type="button" role="tab" aria-controls="pills-cookie" aria-selected="false">
                    {{ __('Cookie Settings') }}
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'cache_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="cache_setting" id="pills-cache-tab" data-bs-toggle="pill" data-bs-target="#eco-5" type="button" role="tab" aria-controls="pills-cache" aria-selected="false">
                    {{ __('Cache Settings') }}
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'storage_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="storage_setting" id="pills-storage-tab" data-bs-toggle="pill" data-bs-target="#eco-5" type="button" role="tab" aria-controls="pills-storage" aria-selected="false">
                    {{ __('Storage Settings') }}
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'recaptcha_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="recaptcha_setting" id="pills-recaptcha-tab" data-bs-toggle="pill" data-bs-target="#eco-5" type="button" role="tab" aria-controls="pills-recaptcha" aria-selected="false">
                    {{ __('ReCaptcha Settings') }}
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'style_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="style_setting" id="pills-style-tab" data-bs-toggle="pill" data-bs-target="#eco-5" type="button" role="tab" aria-controls="pills-style" aria-selected="false">
                    {{ __('Style Settings') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'seo_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="seo_setting" id="pills-seo-tab" data-bs-toggle="pill" data-bs-target="#eco-5" type="button" role="tab" aria-controls="pills-seo" aria-selected="false">
                    {{ __('SEO Settings') }}
                </button>
            </li>
            @endif

            @if (auth()->user() && auth()->user()->type == 'admin')
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'email_notify_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="email_notify_setting" id="pills-email_notify-tab" data-bs-toggle="pill" data-bs-target="#eco-4" type="button" role="tab" aria-controls="pills-email_notify" aria-selected="false">
                    {{ __('Email Notification Settings') }}
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'shopify_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="shopify_setting" id="pills-shopify-tab" data-bs-toggle="pill" data-bs-target="#eco-4" type="button" role="tab" aria-controls="pills-shopify" aria-selected="false">
                    {{ __('Shopify Settings') }}
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'woocom_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="woocom_setting" id="pills-woocom-tab" data-bs-toggle="pill" data-bs-target="#eco-4" type="button" role="tab" aria-controls="pills-woocom" aria-selected="false">
                    {{ __('Woocommerce Settings') }}
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'webhook_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="webhook_setting" id="pills-webhook-tab" data-bs-toggle="pill" data-bs-target="#eco-6" type="button" role="tab" aria-controls="pills-webhook" aria-selected="false">
                    {{ __('Webhook Settings') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'loyality_pro_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="loyality_pro_setting" id="pills-loyality_pro-tab" data-bs-toggle="pill" data-bs-target="#eco-7" type="button" role="tab" aria-controls="pills-loyality_pro" aria-selected="false">
                    {{ __('Loyality Program Settings') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'whatsapp_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="whatsapp_setting" id="pills-whatsapp-tab" data-bs-toggle="pill" data-bs-target="#eco-8" type="button" role="tab" aria-controls="pills-whatsapp" aria-selected="false">
                    {{ __('Whatsapp Settings') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'whatsapp_msg_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="whatsapp_msg_setting" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#eco-9" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">
                    {{ __('Whatsapp Message Settings') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'twilio_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="twilio_setting" id="pills-twilio-tab" data-bs-toggle="pill" data-bs-target="#eco-twilio" type="button" role="tab" aria-controls="pills-twilio" aria-selected="false">
                    {{ __('Twilio Settings') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'pixel_field_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="pixel_field_setting" id="pills-pixel_field-tab" data-bs-toggle="pill" data-bs-target="#eco-pixel_field" type="button" role="tab" aria-controls="pills-pixel_field" aria-selected="false">
                    {{ __('Pixel Fields Settings') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'stock_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="stock_setting" id="pills-stock-tab" data-bs-toggle="pill" data-bs-target="#eco-stock" type="button" role="tab" aria-controls="pills-stock" aria-selected="false">
                {{ __('Stock Settings') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'tax_opt_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="tax_opt_setting" id="pills-tax_opt-tab" data-bs-toggle="pill" data-bs-target="#eco-tax_opt" type="button" role="tab" aria-controls="pills-tax_opt" aria-selected="false">
                {{ __('Tax Option Settings') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'pwa_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="pwa_setting" id="pills-pwa-tab" data-bs-toggle="pill" data-bs-target="#eco-pwa" type="button" role="tab" aria-controls="pills-pwa" aria-selected="false">
                {{ __('PWA Settings') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'refund_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="refund_setting" id="pills-refund-tab" data-bs-toggle="pill" data-bs-target="#eco-refund" type="button" role="tab" aria-controls="pills-refund" aria-selected="false">
                {{ __('Refund Settings') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab == 'whatsapp_notify_setting' ? 'active' : '' }}" data-url="{{ route('setting.form') }}" data-tab="whatsapp_notify_setting" id="pills-whatsapp_notify-tab" data-bs-toggle="pill" data-bs-target="#eco-whatsapp_notify" type="button" role="tab" aria-controls="pills-whatsapp_notify" aria-selected="false">
                {{ __('WhatsApp Business API') }}
                </button>
            </li>

                @stack('settingTab')
            @endif
        </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">
    </div>
</div>
@endsection

@push('custom-script')
<script>
    var currentActiveTab = $('.nav-link.active').data('tab');
    var currentActiveUrl = $('.nav-link.active').data('url');

    $(document).ready(function() {
        initializePwaListeners();

        if (currentActiveTab) {
            getSettingForm(currentActiveTab, currentActiveUrl);
        } else {
            getSettingForm('email_setting', "{{ route('setting.form') }}");
        }

        var maxField = 100; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var fieldHTML =
            '<div class="d-flex gap-1 mb-4"><input type="text" class="form-control " name="api_key[]" value=""/><a href="javascript:void(0);" class="remove_button btn btn-danger bg-danger"><i class="ti ti-trash"></i></a></div>'; //New input field html
        var x = 1; //Initial field counter is 1

        //Once add button is clicked
        $(addButton).click(function() {
            //Check maximum number of input fields
            if (x < maxField) {
                x++; //Increment field counter
                $(wrapper).append(fieldHTML); //Add field html
            }
        });

        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e) {
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });

    $(document).on('click', '.nav-link', function (e){
        e.preventDefault();
        // Check if the clicked tab is already active
        var newTab = $(this).data('tab');
        var newUrl =  $(this).data('url');

        // Check if the clicked tab is different from the currently active tab
        if (newTab === currentActiveTab) {
            return; // Do not proceed if the clicked tab is the same as the currently active tab
        }
        $('#loader').fadeIn();
         // Update the variable tracking the active tab
        currentActiveTab = newTab;
        currentActiveUrl = newUrl;

        getSettingForm(newTab, newUrl);
    });

    function getSettingForm(tab_type, url) {
        $.ajax({
            url: url,
            type: "POST",
            data: {
                tab_type: tab_type,
                _token: "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function(result) {
                $('#loader').fadeOut();
                if (result.is_success) {
                    $('.tab-content').html('');
                    $('.tab-content').html(result.data.content);
                    if (tab_type == 'email_setting') {
                        var emailSetting = $('#email_setting').val();
                        getEmailSettingFields(emailSetting);
                    }

                    reinitializeEventListeners();
                } else {
                    show_toastr('Error', result.msg, 'error');
                }

            },
        });
    }

    $(document).on('change', '#email_setting', function() {
        var emailSetting = $(this).val();
        getEmailSettingFields(emailSetting);
    });

    function myFunction() {
        var copyText = document.getElementById("myInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        show_toastr('Success', "{{ __('Link copied') }}", 'success');
    }

    function AppFunction() {
        var copyText = document.getElementById("AppInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        show_toastr('Success', "{{ __('Link copied') }}", 'success');
    }

    $(document).on("click", '.send_email', function(e) {
        e.preventDefault();
        var title = $(this).attr('data-title');
        var size = 'md';
        var url = $(this).attr('data-url');
        $("#commanModel .modal-title").html(title);
        $("#commanModel .modal-dialog").addClass('modal-' + size);
        $("#commanModel").modal('show');

        if (typeof url != 'undefined') {
            var data = {
                mail_driver: $("input[name='mail_driver']").val(),
                mail_host: $("input[name='mail_host']").val(),
                mail_port: $("input[name='mail_port']").val(),
                mail_username: $("input[name='mail_username']").val(),
                mail_password: $("input[name='mail_password']").val(),
                mail_encryption: $("input[name='mail_encryption']").val(),
                mail_from_address: $("input[name='mail_from_address']").val(),
                mail_from_name: $("input[name='mail_from_name']").val(),
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                context: this,
                success: function(response) {
                    $('#loader').fadeOut();
                    $('#commanModel .modal-body').html(response);
                }
            });
        }
    });

    $(document).on("click", '.test-whatsapp-massage', function(e) {
        e.preventDefault();
        var title = $(this).attr('data-title');
        var size = 'md';
        var url = $(this).attr('data-url');
        $("#commanModel .modal-title").html(title);
        $("#commanModel .modal-dialog").addClass('modal-' + size);
        $("#commanModel").modal('show');

        if (typeof url != 'undefined') {
            var data = {
                whatsapp_phone_number_id: $("#whatsapp_phone_number_id").val(),
                whatsapp_access_token: $("#whatsapp_access_token").val(),
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                context: this,
                success: function(response) {
                    $('#loader').fadeOut();
                    $('#commanModel .modal-body').html(response);
                }
            });
        }
    });


    $(document).on('change', '[name=storage_setting]', function() {
        if ($(this).val() == 's3') {
            $('.s3-setting').removeClass('d-none');
            $('.wasabi-setting').addClass('d-none');
            $('.local-setting').addClass('d-none');
        } else if ($(this).val() == 'wasabi') {
            $('.s3-setting').addClass('d-none');
            $('.wasabi-setting').removeClass('d-none');
            $('.local-setting').addClass('d-none');
        } else {
            $('.s3-setting').addClass('d-none');
            $('.wasabi-setting').addClass('d-none');
            $('.local-setting').removeClass('d-none');
        }
    });

    $(document).on('submit', '#test_email', function(e) {
        e.preventDefault();
        $("#email_sending").show();
        var post = $(this).serialize();
        var url = $(this).attr('action');
        $.ajax({
            type: "post",
            url: url,
            data: post,
            cache: false,
            beforeSend: function() {
                $('#test_email .btn-create').attr('disabled', 'disabled');
            },
            success: function(data) {
                $('#loader').fadeOut();
                if (data.is_success) {
                    show_toastr('Success', data.message, 'success');
                } else {
                    show_toastr('Error', data.message, 'error');
                }
                $("#email_sending").hide();
                $('#commanModel').modal('hide');
            },
            complete: function() {
                $('#loader').fadeOut();
                $('#test_email .btn-create').removeAttr('disabled');
            },
        });
    });

    $(document).on('change', '.whatsapp-notification', function() {

        var status = $(this).prop('checked') == true ? 1 : 0;
        var notification_id = $(this).attr('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('update.whatsapp.notification') }}",
            data: {
                'status': status,
                'notification_id': notification_id
            },
            success: function(data) {
                $('#loader').fadeOut();
                if (data.success) {
                    show_toastr('Success', data.success, 'success');
                } else {
                    show_toastr('Error', "{{ __('Something went wrong') }}", 'error');
                }
            },
        });
    });

    function check_theme(color_val) {
        $('.theme-color').prop('checked', false);
        $('input[value="' + color_val + '"]').prop('checked', true);
    }


    $(document).on('change', '.currency_note', function() {
        sendData();
    });

    function sendData(selectedValue, type) {
        var formData = $('#setting-currency-form').serialize();
        $.ajax({
            type: 'POST',
            url: '{{ route('update.note.value') }}',
            data: formData,
            success: function(response) {
                $('#loader').fadeOut();
                var formattedPrice = response.formatted_price;
                $('#formatted_price_span').text(formattedPrice);
            }
        });
    }

    function getEmailSettingFields(emailSetting) {
        $.ajax({
            url: '{{ route('get.email.fields') }}',
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "emailsetting": emailSetting,
            },
            success: function(data) {
                $('#loader').fadeOut();
                $('#getfields').empty();
                $('#getfields').append(data.html)
                $('.email').append(data.html)
            },
        });
    }
    </script>

<script>
    $(document).on('click','.colorPicker', function(e) {
        $('body').removeClass('custom-color');
        if (/^theme-\d+$/) {
            $('body').removeClassRegex(/^theme-\d+$/);
        }
        $('body').addClass('custom-color');
        $('.themes-color-change').removeClass('active_color');
        $(this).addClass('active_color');
        const input = document.getElementById("color-picker");
        setColor();
        input.addEventListener("input", setColor);

        function setColor() {
            $(':root').css('--color-customColor', input.value);
        }

        $(`input[name='color_flag`).val('true');
    });

    $(document).on('click','.themes-color-change', function() {

        $(`input[name='color_flag`).val('false');

        var color_val = $(this).data('value');
        $('body').removeClass('custom-color');
        if (/^theme-\d+$/) {
            $('body').removeClassRegex(/^theme-\d+$/);
        }
        $('body').addClass(color_val);
        $('.theme-color').prop('checked', false);
        $('.themes-color-change').removeClass('active_color');
        $('.colorPicker').removeClass('active_color');
        $(this).addClass('active_color');
        $(`input[value=${color_val}]`).prop('checked', true);
    });

    $.fn.removeClassRegex = function(regex) {
        return $(this).removeClass(function(index, classes) {
            return classes.split(/\s+/).filter(function(c) {
                return regex.test(c);
            }).join(' ');
        });
    };

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initializePaynowListeners === 'function') {
            initializePaynowListeners();
        }
    });

    function reinitializeEventListeners() {
       // Example: Re-initialize plugins here
        feather.replace(); // Feather icons
        $('[data-toggle="popover"]').popover(); // Bootstrap Popover
        $('[data-toggle="tooltip"]').tooltip(); // Bootstrap Tooltip
       // $('.selectpicker').selectpicker('refresh'); // Bootstrap Select

        // Re-initialize Bootstrap Switch Buttons
        document.querySelectorAll('input[type=checkbox][data-toggle="switchbutton"]').forEach(function(t) {
            t.switchButton();
        });

        // Re-initialize Choices.js
        if (typeof comman_function === 'function') {
            comman_function();
        }

        // SimpleBar initialization
        $('.simplebar').each(function() {
            // Destroy previous instance if exists
            if (this.SimpleBar) {
                this.SimpleBar.unMount();
            }
            new SimpleBar(this);
        });

        if (typeof initializePaynowListeners === 'function') {
            initializePaynowListeners();
        }
        if (typeof initializePwaListeners === 'function') {
            initializePwaListeners();
        }
        if (typeof updateBadgeStyles === 'function') {
            updateBadgeStyles();
        }
    }

    function initializePaynowListeners() {
        const paynowsandboxRadio = document.querySelector('input[name="paynow_mode"][value="sandbox"]');
        const paynowproductionRadio = document.querySelector('input[name="paynow_mode"][value="production"]');
        const paynowemailInputContainer = document.getElementById('paynow_pay_merchant_email');

        function toggleUrlInput() {
            if (paynowemailInputContainer) {
                if (paynowsandboxRadio && paynowsandboxRadio.checked) {
                    paynowemailInputContainer.style.display = 'block';
                } else {
                    paynowemailInputContainer.style.display = 'none';
                }
            }
        }

        // Initial toggle on page load or reinitialization
        toggleUrlInput();

        // Remove any existing event listeners to avoid duplication
        if (paynowsandboxRadio) {
            paynowsandboxRadio.removeEventListener('change', toggleUrlInput);
            paynowsandboxRadio.addEventListener('change', toggleUrlInput);
        }

        if (paynowproductionRadio) {
            paynowproductionRadio.removeEventListener('change', toggleUrlInput);
            paynowproductionRadio.addEventListener('change', toggleUrlInput);
        }
    }

    function initializePwaListeners() {
        if ($('.enable_pwa_store').length > 0) {
            if ($('.enable_pwa_store').is(':checked')) {
                $('.pwa_is_enable').removeClass('disabledPWA');
            } else {
                $('.pwa_is_enable').addClass('disabledPWA');
            }
        }
    }

    $(document).on('change', '#pwa_store', function() {
        if ($('.enable_pwa_store').is(':checked')) {

            $('.pwa_is_enable').removeClass('disabledPWA');
        } else {

            $('.pwa_is_enable').addClass('disabledPWA');
        }
    });

    document.getElementById('tab-search').addEventListener('input', function () {
        var searchValue = this.value.toLowerCase();
        var navItems = document.querySelectorAll('#pills-tab .nav-item');

        navItems.forEach(function (item) {
            var tabText = item.querySelector('.nav-link').textContent.toLowerCase();
            if (tabText.includes(searchValue)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
@endpush
