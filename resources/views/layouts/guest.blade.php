@php
    $setting = getSuperAdminAllSetting();

    $profile = asset(Storage::url('uploads/logo/'));
    if (isset($setting['logo_dark'])) {
        $company_logo = $setting['logo_dark'] ?? \App\Models\Utility::GetValueByName('logo_dark', APP_THEME());
    } else {
        $company_logo = \App\Models\Utility::GetValueByName('logo_dark', APP_THEME());
    }

    $company_logo = get_file($company_logo, APP_THEME());

    if (isset($setting['favicon'])) {
        $favicon = $setting['favicon'] ?? \App\Models\Utility::GetValueByName('favicon', APP_THEME());
    } else {
        $favicon = \App\Models\Utility::GetValueByName('favicon', APP_THEME());
    }

    $favicon = get_file($favicon, APP_THEME());

    $cust_darklayout = $setting['cust_darklayout'] ?? null;
    if ($cust_darklayout == '' || empty($cust_darklayout)) {
        $cust_darklayout = 'off';
    }
    $cust_theme_bg = $setting['cust_theme_bg'] ?? null;
    if ($cust_theme_bg == '' || empty($cust_theme_bg)) {
        $cust_theme_bg = 'on';
    }
    $SITE_RTL = $setting['SITE_RTL'] ?? null;
    if ($SITE_RTL == '' || empty($SITE_RTL)) {
        $SITE_RTL = 'off';
    }

    if (!isset($setting['color'])) {
        $color = 'theme-3';
    } elseif (isset($setting['color_flag']) && $setting['color_flag'] == 'true') {
        $color = 'custom-color';
    } else {
        if (
            !in_array($setting['color'], [
                'theme-1',
                'theme-2',
                'theme-3',
                'theme-4',
                'theme-5',
                'theme-6',
                'theme-7',
                'theme-8',
                'theme-9',
            ])
        ) {
            $color = 'custom-color' ?? 'theme-3';
        } else {
            $color = $setting['color'] ?? 'theme-3';
        }
    }

    $lang = Session::get('LANGUAGE');
    if (empty($lang)) {
        $lang = Cookie::get('LANGUAGE') ?? null;
    }

    if (empty($lang)) {
        $lang = $setting['defult_language'] ?? null;
    }

    if (empty($lang)) {
        $lang = app()->getLocale() ?? 'en';
    }

    if (empty($lang) && app()->getLocale() != $lang) {
        $lang = app()->getLocale();
    }
    if ($lang == 'ar' || $lang == 'he') {
        $SITE_RTL = 'on';
    } else {
        $SITE_RTL = 'off';
    }

    $displaylang = App\Models\Utility::languages();

    $theme_id = !empty($theme_id) ? $theme_id : APP_THEME();

    if (isset($setting['disable_lang'])) {
        $toDisable = explode(',', $setting['disable_lang']);
    }

    foreach ($displaylang as $key => $data) {
        if (isset($setting['disable_lang']) && str_contains($setting['disable_lang'], $key)) {
            unset($displaylang[$key]);
        }
    }

    $footer_text = $setting['footer_text'] ?? (env('APP_NAME') ?? 'Tammer store');
    $menusettings = \Modules\LandingPage\Entities\OwnerMenuSetting::where('created_by', 1)->first();

    if (isset($menusettings) && $menusettings->menus_id) {
        $topNavItems = \Modules\LandingPage\Entities\OwnerMenuSetting::get_ownernav_menu($menusettings->menus_id);
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ isset($SITE_RTL) && $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="tammerofficial.com" />
    <meta name="base-url" content="{{ URL::to('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="title" content="{{ isset($SuperadminData['metatitle']) ? $SuperadminData['metatitle'] : 'تامر' }}">
    <meta name="keywords" content="{{ isset($SuperadminData['metakeyword']) ? $SuperadminData['metakeyword'] : 'تامر في متجرك' }}">
    <meta name="description" content="{{ isset($SuperadminData['metadesc']) ? $SuperadminData['metadesc'] : 'انشئ متجرك الآن، وادفع لاحقا'}}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="{{ isset($SuperadminData['metatitle']) ? $SuperadminData['metatitle'] : 'تامر' }}">
    <meta property="og:description" content="{{ isset($SuperadminData['metadesc']) ? $SuperadminData['metadesc'] : 'انشئ متجرك الآن، وادفع لاحقا'}} ">
    <meta property="og:image" content="{{ get_file(isset($SuperadminData['metaimage']) ? $SuperadminData['metaimage'] : 'storage/uploads/ecommercego-saas-preview.png')  }}{{'?'.time() }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title" content="{{ isset($SuperadminData['metatitle']) ? $SuperadminData['metatitle'] : 'تامر' }}">
    <meta property="twitter:description" content="{{ isset($SuperadminData['metadesc']) ? $SuperadminData['metadesc'] : 'انشئ متجرك الآن، وادفع لاحقا'}} ">
    <meta property="twitter:image" content="{{ get_file(isset($SuperadminData['metaimage']) ? $SuperadminData['metaimage'] : 'storage/uploads/ecommercego-saas-preview.png')  }}{{'?'.time() }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}-@yield('page-title')</title>

    <!-- Favicon icon -->
    <link rel="icon"
        href="{{ !empty($favicon) ? $favicon . '?timestamp=' . time() : $profile . '/logo-sm.svg' . '?timestamp=' . time() }}"
        type="image/x-icon" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ url('Modules/LandingPage/Resources/assets/css/landing-page.css') }}" />
    <!-- vendor css -->
    @if ($cust_darklayout == 'on' && $SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('public/assets/css/style-dark.css') }}" id="main-style-link">
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
        <link rel="stylesheet" href="{{ asset('css/rtl-loader.css') }}{{ '?v=' . time() }}">
    @elseif($cust_darklayout == 'on')
        <link rel="stylesheet" href="{{ asset('public/assets/css/style-dark.css') }}" id="main-style-link">
        <link rel="stylesheet" href="{{ asset('css/loader.css') }}{{ '?v=' . time() }}">
    @elseif($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
        <link rel="stylesheet" href="{{ asset('css/rtl-loader.css') }}{{ '?v=' . time() }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
        <link rel="stylesheet" href="{{ asset('public/assets/css/style.css') }}" id="main-style-link">
        <link rel="stylesheet" href="{{ asset('css/loader.css') }}{{ '?v=' . time() }}">
    @endif

    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}{{ '?v=' . time() }}">
    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">

    <style>
        :root {
            --color-customColor: <?=$setting['color'] ?? 'linear-gradient(141.55deg, rgba(240, 244, 243, 0) 3.46%, #ffffff 99.86%)' ?>;
        }
    </style>
    <style>
        .main-header {
          position: relative;
        }
    </style>
</head>

<body class="{{ $color ?? theme - 3 }}    ">

    <div class="register-page auth-wrapper auth-v3">
        <div class="login-back-img">
            <img src="{{ asset('assets/images/auth/img-bg-1.svg') }}" alt="" class="img-fluid login-bg-1" />
            <img src="{{ asset('assets/images/auth/img-bg-2.svg') }}" alt="" class="img-fluid login-bg-2" />
            <img src="{{ asset('assets/images/auth/img-bg-3.svg') }}" alt="" class="img-fluid login-bg-3" />
            <img src="{{ asset('assets/images/auth/img-bg-4.svg') }}" alt="" class="img-fluid login-bg-4" />
        </div>
        <div class="bg-auth-side bg-primary login-page"></div>
        <div class="auth-content">
            <header class="main-header">
                <nav class="navbar navbar-expand-md navbar-light default">
                    <div class="container-fluid pe-2">
                        <a class="navbar-brand"
                            href="{{ isset($setting['display_landing']) && $setting['display_landing'] == 'on' ? route('start') : url('/') }}">
                            <img src="{{ isset($company_logo) && !empty($company_logo) ? $company_logo . '?timestamp=' . time() : $profile . '/logo-dark.svg' . '?timestamp=' . time() }}"
                                alt="logo" class="brand_icon" />
                        </a>

                        <div class="d-flex gap-3">
                            @if (isset($menusettings) &&
                                    isset($menusettings->menus_id) &&
                                    $menusettings->enable_header == 'on' &&
                                    !empty($topNavItems))
                                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                                    <ul
                                        class="lnding-menubar p-0 {{ isset($menusettings) && $menusettings->enable_dropdown == 'off' ? 'landing-custom-menu' : 'landing-dropdown-menu' }}">
                                        @foreach ($topNavItems as $navGroup)
                                            <li class="menu-lnk has-item">
                                                <a class="dash-head-link" href="#">
                                                    <span>
                                                        {{ $navGroup['name'] }}
                                                    </span>
                                                    <i class="ti ti-chevron-down drp-arrow"></i>
                                                </a>
                                                <div class="menu-dropdown">
                                                    <ul class="p-0 m-0">
                                                        @foreach ($navGroup['items'] as $nav)
                                                            @if ($nav->type == 'page')
                                                                <li class="lnk-itm">
                                                                    <a href="{{ url('landing/pages/' . $nav->slug) }}"
                                                                        target="{{ $nav->target }}"
                                                                        class="dropdown-item">
                                                                        <span>{{ $nav->title }}</span>
                                                                    </a>
                                                                    @if (!empty($nav->children) && isset($nav->children))
                                                                        <ul class="lnk-child">
                                                                            @foreach ($nav->children[0] as $child)
                                                                                @if (!empty($child))
                                                                                    <li>
                                                                                        @if ($child->type == 'page')
                                                                                            <a href="{{ url('landing/pages/' . $child->slug) }}"
                                                                                                target="{{ $child->target }}"
                                                                                                class="dropdown-item">
                                                                                                <span>{{ $child->title }}</span>
                                                                                            </a>
                                                                                        @else
                                                                                            <a href="{{ $child->slug }}"
                                                                                                target="{{ $child->target }}"
                                                                                                class="dropdown-item">
                                                                                                <span>{{ $child->title }}</span>
                                                                                            </a>
                                                                                        @endif
                                                                                    </li>
                                                                                @endif
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <a href="{{ $nav->slug }}"
                                                                        target="{{ $nav->target }}"
                                                                        class="dropdown-item">
                                                                        <span>{{ $nav->title }}</span>
                                                                    </a>
                                                                    @if (!empty($nav->children))
                                                                        <ul>
                                                                            @foreach ($nav->children[0] as $child)
                                                                                @if (!empty($child))
                                                                                    <li>
                                                                                        @if ($child->type == 'page')
                                                                                            <a href="{{ url('landing/pages/' . $child->slug) }}"
                                                                                                target="{{ $child->target }}"
                                                                                                class="dropdown-item">
                                                                                                <span>{{ $child->title }}</span>
                                                                                            </a>
                                                                                        @else
                                                                                            <a href="{{ $child->slug }}"
                                                                                                target="{{ $child->target }}"
                                                                                                class="dropdown-item">
                                                                                                <span>{{ $child->title }}</span>
                                                                                            </a>
                                                                                        @endif
                                                                                    </li>
                                                                                @endif
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <button class="navbar-toggler bg-primary" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01"
                                        aria-controls="navbarTogglerDemo01" aria-expanded="false"
                                        aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                </div>
                            @endif
                            <div class="dropdown dash-h-item drp-language ecom-lang-drp">
                                <a class="dash-head-link dropdown-toggle arrow-none me-0 bg-primary"
                                    data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                                    aria-expanded="false">
                                    <i class="ti ti-world nocolor"></i>
                                    <span class="drp-text">{{ strtoupper($lang) }}</span>
                                    <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                </a>

                                <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                    onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">

                                    @foreach ($displaylang as $key => $language)
                                        <a href="{{ route('changelanguage', $key) }}"
                                            class="dropdown-item {{ $lang == $key ? 'text-primary' : '' }}">
                                            <span>{{ Str::ucfirst($language) }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <button class="navbar-toggler " type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                        </div>
                    </div>
                </nav>
            </header>
            <div class="card">
                <div class="row align-items-center justify-content-center text-start">
                    <div class="col-xl-12">
                        <div class="card-body mx-auto my-4 new-login-design">
                            @yield('content')
                        </div>
                    </div>

                </div>
            </div>
            <div class="auth-footer">
                <div class="container-fluid text-center">
                    <div class="row">
                        <div class="col-12">
                            <p class="text-black">
                                @if (isset($setting['footer_text']) &&
                                        (strpos($setting['footer_text'], '©') === false && strpos($setting['footer_text'], '&copy;') === false))
                                    &copy;
                                @endif

                                {{ date('Y') }}
                                {{ isset($setting['footer_text']) ? $setting['footer_text'] : config('app.name', 'E-CommerceGo') }}
                            </p>
                        </div>
                        <div class="col-6 text-end">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loader" class="loader-wrapper" style="display: none;">
        <span class="site-loader"> </span>
        <h3 class="loader-content"> {{ __('Loading . . .') }} </h3>
    </div>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor-all.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('js/loader.js') }}"></script>
    @stack('scripts')
    @php
        $setting = getSuperAdminAllSetting();
    @endphp
    @if (isset($setting['enable_cookie']) && $setting['enable_cookie'] == 'on')
        @include('layouts.cookie_consent')
    @endif
    <script>
        $(document).ready(function() {
            var mediaQuery = window.matchMedia("(max-width: 991px)");

            function handleResponsiveDropdown(mediaQuery) {
                if (mediaQuery.matches) {
                    $('.dash-head-link').off('click').on('click', function(e) {
                        var $currentDropdown = $(this).next('.menu-dropdown');

                        if ($currentDropdown.is(':visible')) {
                            $currentDropdown.slideUp();
                        } else {
                            $('.menu-dropdown').slideUp();
                            $currentDropdown.slideDown();
                        }
                    });
                }
            }

            handleResponsiveDropdown(mediaQuery);

            mediaQuery.addListener(handleResponsiveDropdown);
        });
    </script>
</body>

</html>
