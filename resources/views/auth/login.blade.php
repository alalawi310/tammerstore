@php
    $adminSetting = getSuperAdminAllSetting();
    config([
        'captcha.secret' => $adminSetting['NOCAPTCHA_SECRET'] ?? '',
        'captcha.sitekey' => $adminSetting['NOCAPTCHA_SITEKEY'] ?? '',
        'options' => [
            'timeout' => 30,
        ],
    ]);
@endphp

@extends('layouts.guest')
@section('page-title')
    {{ __('Login') }}
@endsection
@if ($adminSetting['cust_darklayout'] == 'on')
    <style>
        .g-recaptcha {
            filter: invert(1) hue-rotate(180deg) !important;
        }
    </style>
@endif
<!-- Session Status -->
@section('content')
    <div class="">
        <h2 class="mb-3 f-w-600">{{ __('Login') }}</h2>
    </div>
    <div class="">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}" id="form_data">
            @csrf
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Email') }}</label>
                <input id="email" class="form-control" type="email" name="email" :value="old('email')" placeholder="{{ __('Enter Email') }}" required
                    autofocus />
            </div>
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Password') }}</label>
                <input id="password" class="form-control" type="password" name="password" required
                    autocomplete="current-password" placeholder="{{ __('Enter Password') }}"/>
            </div>

            <div class="my-1">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-primary text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            @if (isset($adminSetting['RECAPTCHA_MODULE']) && $adminSetting['RECAPTCHA_MODULE'] == 'yes')
                @if (isset($adminSetting['NOCAPTCHA_VERSON']) && $adminSetting['NOCAPTCHA_VERSON'] == 'v2')
                    <div class="form-group col-lg-12 col-md-12 mt-3">
                        {!! NoCaptcha::display($adminSetting['cust_darklayout'] == 'on' ? ['data-theme' => 'dark'] : []) !!}
                        @error('g-recaptcha-response')
                            <span class="small text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                @else
                    <div class="form-group col-lg-12 col-md-12 mt-3">
                        <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" class="form-control">
                        @error('g-recaptcha-response')
                            <span class="error small text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                @endif
            @endif

            <div class="d-grid">
                {{-- {!! Form::hidden('type', 'admin') !!} --}}
                <button class="btn btn-primary btn-block mt-2 login_button" type="submit"> {{ __('Login') }} </button>
            </div>
            @if ($adminSetting['SIGNUP'] == 'on')
            <p class="my-4 text-center">{{ __("Don't have an account?") }}
               
                <a href="{{ route('register') }}" class="my-4 text-primary">{{ __('Register') }}</a>
                
            </p>
            @endif
        </form>
    </div>
@endsection

@push('scripts')
    @if (isset($adminSetting['RECAPTCHA_MODULE']) && $adminSetting['RECAPTCHA_MODULE'] == 'yes')
        @if (isset($adminSetting['NOCAPTCHA_VERSON']) && $adminSetting['NOCAPTCHA_VERSON'] == 'v2')
                {!! NoCaptcha::renderJs() !!}
        @else
            <script src="https://www.google.com/recaptcha/api.js?render={{ $adminSetting['NOCAPTCHA_SITEKEY'] }}"></script>
            <script>
                $(document).ready(function() {
                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ $adminSetting['NOCAPTCHA_SITEKEY'] }}', {
                            action: 'submit'
                        }).then(function(token) {
                            $('#g-recaptcha-response').val(token);
                        });
                    });
                });
            </script>
        @endif
    @endif
    <script>
        $(document).ready(function() {
            $("#form_data").submit(function(e) {
                $('#loader').fadeIn();
                $(".login_button").attr("disabled", true);
                return true;
            });
        });
    </script>
@endpush