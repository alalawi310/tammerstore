@extends('layouts.app')

@section('page-title', __('Store Settings'))

@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Store Settings') }}</li>
@endsection

@php
    $theme_name = !empty(APP_THEME()) ? APP_THEME() : env('DATA_INSERT_APP_THEME');
    $theme_logo = \App\Models\Utility::GetValueByName('theme_logo', $theme_name);
    $theme_logo = get_file($theme_logo, APP_THEME());

    $invoice_logo = \App\Models\Utility::GetValueByName('invoice_logo', $theme_name);
    $invoice_logo = get_file($invoice_logo, APP_THEME());

    $theme_favicon = \App\Models\Utility::GetValueByName('theme_favicon', $theme_name);
    $theme_favicon = get_file($theme_favicon, APP_THEME());

    $theme_image = \App\Models\Utility::GetValueByName('theme_image', $theme_name);
    $theme_image = get_file($theme_image, APP_THEME());

    $metaimage = \App\Models\Utility::GetValueByName('metaimage', $theme_name);
    $metaimage = get_file($metaimage, APP_THEME());

    $enable_storelink = \App\Models\Utility::GetValueByName('enable_storelink', $theme_name);
    $enable_domain = \App\Models\Utility::GetValueByName('enable_domain', $theme_name);
    $domains = \App\Models\Utility::GetValueByName('domains', $theme_name);
    $enable_subdomain = \App\Models\Utility::GetValueByName('enable_subdomain', $theme_name);
    $subdomain = \App\Models\Utility::GetValueByName('subdomain', $theme_name);
    $Additional_notes = \App\Models\Utility::GetValueByName('additional_notes', $theme_name);
    $is_checkout_login_required = \App\Models\Utility::GetValueByName('is_checkout_login_required', $theme_name);

    $profile = asset('themes/' . APP_THEME() . '/assets/images');
@endphp

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="list-group list-group-flush app-seeting-tab" id="useradd-sidenav">
                    <ul class="nav nav-pills w-100 gap-2" id="pills-tab" role="tablist">
                        <li class="nav-item " role="presentation">
                            <a href="#Theme_Setting" class="nav-link @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-home-tab')) active show @endif btn-sm f-w-600" id="pills-home-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab"
                                aria-controls="pills-home" aria-selected="true">
                                {{ __('Store Settings') }}
                            </a>

                        </li>
                        <li class="nav-item " role="presentation">
                            <a href="#SEO_Setting" class="nav-link btn-sm f-w-600 @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-seo-tab')) active show @endif" id="pills-seo-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-seo" type="button" role="tab" aria-controls="pills-seo"
                                aria-selected="true">
                                {{ __('SEO Settings') }}
                            </a>

                        </li>
                        <li class="nav-item " role="presentation">
                            <a href="#custom_Setting" class="nav-link @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-custom-tab')) active show @endif btn-sm f-w-600" id="pills-custom-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-custom" type="button" role="tab"
                                aria-controls="pills-custom" aria-selected="true">
                                {{ __('Custom Settings') }}
                            </a>

                        </li>
                        <li class="nav-item " role="presentation">
                            <a href="#checkout_Setting" class="nav-link @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-checkout-tab')) active show @endif btn-sm f-w-600" id="pills-checkout-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-checkout" type="button" role="tab"
                                aria-controls="pills-checkout" aria-selected="true">
                                {{ __('Checkout Settings') }}
                            </a>

                        </li>
                        <li class="nav-item " role="presentation">
                            <a href="#shippinglabel_Setting" class="nav-link @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-shipping-tab')) active show @endif btn-sm f-w-600" id="pills-shipping-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-shipping" type="button" role="tab"
                                aria-controls="pills-shipping" aria-selected="true">
                                {{ __('Shipping Label Settings') }}
                            </a>

                        </li>
                        {{-- <li class="nav-item " role="presentation">
                            <a href="#Main_Page_Content_web"
                                class="nav-link btn-sm f-w-600 @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-profile-tab')) active show @endif"
                                id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button"
                                role="tab" aria-controls="pills-profile" aria-selected="false">
                                {{ __('Home Page Content') }}
                    </a>
                    </li> --}}
                        <li class="nav-item " role="presentation">
                            <a href="#Order_Complete_Page_Content" class="nav-link btn-sm f-w-600 @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-contact-tab')) active show @endif" id="pills-contact-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab"
                                aria-controls="pills-contact" aria-selected="false">
                                {{ __('Order Complete Screen') }}
                            </a>
                        </li>
                        <li class="nav-item " role="presentation">
                            <a href="#Page_Setting" class="nav-link btn-sm f-w-600 @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-page-tab')) active show @endif" id="pills-page-setting-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-page-setting" type="button" role="tab"
                                aria-controls="pills-page-setting" aria-selected="false">
                                {{ __('Page Setting') }}
                            </a>
                        </li>
                        @stack('appSettingTab')
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="tab-content store-tab-content" id="pills-tabContent">
                <div class="tab-pane fade @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-home-tab')) show active @endif" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div id="Theme_Setting">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center gap-3">
                                <h5 class=""> {{ __('Store Settings') }} </h5>
                            </div>
                            <div class="card-body p-4">
                                {{ Form::model($setting, ['route' => 'theme.settings', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                <input type="hidden" name="app_setting_tab" value="pills-home-tab">
                                <div class="row mt-2">
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-6">
                                            {{ Form::label('theme_name', __('Store Name'), ['class' => 'form-label']) }}
                                            {!! Form::text('theme_name', $store->name, ['class' => 'form-control', 'placeholder' => __('Store Name')]) !!}
                                            @error('theme_name')
                                                <span class="invalid-store_name" role="alert">
                                                    <strong class="text-danger">
                                                        {{ $message }}
                                                    </strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('theme_name', __('Store Language'), ['class' => 'form-label']) }}
                                            {!! Form::select('default_language', $languages, $store->default_language, [
                                                'class' => 'form-control',
                                                'data-role' => 'tagsinput',
                                                'id' => 'languages-dropdown',
                                            ]) !!}
                                            @error('default_language')
                                                <span class="invalid-store_name" role="alert">
                                                    <strong class="text-danger">
                                                        {{ $message }}
                                                    </strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="row col-md-12 g-3">
                                        @stack('appSettingEnableBtn')
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>{{ __('Store Logo') }}</h5>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class=" setting-card">
                                                    <div class="logo-content mt-3">
                                                        <a href="#" target="_blank">
                                                            <img src="{{ !empty($theme_logo) ? $theme_logo : $profile . '/logo.png' }}"
                                                                class="big-logo invoice_logo img_setting" id="storeLogo">
                                                        </a>
                                                    </div>
                                                    <div class="choose-files mt-4">
                                                        <label for="theme_logo">
                                                            <div class=" bg-primary logo_update"> <i
                                                                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            <input type="file" class="form-control file"
                                                                name="theme_logo" id="theme_logo"
                                                                data-filename="logo_update"
                                                                onchange="document.getElementById('storeLogo').src = window.URL.createObjectURL(this.files[0])">
                                                        </label>
                                                    </div>
                                                    @error('theme_logo')
                                                        <div class="row">
                                                            <span class="invalid-logo" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>{{ __('Invoice Logo') }}</h5>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class=" setting-card">
                                                    <div class="logo-content mt-3">
                                                        <a href="#" target="_blank">
                                                            <img src="{{ !empty($invoice_logo) ? $invoice_logo : $profile . '/logo.png' }}"
                                                                class="big-logo invoice_logo img_setting"
                                                                id="invoiceLogo">
                                                        </a>
                                                    </div>
                                                    <div class="choose-files mt-4">
                                                        <label for="invoice_logo">
                                                            <div class=" bg-primary logo_update"> <i
                                                                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            <input type="file" name="invoice_logo" id="invoice_logo"
                                                                class="form-control file"
                                                                data-filename="invoice_logo_update"
                                                                onchange="document.getElementById('invoiceLogo').src = window.URL.createObjectURL(this.files[0])">
                                                        </label>
                                                    </div>
                                                    @error('invoice_logo')
                                                        <div class="row">
                                                            <span class="invalid-invoice_logo" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>{{ __('Store Favicon') }}</h5>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class=" setting-card">
                                                    <div class="logo-content mt-3">
                                                        <a href="#" target="_blank">
                                                            <img src="{{ !empty($theme_favicon) ? $theme_favicon : $profile . '/logo.png' }}"
                                                                class="big-logo invoice_logo img_setting"
                                                                id="themeFavicon">
                                                        </a>
                                                    </div>
                                                    <div class="choose-files mt-4">
                                                        <label for="theme_favicon">
                                                            <div class=" bg-primary logo_update"> <i
                                                                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            <input type="file" name="theme_favicon" id="theme_favicon"
                                                                class="form-control file" data-filename="logo_update"
                                                                onchange="document.getElementById('themeFavicon').src = window.URL.createObjectURL(this.files[0])">
                                                        </label>
                                                    </div>
                                                    @error('theme_favicon')
                                                        <div class="row">
                                                            <span class="invalid-theme_favicon" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>{{ __('Store Image') }}</h5>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class=" setting-card">
                                                    <div class="logo-content mt-3">
                                                        <a href="#" target="_blank">
                                                            <img src="{{ !empty($theme_image) ? $theme_image : $profile . '/logo.png' }}"
                                                                class="big-logo store_image img_setting" id="storeImage">
                                                        </a>
                                                    </div>
                                                    <div class="choose-files mt-4">
                                                        <label for="theme_image">
                                                            <div class=" bg-primary logo_update"> <i
                                                                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            <input type="file" class="form-control file"
                                                                name="theme_image" id="theme_image"
                                                                data-filename="logo_update"
                                                                onchange="document.getElementById('storeImage').src = window.URL.createObjectURL(this.files[0])">
                                                        </label>
                                                    </div>
                                                    @error('theme_image')
                                                        <div class="row">
                                                            <span class="invalid-logo" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 text-end">
                                        {{-- @permission('Edit Store Setting') --}}
                                        <input type="submit" value="{{ __('Save Changes') }}"
                                            class="btn-submit btn btn-primary">
                                        {!! Form::close() !!}
                                        {{-- @endpermission --}}

                                        @if (\Auth::user()->type == 'admin')
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['ownerstore.remove', getCurrentStore()],
                                                'class' => 'd-inline',
                                            ]) !!}
                                            <button type="button"
                                                class="btn btn-secondary btn-danger btn-sm  show_confirm danger-btn">
                                                <span class="text-black">{{ __('Delete Store') }}</span>
                                            </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-seo-tab')) show active @endif" id="pills-seo" role="tabpanel" aria-labelledby="pills-seo-tab">
                    <div id="SEO_Setting">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center gap-3">
                                <h5 class=""> {{ __('SEO Settings') }} </h5>

                                @if ($plan && $plan->enable_chatgpt == 'on')
                                    <a href="#" class="btn btn-primary btn-sm float-end ai-btn" data-size="lg"
                                        data-ajax-popup-over="true" data-url="{{ route('generate', ['meta']) }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
                                        data-title="{{ __('Generate Content With AI') }}">
                                        <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
                                    </a>
                                @endif

                            </div>
                            <div class="card-body p-4">
                                {{ Form::model($setting, ['route' => 'seo.settings', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                <input type="hidden" name="app_setting_tab" value="pills-seo-tab">
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <i class="fab fa-google" aria-hidden="true"></i>
                                            {{ Form::label('google_analytic', __('Google Analytic'), ['class' => 'form-label']) }}
                                            {{ Form::text('google_analytic', '*********', ['class' => 'form-control', 'placeholder' => 'UA-XXXXXXXXX-X']) }}
                                            @error('google_analytic')
                                                <span class="invalid-google_analytic" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                            {{ Form::label('facebook_pixel_code', __('Facebook Pixel'), ['class' => 'form-label']) }}
                                            {{ Form::text('fbpixel_code', '*********', ['class' => 'form-control', 'placeholder' => 'UA-0000000-0']) }}
                                            @error('facebook_pixel_code')
                                                <span class="invalid-google_analytic" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('metakeyword', __('Meta Keywords'), ['class' => 'form-label']) }}
                                            {!! Form::textarea('metakeyword', '*********', [
                                                'class' => 'form-control',
                                                'rows' => 3,
                                                'placeholder' => __('Meta Keyword'),
                                            ]) !!}
                                            @error('meta_keywords')
                                                <span class="invalid-about" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('metadesc', __('Meta Description'), ['class' => 'form-label']) }}
                                            {!! Form::textarea('metadesc', '*********', [
                                                'class' => 'form-control',
                                                'rows' => 3,
                                                'placeholder' => __('Meta Description'),
                                            ]) !!}

                                            @error('meta_description')
                                                <span class="invalid-about" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group pt-0">
                                            <div class=" setting-card">
                                                <label for="" class="form-label">{{ __('Meta Image') }}</label>
                                                <div class="logo-content mt-4">
                                                    <a href="{{ asset(!empty($setting['metaimage']) ? $setting['metaimage'] : 'themes/grocery/theme_img/img_1.png') }}"
                                                        target="_blank">
                                                        <img id="meta_image" alt="your image"
                                                            src="{{ asset(!empty($setting['metaimage']) ? $setting['metaimage'] : 'themes/grocery/theme_img/img_1.png') }}"
                                                            width="150px" class="img_setting">
                                                    </a>
                                                </div>
                                                <div class="choose-files mt-3">
                                                    <label for="metaimage">
                                                        <div class=" bg-primary full_logo"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="metaimage" id="metaimage"
                                                            class="form-control file" data-filename="metaimage"
                                                            onchange="document.getElementById('meta_image').src = window.URL.createObjectURL(this.files[0])">
                                                    </label>
                                                </div>
                                                @error('metaimage')
                                                    <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 text-end">
                                        {{-- @permission('Edit Store Setting') --}}
                                        <input type="submit" value="{{ __('Save Changes') }}"
                                            class="btn-submit btn btn-primary">
                                        {!! Form::close() !!}
                                        {{-- @endpermission --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade  @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-custom-tab')) show active @endif" id="pills-custom" role="tabpanel" aria-labelledby="pills-custom-tab">
                    <div id="custom_Setting">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center gap-3">
                                <h5 class=""> {{ __('Custom Settings') }} </h5>

                            </div>
                            <div class="card-body p-4">
                                {{ Form::model($setting, ['route' => 'theme.settings', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                <input type="hidden" name="app_setting_tab" value="pills-custom-tab">
                                <div class="row mt-2">
                                    @if ($plan && ($plan->enable_domain == 'on' || $plan->enable_subdomain == 'on'))
                                        <div class="col-md-6 py-4">
                                            <div class="radio-button-group row gy-2 mts">
                                                <div class="col-sm-4">
                                                    <label
                                                        class="btn btn-outline-primary w-100 {{ $enable_storelink == 'on' ? 'active' : '' }}">
                                                        <input type="radio" class="domain_click  radio-button"
                                                            name="enable_domain" value="enable_storelink"
                                                            id="enable_storelink"
                                                            {{ $enable_storelink == 'on' ? 'checked' : '' }}>
                                                        {{ __('Store Link') }}
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    @if ($plan && ($plan->enable_domain == 'on'))
                                                        <label
                                                            class="btn btn-outline-primary w-100 {{ $enable_domain == 'on' ? 'active' : '' }}">
                                                            <input type="radio" class="domain_click radio-button"
                                                                name="enable_domain" value="enable_domain"
                                                                id="enable_domain"
                                                                {{ $enable_domain == 'on' ? 'checked' : '' }}>
                                                            {{ __('Domain') }}
                                                        </label>
                                                    @endif
                                                </div>
                                                <div class="col-sm-4">
                                                    @if ($plan && ($plan->enable_subdomain == 'on'))
                                                        <label
                                                            class="btn btn-outline-primary w-100 {{ $enable_subdomain == 'on' ? 'active' : '' }}">
                                                            <input type="radio" class="domain_click radio-button"
                                                                name="enable_domain" value="enable_subdomain"
                                                                id="enable_subdomain"
                                                                {{ $enable_subdomain == 'on' ? 'checked' : '' }}>
                                                            {{ __('Sub Domain') }}
                                                        </label>
                                                    @endif
                                                </div>
                                            </div>
                                            @if ($domainPointing == 1)
                                                <div class="text-sm mt-2" id="domainnote"
                                                    style="{{ $enable_domain == 'on' ? 'display: block' : '' }}">
                                                    <span><b class="text-success">{{ __('Note : Before add Custom Domain, your domain A record is pointing to our server IP :') }}{{ $serverIp }}|
                                                            {{ __('Your Custom Domain IP Is This: ') }}</b></span>
                                                </div>
                                            @else
                                                <div class="text-sm mt-2" id="domainnote" style="display: none">
                                                    <span><b>{{ __('Note : Before add Custom Domain, your domain A record is pointing to our server IP :') }}{{ $serverIp }}|</b>
                                                        <b
                                                            class="text-danger">{{ __('Your Custom Domain IP Is This: ') }} {{ $serverIp }}</b></span>
                                                </div>
                                            @endif
                                            @if ($subdomainPointing == 1)
                                                <div class="text-sm mt-2" id="subdomainnote" style="display: none">
                                                    <span><b class="text-success">{{ __('Note : Before add Sub Domain, your domain A record is pointing to our server IP :') }}{{ $serverIp }}|
                                                            {{ __('Your Sub Domain IP Is This: ') }}</b></span>
                                                </div>
                                            @else
                                                <div class="text-sm mt-2" id="subdomainnote" style="display: none">
                                                    <span><b>{{ __('Note : Before add Sub Domain, your domain A record is pointing to our server IP :') }}{{ $serverIp }}|</b>
                                                        <b
                                                            class="text-danger">{{ __('Your Sub Domain IP Is This: ') }}</b></span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group col-md-6" id="StoreLink"
                                            style="{{ $enable_storelink == 'on' ? 'display: block' : 'display: none' }}">
                                            {{ Form::label('store_link', __('Store Link'), ['class' => 'form-label']) }}
                                            <div class="input-group">
                                                <input type="text" value="{{ route('landing_page', $slug) }}"
                                                    id="myInput" class="form-control d-inline-block"
                                                    aria-label="Recipient's username" aria-describedby="button-addon2"
                                                    readonly>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary" type="button"
                                                        onclick="myFunction()" id="button-addon2"><i
                                                            class="far fa-copy"></i>
                                                        {{ __('Copy Link') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 domain"
                                            style="{{ $enable_domain == 'on' ? 'display:block' : 'display:none' }}">
                                            {{ Form::label('store_domain', __('Custom Domain'), ['class' => 'form-label']) }}
                                            {{ Form::text('domains', $domains, ['class' => 'form-control', 'placeholder' => __('xyz.com')]) }}
                                        </div>
                                        @if ($plan && ($plan->enable_subdomain == 'on'))
                                            <div class="form-group col-md-6 sundomain"
                                                style="{{ $enable_subdomain == 'on' ? 'display:block' : 'display:none' }}">
                                                {{ Form::label('store_subdomain', __('Sub Domain'), ['class' => 'form-label']) }}
                                                <div class="input-group">
                                                    {{ Form::text('subdomain', $slug, ['class' => 'form-control', 'placeholder' => __('Enter Domain'), 'readonly']) }}
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"
                                                            id="basic-addon2">.{{ $subdomain_name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="form-group col-md-6" id="StoreLink">
                                            {{ Form::label('store_link', __('Store Link'), ['class' => 'form-label']) }}
                                            <div class="input-group">
                                                <input type="text" value="{{ route('landing_page', $slug) }}"
                                                    id="myInput" class="form-control d-inline-block"
                                                    aria-label="Recipient's username" aria-describedby="button-addon2"
                                                    readonly>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary" type="button"
                                                        onclick="myFunction()" id="button-addon2"><i
                                                            class="far fa-copy"></i>
                                                        {{ __('Copy Link') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group col-md-6">
                                        {{ Form::label('storejs', __('Store Custom JS'), ['class' => 'form-label']) }}
                                        {{ Form::textarea('storejs', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('console.log(hello);')]) }}
                                        @error('storejs')
                                            <span class="invalid-about" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        {{ Form::label('storecss', __('Store Custom CSS'), ['class' => 'form-label']) }}
                                        {{ Form::textarea('storecss', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Custom CSS')]) }}
                                        @error('storecss')
                                            <span class="invalid-about" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 text-end">
                                        {{-- @permission('Edit Store Setting') --}}
                                        <input type="submit" value="{{ __('Save Changes') }}"
                                            class="btn-submit btn btn-primary">
                                        {!! Form::close() !!}
                                        {{-- @endpermission --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade  @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-checkout-tab')) show active @endif" id="pills-checkout" role="tabpanel" aria-labelledby="pills-checkout-tab">
                    <div id="checkout_Setting">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center gap-3">
                                <h5 class=""> {{ __('Checkout Settings') }} </h5>

                            </div>
                            <div class="card-body p-4">
                                {{ Form::model($setting, ['route' => 'theme.settings', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                <div class="row mt-2">
                                <input type="hidden" name="app_setting_tab" value="pills-checkout-tab">
                                    <div class="form-group col-lg-6 col-sm-6 col-md-6">
                                        <div class="custom-control form-switch p-0">
                                            <label class="form-check-label mb-2 text-primary"
                                                for="additional_notes">{{ __('Additional Notes') }}</label><br>
                                            <small class="mb-2 d-inline-block"> {{ __('Note') }}:
                                                {{ __('Enable the Additional Notes functionality to allow users to provide extra order-specific information on the checkout page.') }}</small><br>
                                            {!! Form::hidden('additional_notes', 'off') !!}
                                            <input type="checkbox" class="form-check-input" data-toggle="switchbutton"
                                            data-onstyle="primary" name="additional_notes" id="additional_notes"
                                            {{ isset($Additional_notes) && $Additional_notes == 'on' ? 'checked="checked"' : '' }}>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-6 col-md-6">
                                        <div class="custom-control form-switch p-0 ">
                                            <label class="form-check-label mb-2 text-primary"
                                                for="is_checkout_login_required">{{ __('Is Checkout Login Required') }}</label><br>
                                            <small class="mb-2 d-inline-block"> {{ __('Note') }}:
                                                {{ __('Use the Is Checkout Required feature to prevent guest checkout, requiring users to log in before completing their purchase for added security.') }}</small><br>
                                            {!! Form::hidden('is_checkout_login_required', 'off') !!}
                                            <input type="checkbox" class="form-check-input" data-toggle="switchbutton"
                                            data-onstyle="primary" name="is_checkout_login_required" id="is_checkout_login_required"
                                            {{ isset($is_checkout_login_required) && $is_checkout_login_required == 'on' ? 'checked="checked"' : '' }}>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 text-end">
                                        {{-- @permission('Edit Store Setting') --}}
                                        <input type="submit" value="{{ __('Save Changes') }}"
                                            class="btn-submit btn btn-primary">
                                        {!! Form::close() !!}
                                        {{-- @endpermission --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade  @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-shipping-tab')) show active @endif" id="pills-shipping" role="tabpanel" aria-labelledby="pills-shipping-tab">
                    <div id="shippinglabel_Setting">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center gap-3">
                                <h5 class=""> {{ __('Shipping Label Settings') }} </h5>

                            </div>
                            <div class="card-body p-4">
                                {{ Form::model($setting, ['route' => 'shippinglabel.settings', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                @csrf
                                <input type="hidden" name="app_setting_tab" value="pills-shipping-tab">
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('store_address', __('Address'), ['class' => 'form-label']) }}
                                            {{ Form::text('store_address', null, ['class' => 'form-control', 'placeholder' => 'Address']) }}
                                            @error('store_Address')
                                                <span class="invalid-store_Address" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('store_city', __('City'), ['class' => 'form-label']) }}
                                            {{ Form::text('store_city', null, ['class' => 'form-control', 'placeholder' => 'City']) }}
                                            @error('store_city')
                                                <span class="invalid-store_city" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('store_state', __('State'), ['class' => 'form-label']) }}
                                            {{ Form::text('store_state', null, ['class' => 'form-control', 'placeholder' => 'State']) }}
                                            @error('store_state')
                                                <span class="invalid-store_state" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('store_zipcode', __('Zipcode'), ['class' => 'form-label']) }}
                                            {{ Form::number('store_zipcode', null, ['class' => 'form-control', 'placeholder' => 'Zipcode']) }}
                                            @error('store_zipcode')
                                                <span class="invalid-store_zipcode" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('store_country', __('Country'), ['class' => 'form-label']) }}
                                            {{ Form::text('store_country', null, ['class' => 'form-control', 'placeholder' => 'Country']) }}
                                            @error('store_country')
                                                <span class="invalid-store_country" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12 text-end">
                                        {{-- @permission('Edit Store Setting') --}}
                                        <input type="submit" value="{{ __('Save Changes') }}"
                                            class="btn-submit btn btn-primary">
                                        {!! Form::close() !!}
                                        {{-- @endpermission --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade  @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-contact-tab')) show active @endif" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <div id="Order_Complete_Page_Content">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class=""> {{ __('Order Complete Screen') }} </h5>
                                </div>
                                <div class="card-body">
                                    @if (isset($json_data['section_name']))
                                        {{ Form::open(['route' => ['product.page.setting'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                        <input type="hidden" name="app_setting_tab" value="pills-contact-tab">
                                        <input type="hidden" name="section_name" value="{{ $json_data['section_slug'] }}">
                                        <input type="hidden" name="array[section_name]" value="{{ $json_data['section_name'] }}">
                                        <input type="hidden" name="array[section_slug]" value="{{ $json_data['section_slug'] }}">
                                        <input type="hidden" name="array[array_type]" value="{{ $json_data['array_type'] }}">
                                        <input type="hidden" name="array[loop_number]" value="{{ $json_data['loop_number'] ?? 1 }}" id="slider-loop-number">

                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <div>
                                                    <h5> {{ $json_data['section_name'] }} </h5>
                                                </div>
                                            </div>
                                            <div class="card-body slider-body-rows">
                                                @for ($i = 0; $i < $json_data['loop_number']; $i++)
                                                    <div class="row slider_{{ $i }}"
                                                        data-slider-index="{{ $i }}">
                                                        @if (isset($json_data['section']['title']))
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        class="form-label">{{ $json_data['section']['title']['lable'] }}</label>
                                                                    <input type="hidden"
                                                                        name="array[section][title][slug]"
                                                                        class="form-control"
                                                                        value="{{ $json_data['section']['title']['slug'] ?? '' }}">
                                                                    <input type="hidden"
                                                                        name="array[section][title][lable]"
                                                                        class="form-control"
                                                                        value="{{ $json_data['section']['title']['lable'] ?? '' }}">
                                                                    <input type="hidden"
                                                                        name="array[section][title][type]"
                                                                        class="form-control"
                                                                        value="{{ $json_data['section']['title']['type'] ?? '' }}">
                                                                    <input type="hidden"
                                                                        name="array[section][title][placeholder]"
                                                                        class="form-control"
                                                                        value="{{ $json_data['section']['title']->placeholder ?? '' }}">
                                                                    <input type="text"
                                                                        name="array[section][title][text]"
                                                                        class="form-control"
                                                                        value="{{ $json_data['section']['title']['text'] ?? '' }}"
                                                                        placeholder="{{ $json_data['section']['title']['placeholder'] }}"
                                                                        id="{{ $json_data['section']['title']['slug'] }}">

                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if (isset($json_data['section']['description']))
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        class="form-label">{{ $json_data['section']['description']['lable'] }}</label>
                                                                    <input type="hidden"
                                                                        name="array[section][description][slug]"
                                                                        class="form-control"
                                                                        value="{{ $json_data['section']['description']['slug'] ?? '' }}">
                                                                    <input type="hidden"
                                                                        name="array[section][description][lable]"
                                                                        class="form-control"
                                                                        value="{{ $json_data['section']['description']['lable'] ?? '' }}">
                                                                    <input type="hidden"
                                                                        name="array[section][description][type]"
                                                                        class="form-control"
                                                                        value="{{ $json_data['section']['description']['type'] ?? '' }}">
                                                                    <input type="hidden"
                                                                        name="array[section][description][placeholder]"
                                                                        class="form-control"
                                                                        value="{{ $json_data['section']['description']['placeholder'] ?? '' }}">
                                                                    <textarea type="text" name="array[section][description][text]" class="form-control"
                                                                        placeholder="{{ $json_data['section']['description']['placeholder'] }}"
                                                                        id="{{ $json_data['section']['description']['slug'] }}"> {{ $json_data['section']['description']['text'] }} </textarea>
                                                                    {{-- <small>{{ $json_data['section']['description']['placeholder'] }}</small>
                                                    --}}

                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                        {{-- @permission('Edit Store Setting') --}}
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">{{__('save')}}</button>
                                        </div>
                                        {{-- @endpermission --}}
                                        {!! Form::close() !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade  @if(isset($app_setting_tab) && ($app_setting_tab == 'pills-page-tab')) show active @endif" id="pills-page-setting" role="tabpanel"
                    aria-labelledby="pills-page-setting-tab">
                    <div id="Page_Setting">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class=""> {{ __('Page Setting') }} </h5>
                                </div>
                                <div class="card-body">
                                    <div class="faq">
                                        <div class="accordion accordion-flush">
                                            {{--@if (isset($product_json_data['section_name']))
                                                {{ Form::open(['route' => ['product.page.setting'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                                <input type="hidden" name="app_setting_tab" value="pills-page-tab">
                                                    <input type="hidden" name="section_name"
                                                        value="{{ $product_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[section_name]"
                                                        value="{{ $product_json_data['section_name'] }}">
                                                    <input type="hidden" name="array[section_slug]"
                                                        value="{{ $product_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[array_type]"
                                                        value="{{ $product_json_data['array_type'] }}">
                                                    <input type="hidden" name="array[loop_number]"
                                                        value="{{ $product_json_data['loop_number'] ?? 1 }}">

                                                    <div class="accordion-item card">
                                                        <h5 class="accordion-header" id="product_page_setting">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapseone_product_page_setting"
                                                                aria-expanded="false"
                                                                aria-controls="collapseone_product_page_setting">

                                                                <span class="d-flex align-items-center">
                                                                <b> {{ $product_json_data['section_name'] }}</b>
                                                                </span>
                                                            </button>
                                                        </h5>
                                                        <div id="collapseone_product_page_setting" class="accordion-collapse collapse" aria-labelledby="product_page_setting" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body p-4">
                                                                <div class="card-body">
                                                                    @for ($i = 0; $i < $product_json_data['loop_number']; $i++)
                                                                        <div class="row">
                                                                            @if (isset($product_json_data['section']['title']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $product_json_data['section']['title']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['title']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['title']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['title']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['title']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][title][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['title']['text'] ?? '' }}"
                                                                                            placeholder="{{ $product_json_data['section']['title']['placeholder'] }}"
                                                                                            id="{{ $product_json_data['section']['title']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($product_json_data['section']['description']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $product_json_data['section']['description']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['description']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['description']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['description']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['description']['placeholder'] ?? '' }}">
                                                                                        <textarea type="text" name="array[section][description][text]" class="form-control"
                                                                                            placeholder="{{ $product_json_data['section']['description']['placeholder'] }}"
                                                                                            id="{{ $product_json_data['section']['description']['slug'] }}"> {{ $product_json_data['section']['description']['text'] }} </textarea>
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($product_json_data['section']['button']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $product_json_data['section']['button']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['button']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['button']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['button']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['button']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][button][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['button']['text'] ?? '' }}"
                                                                                            placeholder="{{ $product_json_data['section']['button']['placeholder'] }}"
                                                                                            id="{{ $product_json_data['section']['button']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($product_json_data['section']['image']))
                                                                                <div class="col-sm-2">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $product_json_data['section']['image']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['image']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['image']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['image']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['image']->placeholder ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][image]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['image']['image'] ?? '' }}">
                                                                                        <input type="file"
                                                                                            name="array[section][image][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $product_json_data['section']['image']['text'] ?? '' }}"
                                                                                            placeholder="{{ $product_json_data['section']['image']['placeholder'] }}"
                                                                                            id="{{ $product_json_data['section']['image']['slug'] }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="logo-content mt-0">
                                                                                        @if (isset($product_json_data['section']['image']['image']))
                                                                                        <a href="#" target="_blank">
                                                                                            <img src="{{ get_file($product_json_data['section']['image']['image'] ?? '#', APP_THEME()) }}"
                                                                                                class="big-logo invoice_logo img_setting"
                                                                                                id="productImage">
                                                                                        </a>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endfor

                                                                    @permission('Edit Store Setting')
                                                                    <div class="text-end">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">save</button>
                                                                    </div>
                                                                    @endpermission
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {!! Form::close() !!}
                                            @endif --}}

                                            @if (isset($blog_json_data['section_name']))
                                                {{ Form::open(['route' => ['product.page.setting'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                                <input type="hidden" name="app_setting_tab" value="pills-page-tab">
                                                    <input type="hidden" name="section_name"
                                                        value="{{ $blog_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[section_name]"
                                                        value="{{ $blog_json_data['section_name'] }}">
                                                    <input type="hidden" name="array[section_slug]"
                                                        value="{{ $blog_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[array_type]"
                                                        value="{{ $blog_json_data['array_type'] }}">
                                                    <input type="hidden" name="array[loop_number]"
                                                        value="{{ $blog_json_data['loop_number'] ?? 1 }}">

                                                    <div class="accordion-item card">
                                                        <h5 class="accordion-header" id="blog_page_setting">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapseone_blog_page_setting"
                                                                aria-expanded="false"
                                                                aria-controls="collapseone_blog_page_setting">

                                                                <span class="d-flex align-items-center">
                                                                <b> {{ $blog_json_data['section_name'] }}</b>
                                                                </span>
                                                            </button>
                                                        </h5>
                                                        <div id="collapseone_blog_page_setting" class="accordion-collapse collapse" aria-labelledby="blog_page_setting" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body p-4">
                                                                <div class="card-body">
                                                                    @for ($i = 0; $i < $blog_json_data['loop_number']; $i++)
                                                                        <div class="row">
                                                                            @if (isset($blog_json_data['section']['title']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $blog_json_data['section']['title']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['title']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['title']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['title']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['title']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][title][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['title']['text'] ?? '' }}"
                                                                                            placeholder="{{ $blog_json_data['section']['title']['placeholder'] }}"
                                                                                            id="{{ $blog_json_data['section']['title']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($blog_json_data['section']['description']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $blog_json_data['section']['description']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['description']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['description']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['description']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['description']['placeholder'] ?? '' }}">
                                                                                        <textarea type="text" name="array[section][description][text]" class="form-control"
                                                                                            placeholder="{{ $blog_json_data['section']['description']['placeholder'] }}"
                                                                                            id="{{ $blog_json_data['section']['description']['slug'] }}"> {{ $blog_json_data['section']['description']['text'] }} </textarea>
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($blog_json_data['section']['button']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $blog_json_data['section']['button']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['button']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['button']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['button']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['button']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][button][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['button']['text'] ?? '' }}"
                                                                                            placeholder="{{ $blog_json_data['section']['button']['placeholder'] }}"
                                                                                            id="{{ $blog_json_data['section']['button']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($blog_json_data['section']['image']))
                                                                                <div class="col-sm-2">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $blog_json_data['section']['image']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['image']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['image']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['image']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['image']->placeholder ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][image]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['image']['image'] ?? '' }}">
                                                                                        <input type="file"
                                                                                            name="array[section][image][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $blog_json_data['section']['image']['text'] ?? '' }}"
                                                                                            placeholder="{{ $blog_json_data['section']['image']['placeholder'] }}"
                                                                                            id="{{ $blog_json_data['section']['image']['slug'] }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="logo-content mt-0">
                                                                                        @if (isset($blog_json_data['section']['image']['image']))
                                                                                        <a href="#" target="_blank">
                                                                                            <img src="{{ get_file($blog_json_data['section']['image']['image'] ?? '#', APP_THEME()) }}"
                                                                                                class="big-logo invoice_logo img_setting"
                                                                                                id="productImage">
                                                                                        </a>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endfor

                                                                    {{-- @permission('Edit Store Setting') --}}
                                                                    <div class="text-end">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">{{__('save')}}</button>
                                                                    </div>
                                                                    {{-- @endpermission --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {!! Form::close() !!}
                                            @endif

                                            @if (isset($article_json_data['section_name']))
                                                {{ Form::open(['route' => ['product.page.setting'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                                <input type="hidden" name="app_setting_tab" value="pills-page-tab">
                                                    <input type="hidden" name="section_name"
                                                        value="{{ $article_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[section_name]"
                                                        value="{{ $article_json_data['section_name'] }}">
                                                    <input type="hidden" name="array[section_slug]"
                                                        value="{{ $article_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[array_type]"
                                                        value="{{ $article_json_data['array_type'] }}">
                                                    <input type="hidden" name="array[loop_number]"
                                                        value="{{ $article_json_data['loop_number'] ?? 1 }}">

                                                    <div class="accordion-item card">
                                                        <h5 class="accordion-header" id="article_page_setting">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapseone_article_page_setting"
                                                                aria-expanded="false"
                                                                aria-controls="collapseone_article_page_setting">

                                                                <span class="d-flex align-items-center">
                                                                <b> {{ $article_json_data['section_name'] }}</b>
                                                                </span>
                                                            </button>
                                                        </h5>
                                                        <div id="collapseone_article_page_setting" class="accordion-collapse collapse" aria-labelledby="article_page_setting" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body p-4">
                                                                <div class="card-body">
                                                                    @for ($i = 0; $i < $article_json_data['loop_number']; $i++)
                                                                        <div class="row">
                                                                            @if (isset($article_json_data['section']['title']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $article_json_data['section']['title']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['title']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['title']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['title']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['title']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][title][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['title']['text'] ?? '' }}"
                                                                                            placeholder="{{ $article_json_data['section']['title']['placeholder'] }}"
                                                                                            id="{{ $article_json_data['section']['title']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($article_json_data['section']['description']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $article_json_data['section']['description']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['description']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['description']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['description']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['description']['placeholder'] ?? '' }}">
                                                                                        <textarea type="text" name="array[section][description][text]" class="form-control"
                                                                                            placeholder="{{ $article_json_data['section']['description']['placeholder'] }}"
                                                                                            id="{{ $article_json_data['section']['description']['slug'] }}"> {{ $article_json_data['section']['description']['text'] }} </textarea>
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($article_json_data['section']['button']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $article_json_data['section']['button']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['button']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['button']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['button']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['button']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][button][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['button']['text'] ?? '' }}"
                                                                                            placeholder="{{ $article_json_data['section']['button']['placeholder'] }}"
                                                                                            id="{{ $article_json_data['section']['button']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($article_json_data['section']['image']))
                                                                                <div class="col-sm-2">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $article_json_data['section']['image']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['image']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['image']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['image']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['image']->placeholder ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][image]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['image']['image'] ?? '' }}">
                                                                                        <input type="file"
                                                                                            name="array[section][image][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $article_json_data['section']['image']['text'] ?? '' }}"
                                                                                            placeholder="{{ $article_json_data['section']['image']['placeholder'] }}"
                                                                                            id="{{ $article_json_data['section']['image']['slug'] }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="logo-content mt-0">
                                                                                        @if (isset($article_json_data['section']['image']['image']))
                                                                                        <a href="#" target="_blank">
                                                                                            <img src="{{ get_file($article_json_data['section']['image']['image'] ?? '#', APP_THEME()) }}"
                                                                                                class="big-logo invoice_logo img_setting"
                                                                                                id="productImage">
                                                                                        </a>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endfor

                                                                    {{-- @permission('Edit Store Setting') --}}
                                                                    <div class="text-end">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">{{__('save')}}</button>
                                                                    </div>
                                                                    {{-- @endpermission --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {!! Form::close() !!}
                                            @endif

                                            @if (isset($cart_json_data['section_name']))
                                                {{ Form::open(['route' => ['product.page.setting'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                                <input type="hidden" name="app_setting_tab" value="pills-page-tab">
                                                    <input type="hidden" name="section_name"
                                                        value="{{ $cart_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[section_name]"
                                                        value="{{ $cart_json_data['section_name'] }}">
                                                    <input type="hidden" name="array[section_slug]"
                                                        value="{{ $cart_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[array_type]"
                                                        value="{{ $cart_json_data['array_type'] }}">
                                                    <input type="hidden" name="array[loop_number]"
                                                        value="{{ $cart_json_data['loop_number'] ?? 1 }}">

                                                    <div class="accordion-item card">
                                                        <h5 class="accordion-header" id="cart_page_setting">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapseone_cart_page_setting"
                                                                aria-expanded="false"
                                                                aria-controls="collapseone_cart_page_setting">

                                                                <span class="d-flex align-items-center">
                                                                <b> {{ $cart_json_data['section_name'] }}</b>
                                                                </span>
                                                            </button>
                                                        </h5>
                                                        <div id="collapseone_cart_page_setting" class="accordion-collapse collapse" aria-labelledby="cart_page_setting" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body p-4">
                                                                <div class="card-body">
                                                                    @for ($i = 0; $i < $cart_json_data['loop_number']; $i++)
                                                                        <div class="row">
                                                                            @if (isset($cart_json_data['section']['title']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $cart_json_data['section']['title']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['title']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['title']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['title']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['title']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][title][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['title']['text'] ?? '' }}"
                                                                                            placeholder="{{ $cart_json_data['section']['title']['placeholder'] }}"
                                                                                            id="{{ $cart_json_data['section']['title']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($cart_json_data['section']['description']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $cart_json_data['section']['description']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['description']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['description']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['description']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['description']['placeholder'] ?? '' }}">
                                                                                        <textarea type="text" name="array[section][description][text]" class="form-control"
                                                                                            placeholder="{{ $cart_json_data['section']['description']['placeholder'] }}"
                                                                                            id="{{ $cart_json_data['section']['description']['slug'] }}"> {{ $cart_json_data['section']['description']['text'] }} </textarea>
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($cart_json_data['section']['button']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $cart_json_data['section']['button']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['button']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['button']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['button']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['button']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][button][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['button']['text'] ?? '' }}"
                                                                                            placeholder="{{ $cart_json_data['section']['button']['placeholder'] }}"
                                                                                            id="{{ $cart_json_data['section']['button']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($cart_json_data['section']['image']))
                                                                                <div class="col-sm-2">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $cart_json_data['section']['image']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['image']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['image']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['image']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['image']->placeholder ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][image]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['image']['image'] ?? '' }}">
                                                                                        <input type="file"
                                                                                            name="array[section][image][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $cart_json_data['section']['image']['text'] ?? '' }}"
                                                                                            placeholder="{{ $cart_json_data['section']['image']['placeholder'] }}"
                                                                                            id="{{ $cart_json_data['section']['image']['slug'] }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="logo-content mt-0">
                                                                                        @if (isset($cart_json_data['section']['image']['image']))
                                                                                        <a href="#" target="_blank">
                                                                                            <img src="{{ get_file($cart_json_data['section']['image']['image'] ?? '#', APP_THEME()) }}"
                                                                                                class="big-logo invoice_logo img_setting"
                                                                                                id="productImage">
                                                                                        </a>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endfor

                                                                    {{-- @permission('Edit Store Setting') --}}
                                                                    <div class="text-end">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">{{__('save')}}</button>
                                                                    </div>
                                                                    {{-- @endpermission --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {!! Form::close() !!}
                                            @endif

                                            @if (isset($checkout_json_data['section_name']))
                                                {{ Form::open(['route' => ['product.page.setting'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                                <input type="hidden" name="app_setting_tab" value="pills-page-tab">
                                                    <input type="hidden" name="section_name"
                                                        value="{{ $checkout_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[section_name]"
                                                        value="{{ $checkout_json_data['section_name'] }}">
                                                    <input type="hidden" name="array[section_slug]"
                                                        value="{{ $checkout_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[array_type]"
                                                        value="{{ $checkout_json_data['array_type'] }}">
                                                    <input type="hidden" name="array[loop_number]"
                                                        value="{{ $checkout_json_data['loop_number'] ?? 1 }}">

                                                    <div class="accordion-item card">
                                                        <h5 class="accordion-header" id="checkout_page_setting">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapseone_checkout_page_setting"
                                                                aria-expanded="false"
                                                                aria-controls="collapseone_checkout_page_setting">

                                                                <span class="d-flex align-items-center">
                                                                <b> {{ $checkout_json_data['section_name'] }}</b>
                                                                </span>
                                                            </button>
                                                        </h5>
                                                        <div id="collapseone_checkout_page_setting" class="accordion-collapse collapse" aria-labelledby="checkout_page_setting" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body p-4">
                                                                <div class="card-body">
                                                                    @for ($i = 0; $i < $checkout_json_data['loop_number']; $i++)
                                                                        <div class="row">
                                                                            @if (isset($checkout_json_data['section']['title']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $checkout_json_data['section']['title']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['title']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['title']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['title']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['title']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][title][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['title']['text'] ?? '' }}"
                                                                                            placeholder="{{ $checkout_json_data['section']['title']['placeholder'] }}"
                                                                                            id="{{ $checkout_json_data['section']['title']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($checkout_json_data['section']['description']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $checkout_json_data['section']['description']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['description']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['description']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['description']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['description']['placeholder'] ?? '' }}">
                                                                                        <textarea type="text" name="array[section][description][text]" class="form-control"
                                                                                            placeholder="{{ $checkout_json_data['section']['description']['placeholder'] }}"
                                                                                            id="{{ $checkout_json_data['section']['description']['slug'] }}"> {{ $checkout_json_data['section']['description']['text'] }} </textarea>
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($checkout_json_data['section']['button']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $checkout_json_data['section']['button']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['button']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['button']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['button']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['button']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][button][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['button']['text'] ?? '' }}"
                                                                                            placeholder="{{ $checkout_json_data['section']['button']['placeholder'] }}"
                                                                                            id="{{ $checkout_json_data['section']['button']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($checkout_json_data['section']['image']))
                                                                                <div class="col-sm-2">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $checkout_json_data['section']['image']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['image']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['image']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['image']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['image']->placeholder ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][image]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['image']['image'] ?? '' }}">
                                                                                        <input type="file"
                                                                                            name="array[section][image][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $checkout_json_data['section']['image']['text'] ?? '' }}"
                                                                                            placeholder="{{ $checkout_json_data['section']['image']['placeholder'] }}"
                                                                                            id="{{ $checkout_json_data['section']['image']['slug'] }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="logo-content mt-0">
                                                                                        @if (isset($checkout_json_data['section']['image']['image']))
                                                                                        <a href="#" target="_blank">
                                                                                            <img src="{{ get_file($checkout_json_data['section']['image']['image'] ?? '#', APP_THEME()) }}"
                                                                                                class="big-logo invoice_logo img_setting"
                                                                                                id="productImage">
                                                                                        </a>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endfor

                                                                    {{-- @permission('Edit Store Setting') --}}
                                                                    <div class="text-end">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">{{__('save')}}</button>
                                                                    </div>
                                                                    {{-- @endpermission --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {!! Form::close() !!}
                                            @endif

                                            @if (isset($contact_json_data['section_name']))
                                                {{ Form::open(['route' => ['product.page.setting'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                                <input type="hidden" name="app_setting_tab" value="pills-page-tab">
                                                    <input type="hidden" name="section_name"
                                                        value="{{ $contact_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[section_name]"
                                                        value="{{ $contact_json_data['section_name'] }}">
                                                    <input type="hidden" name="array[section_slug]"
                                                        value="{{ $contact_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[array_type]"
                                                        value="{{ $contact_json_data['array_type'] }}">
                                                    <input type="hidden" name="array[loop_number]"
                                                        value="{{ $contact_json_data['loop_number'] ?? 1 }}">

                                                    <div class="accordion-item card">
                                                        <h5 class="accordion-header" id="contact_page_setting">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapseone_contact_page_setting"
                                                                aria-expanded="false"
                                                                aria-controls="collapseone_contact_page_setting">

                                                                <span class="d-flex align-items-center">
                                                                <b> {{ $contact_json_data['section_name'] }}</b>
                                                                </span>
                                                            </button>
                                                        </h5>
                                                        <div id="collapseone_contact_page_setting" class="accordion-collapse collapse" aria-labelledby="contact_page_setting" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body p-4">
                                                                <div class="card-body">
                                                                    @for ($i = 0; $i < $contact_json_data['loop_number']; $i++)
                                                                        <div class="row">
                                                                            @if (isset($contact_json_data['section']['title']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $contact_json_data['section']['title']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['title']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['title']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['title']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['title']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][title][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['title']['text'] ?? '' }}"
                                                                                            placeholder="{{ $contact_json_data['section']['title']['placeholder'] }}"
                                                                                            id="{{ $contact_json_data['section']['title']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($contact_json_data['section']['description']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $contact_json_data['section']['description']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['description']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['description']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['description']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['description']['placeholder'] ?? '' }}">
                                                                                        <textarea type="text" name="array[section][description][text]" class="form-control"
                                                                                            placeholder="{{ $contact_json_data['section']['description']['placeholder'] }}"
                                                                                            id="{{ $contact_json_data['section']['description']['slug'] }}"> {{ $contact_json_data['section']['description']['text'] }} </textarea>
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($contact_json_data['section']['button']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $contact_json_data['section']['button']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['button']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['button']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['button']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['button']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][button][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['button']['text'] ?? '' }}"
                                                                                            placeholder="{{ $contact_json_data['section']['button']['placeholder'] }}"
                                                                                            id="{{ $contact_json_data['section']['button']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($contact_json_data['section']['image']))
                                                                                <div class="col-sm-2">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $contact_json_data['section']['image']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['image']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['image']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['image']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['image']->placeholder ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][image]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['image']['image'] ?? '' }}">
                                                                                        <input type="file"
                                                                                            name="array[section][image][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_json_data['section']['image']['text'] ?? '' }}"
                                                                                            placeholder="{{ $contact_json_data['section']['image']['placeholder'] }}"
                                                                                            id="{{ $contact_json_data['section']['image']['slug'] }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="logo-content mt-0">
                                                                                        @if (isset($contact_json_data['section']['image']['image']))
                                                                                        <a href="#" target="_blank">
                                                                                            <img src="{{ get_file($contact_json_data['section']['image']['image'] ?? '#', APP_THEME()) }}"
                                                                                                class="big-logo invoice_logo img_setting"
                                                                                                id="productImage">
                                                                                        </a>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endfor

                                                                    {{-- @permission('Edit Store Setting') --}}
                                                                    <div class="text-end">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">{{__('save')}}</button>
                                                                    </div>
                                                                    {{-- @endpermission --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {!! Form::close() !!}
                                            @endif

                                            @if (isset($faq_json_data['section_name']))
                                                {{ Form::open(['route' => ['product.page.setting'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                                <input type="hidden" name="app_setting_tab" value="pills-page-tab">
                                                    <input type="hidden" name="section_name"
                                                        value="{{ $faq_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[section_name]"
                                                        value="{{ $faq_json_data['section_name'] }}">
                                                    <input type="hidden" name="array[section_slug]"
                                                        value="{{ $faq_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[array_type]"
                                                        value="{{ $faq_json_data['array_type'] }}">
                                                    <input type="hidden" name="array[loop_number]"
                                                        value="{{ $faq_json_data['loop_number'] ?? 1 }}">

                                                    <div class="accordion-item card">
                                                        <h5 class="accordion-header" id="faq_page_setting">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapseone_faq_page_setting"
                                                                aria-expanded="false"
                                                                aria-controls="collapseone_faq_page_setting">

                                                                <span class="d-flex align-items-center">
                                                                <b> {{ $faq_json_data['section_name'] }}</b>
                                                                </span>
                                                            </button>
                                                        </h5>
                                                        <div id="collapseone_faq_page_setting" class="accordion-collapse collapse" aria-labelledby="faq_page_setting" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body p-4">
                                                                <div class="card-body">
                                                                    @for ($i = 0; $i < $faq_json_data['loop_number']; $i++)
                                                                        <div class="row">
                                                                            @if (isset($faq_json_data['section']['title']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $faq_json_data['section']['title']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['title']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['title']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['title']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['title']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][title][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['title']['text'] ?? '' }}"
                                                                                            placeholder="{{ $faq_json_data['section']['title']['placeholder'] }}"
                                                                                            id="{{ $faq_json_data['section']['title']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($faq_json_data['section']['description']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $faq_json_data['section']['description']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['description']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['description']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['description']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['description']['placeholder'] ?? '' }}">
                                                                                        <textarea type="text" name="array[section][description][text]" class="form-control"
                                                                                            placeholder="{{ $faq_json_data['section']['description']['placeholder'] }}"
                                                                                            id="{{ $faq_json_data['section']['description']['slug'] }}"> {{ $faq_json_data['section']['description']['text'] }} </textarea>
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($faq_json_data['section']['button']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $faq_json_data['section']['button']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['button']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['button']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['button']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['button']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][button][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['button']['text'] ?? '' }}"
                                                                                            placeholder="{{ $faq_json_data['section']['button']['placeholder'] }}"
                                                                                            id="{{ $faq_json_data['section']['button']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($faq_json_data['section']['image']))
                                                                                <div class="col-sm-2">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $faq_json_data['section']['image']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['image']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['image']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['image']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['image']->placeholder ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][image]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['image']['image'] ?? '' }}">
                                                                                        <input type="file"
                                                                                            name="array[section][image][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $faq_json_data['section']['image']['text'] ?? '' }}"
                                                                                            placeholder="{{ $faq_json_data['section']['image']['placeholder'] }}"
                                                                                            id="{{ $faq_json_data['section']['image']['slug'] }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="logo-content mt-0">
                                                                                        @if (isset($faq_json_data['section']['image']['image']))
                                                                                        <a href="#" target="_blank">
                                                                                            <img src="{{ get_file($faq_json_data['section']['image']['image'] ?? '#', APP_THEME()) }}"
                                                                                                class="big-logo invoice_logo img_setting"
                                                                                                id="productImage">
                                                                                        </a>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endfor

                                                                    {{-- @permission('Edit Store Setting') --}}
                                                                    <div class="text-end">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">{{__('save')}}</button>
                                                                    </div>
                                                                    {{-- @endpermission --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {!! Form::close() !!}
                                            @endif

                                            @if (isset($collection_json_data['section_name']))
                                                {{ Form::open(['route' => ['product.page.setting'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                                <input type="hidden" name="app_setting_tab" value="pills-page-tab">
                                                    <input type="hidden" name="section_name"
                                                        value="{{ $collection_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[section_name]"
                                                        value="{{ $collection_json_data['section_name'] }}">
                                                    <input type="hidden" name="array[section_slug]"
                                                        value="{{ $collection_json_data['section_slug'] }}">
                                                    <input type="hidden" name="array[array_type]"
                                                        value="{{ $collection_json_data['array_type'] }}">
                                                    <input type="hidden" name="array[loop_number]"
                                                        value="{{ $collection_json_data['loop_number'] ?? 1 }}">

                                                    <div class="accordion-item card">
                                                        <h5 class="accordion-header" id="collection_page_setting">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapseone_collection_page_setting"
                                                                aria-expanded="false"
                                                                aria-controls="collapseone_collection_page_setting">

                                                                <span class="d-flex align-items-center">
                                                                <b> {{ $collection_json_data['section_name'] }}</b>
                                                                </span>
                                                            </button>
                                                        </h5>
                                                        <div id="collapseone_collection_page_setting" class="accordion-collapse collapse" aria-labelledby="collection_page_setting" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body p-4">
                                                                <div class="card-body">
                                                                    @for ($i = 0; $i < $collection_json_data['loop_number']; $i++)
                                                                        <div class="row">
                                                                            @if (isset($collection_json_data['section']['title']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $collection_json_data['section']['title']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['title']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['title']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['title']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][title][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['title']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][title][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['title']['text'] ?? '' }}"
                                                                                            placeholder="{{ $collection_json_data['section']['title']['placeholder'] }}"
                                                                                            id="{{ $collection_json_data['section']['title']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($collection_json_data['section']['description']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $collection_json_data['section']['description']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['description']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['description']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['description']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][description][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['description']['placeholder'] ?? '' }}">
                                                                                        <textarea type="text" name="array[section][description][text]" class="form-control"
                                                                                            placeholder="{{ $collection_json_data['section']['description']['placeholder'] }}"
                                                                                            id="{{ $collection_json_data['section']['description']['slug'] }}"> {{ $collection_json_data['section']['description']['text'] }} </textarea>
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($collection_json_data['section']['button']))
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $collection_json_data['section']['button']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['button']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['button']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['button']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][button][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['button']->placeholder ?? '' }}">
                                                                                        <input type="text"
                                                                                            name="array[section][button][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['button']['text'] ?? '' }}"
                                                                                            placeholder="{{ $collection_json_data['section']['button']['placeholder'] }}"
                                                                                            id="{{ $collection_json_data['section']['button']['slug'] }}">

                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            @if (isset($collection_json_data['section']['image']))
                                                                                <div class="col-sm-2">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">{{ $collection_json_data['section']['image']['lable'] }}</label>
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][slug]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['image']['slug'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][lable]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['image']['lable'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][type]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['image']['type'] ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][placeholder]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['image']->placeholder ?? '' }}">
                                                                                        <input type="hidden"
                                                                                            name="array[section][image][image]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['image']['image'] ?? '' }}">
                                                                                        <input type="file"
                                                                                            name="array[section][image][text]"
                                                                                            class="form-control"
                                                                                            value="{{ $collection_json_data['section']['image']['text'] ?? '' }}"
                                                                                            placeholder="{{ $collection_json_data['section']['image']['placeholder'] }}"
                                                                                            id="{{ $collection_json_data['section']['image']['slug'] }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="logo-content mt-0">
                                                                                        <a href="#" target="_blank">
                                                                                            @if (isset($collection_json_data['section']['image']['image']))
                                                                                            <img src="{{ get_file($collection_json_data['section']['image']['image'] ?? '#', APP_THEME()) }}"
                                                                                                class="big-logo invoice_logo img_setting"
                                                                                                id="productImage">
                                                                                            @endif
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endfor

                                                                    {{-- @permission('Edit Store Setting') --}}
                                                                    <div class="text-end">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">{{__('save')}}</button>
                                                                    </div>
                                                                    {{-- @endpermission --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {!! Form::close() !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @stack('appSettingTabForm')
            </div>
        </div>

    </div>
    </div>
    </div>

@endsection

@push('custom-script')
    <script>
        function myFunction() {
            var copyText = document.getElementById("myInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            show_toastr('Success', "{{ __('Link copied') }}", 'success');
        }

        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        });
    </script>


    <script>
        $(function() {
            $('body').on('click', '.image_delete', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                var data = {
                    'image': id
                };
                // now make the ajax request
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "#",
                    data: data,
                    context: this,
                    success: function(data) {
                        $(this).closest('.product_Image').remove();
                        $('#loader').fadeOut();
                        $('#Main_Page_Content_web_post').find('.submit_form').click();
                    }
                });
            });
        });
    </script>
@endpush
