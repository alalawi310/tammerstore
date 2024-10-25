<svg style="display: none;">
    <symbol viewBox="0 0 6 5" id="slickarrow">
        <path fill-rule="evenodd" clip-rule="evenodd"
            d="M5.89017 2.75254C6.03661 2.61307 6.03661 2.38693 5.89017 2.24746L3.64017 0.104605C3.49372 -0.0348681 3.25628 -0.0348681 3.10984 0.104605C2.96339 0.244078 2.96339 0.470208 3.10984 0.609681L5.09467 2.5L3.10984 4.39032C2.96339 4.52979 2.96339 4.75592 3.10984 4.8954C3.25628 5.03487 3.49372 5.03487 3.64016 4.8954L5.89017 2.75254ZM0.640165 4.8954L2.89017 2.75254C3.03661 2.61307 3.03661 2.38693 2.89017 2.24746L0.640165 0.104605C0.493719 -0.0348682 0.256282 -0.0348682 0.109835 0.104605C-0.0366115 0.244078 -0.0366115 0.470208 0.109835 0.609681L2.09467 2.5L0.109835 4.39032C-0.0366117 4.52979 -0.0366117 4.75592 0.109835 4.8954C0.256282 5.03487 0.493719 5.03487 0.640165 4.8954Z">
        </path>
    </symbol>
</svg>
<!--header start here-->

@if (\Route::current()->getName() != 'landing_page')
    <header class="site-header header-style-one header-style-two home-header"
        style="@if (isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif"
        data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}"
        data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"
        data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    @else
        <header class="site-header header-style-one home-header"
            style="@if (isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif"
            data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}"
            data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}"
            data-section="{{ $option->section_name ?? '' }}" data-store="{{ $option->store_id ?? '' }}"
            data-theme="{{ $option->theme_id ?? '' }}">
@endif
<div class="custome_tool_bar"></div>
<div class="announce-bar">
    <div class="container">
        <div class="announcebar-inner">
            <div class="row align-items-center">
                <div class="col-md-6 col-12">
                    <p>
                        <img src="{{ asset('themes/' . $currentTheme . '/assets/img/header.svg') }}" alt=""
                            class="svg">
                        <span
                            id="{{ $section->header->section->title->slug ?? '' }}_preview">{!! $section->header->section->title->text ?? '' !!}</span>
                    </p>
                </div>
                <div class="col-md-6 col-12">
                    <ul class="d-flex align-items-center justify-content-end">
                        <li>
                            <a href="tel:1234567890">
                                <img src="{{ asset('themes/' . $currentTheme . '/assets/img/contact.svg') }}"
                                    alt="" class="svg">
                                <span
                                    id="{{ $section->header->section->support_title->slug ?? '' }}_preview">{!! $section->header->section->support_title->text !!}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main-navigationbar">
    <div class="container">
        <div class="navigationbar-row d-flex align-items-center">
            <div class="logo-col">
                <h1>
                    <a href="{{ route('landing_page', $slug) }}">
                        <img src="{{ get_file(isset($theme_logo) && !empty($theme_logo) ? $theme_logo : 'themes/' . $currentTheme . '/assets/images/logo.png', $currentTheme) }}"
                            alt="Logo">
                    </a>
                </h1>
            </div>
            <div class="menu-items-col">
                <ul class="main-nav">
                    @if (!empty($topNavItems))
                        @foreach ($topNavItems as $key => $nav)
                            @if (!empty($nav->children[0]))
                                <li class="menu-lnk has-item">
                                    <a href="#">
                                        @if ($nav->title == null)
                                            {{ $nav->title }}
                                        @else
                                            {{ $nav->title }}
                                        @endif
                                    </a>
                                    <div class="menu-dropdown">
                                        <ul>
                                            @foreach ($nav->children[0] as $childNav)
                                                @if ($childNav->type == 'custom')
                                                    <li><a href="{{ url($childNav->slug) }}"
                                                            target="{{ $childNav->target }}">
                                                            @if ($childNav->title == null)
                                                                {{ $childNav->title }}
                                                            @else
                                                                {{ $childNav->title }}
                                                            @endif
                                                        </a></li>
                                                @elseif($childNav->type == 'category')
                                                    <li><a href="{{ url($slug . '/' . $childNav->slug) }}"
                                                            target="{{ $childNav->target }}">
                                                            @if ($childNav->title == null)
                                                                {{ $childNav->title }}
                                                            @else
                                                                {{ $childNav->title }}
                                                            @endif
                                                        </a></li>
                                                @elseif($childNav->type == 'brand')
                                                    <li><a href="{{ url($slug.'/'.$childNav->slug) }}" target="{{ $childNav->target }}">
                                                        @if ($childNav->title == null)
                                                            {{ $childNav->title }}
                                                        @else
                                                            {{ $childNav->title }}
                                                        @endif
                                                    </a></li>
                                                @else
                                                    <li><a href="{{ route('themes.page', [$currentTheme, $childNav->slug]) }}"
                                                            target="{{ $childNav->target }}">
                                                            @if ($childNav->title == null)
                                                                {{ $childNav->title }}
                                                            @else
                                                                {{ $childNav->title }}
                                                            @endif
                                                        </a></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @else
                                @if ($nav->type == 'custom')
                                    <li class="">
                                        <a href="{{ url($nav->slug) }}" target="{{ $nav->target }}">
                                            @if ($nav->title == null)
                                                {{ $nav->title }}
                                            @else
                                                {{ $nav->title }}
                                            @endif
                                        </a>
                                    </li>
                                @elseif($nav->type == 'category')
                                    <li class="">
                                        <a href="{{ url($slug . '/' . $nav->slug) }}" target="{{ $nav->target }}"
                                            target="{{ $nav->target }}">
                                            @if ($nav->title == null)
                                                {{ $nav->title }}
                                            @else
                                                {{ $nav->title }}
                                            @endif
                                        </a>
                                    </li>
                                @else
                                    <li class="">
                                        <a href="{{ url($slug . '/custom/' . $nav->slug) }}" target="{{ $nav->target }}">
                                            @if ($nav->title == null)
                                                {{ $nav->title }}
                                            @else
                                                {{ $nav->title }}
                                            @endif
                                        </a>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    @endif
                    <li class="menu-lnk has-item">
                        <a href="#">
                            {{ __('Pages') }}
                        </a>
                        <div class="menu-dropdown">
                            <ul>
                                @if(isset($pages))
                                    @foreach ($pages as $page)
                                    <li><a
                                            href="{{ route('custom.page',[$slug, $page->page_slug]) }}">{{ ucFirst($page->name) }}</a>
                                    </li>
                                    @endforeach
                                @endif
                                <li><a href="{{route('page.faq',['storeSlug' => $slug])}}">{{__('FAQs')}}</a></li>
                                <li><a href="{{route('page.blog',['storeSlug' => $slug])}}">{{__('Blog')}}</a></li>
                                <li><a href="{{route('page.product-list',['storeSlug' => $slug])}}">{{__('Collection')}}</a></li>
                                <li><a href="{{route('page.contact_us',['storeSlug' => $slug])}}">{{__('Contact')}}</a></li>
                            </ul>
                        </div>
                    </li>
                    @include('front_end.hooks.header_link')
                </ul>
                <ul class="menu-right d-flex">
                    <li class="search-header">
                        <a href="javascript:;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19"
                                fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M0.000169754 6.99457C0.000169754 10.8576 3.13174 13.9891 6.99473 13.9891C8.60967 13.9891 10.0968 13.4418 11.2807 12.5226C11.3253 12.6169 11.3866 12.7053 11.4646 12.7834L17.0603 18.379C17.4245 18.7432 18.015 18.7432 18.3792 18.379C18.7434 18.0148 18.7434 17.4243 18.3792 17.0601L12.7835 11.4645C12.7055 11.3864 12.6171 11.3251 12.5228 11.2805C13.442 10.0966 13.9893 8.60951 13.9893 6.99457C13.9893 3.13157 10.8577 0 6.99473 0C3.13174 0 0.000169754 3.13157 0.000169754 6.99457ZM1.86539 6.99457C1.86539 4.1617 4.16187 1.86522 6.99473 1.86522C9.8276 1.86522 12.1241 4.1617 12.1241 6.99457C12.1241 9.82743 9.8276 12.1239 6.99473 12.1239C4.16187 12.1239 1.86539 9.82743 1.86539 6.99457Z"
                                    fill="#183A40"></path>
                            </svg>
                        </a>
                    </li>
                    @auth('customers')
                        <li class="profile-header">
                            <a href="javascript:void(0)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="22" viewBox="0 0 16 22"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M13.3699 21.0448H4.60183C4.11758 21.0448 3.72502 20.6522 3.72502 20.168C3.72502 19.6837 4.11758 19.2912 4.60183 19.2912H13.3699C13.8542 19.2912 14.2468 18.8986 14.2468 18.4143V14.7756C14.2026 14.2836 13.9075 13.8492 13.4664 13.627C10.0296 12.2394 6.18853 12.2394 2.75176 13.627C2.31062 13.8492 2.01554 14.2836 1.9714 14.7756V20.168C1.9714 20.6522 1.57883 21.0448 1.09459 21.0448C0.610335 21.0448 0.217773 20.6522 0.217773 20.168V14.7756C0.256548 13.5653 0.986136 12.4845 2.09415 11.9961C5.95255 10.4369 10.2656 10.4369 14.124 11.9961C15.232 12.4845 15.9616 13.5653 16.0004 14.7756V18.4143C16.0004 19.8671 14.8227 21.0448 13.3699 21.0448ZM12.493 4.38406C12.493 1.96281 10.5302 0 8.10892 0C5.68767 0 3.72486 1.96281 3.72486 4.38406C3.72486 6.80531 5.68767 8.76812 8.10892 8.76812C10.5302 8.76812 12.493 6.80531 12.493 4.38406ZM10.7393 4.38483C10.7393 5.83758 9.56159 7.01526 8.10884 7.01526C6.6561 7.01526 5.47841 5.83758 5.47841 4.38483C5.47841 2.93208 6.6561 1.75439 8.10884 1.75439C9.56159 1.75439 10.7393 2.93208 10.7393 4.38483Z"
                                        fill="#183A40"></path>
                                </svg>
                            </a>
                            <div class="menu-dropdown">
                                <ul>
                                    <li><a href="{{ route('my-account.index', $slug) }}">{{ __('My Account') }}</a></li>
                                    <li>
                                        <form method="POST" action="{{ route('customer.logout', $store->slug) }}"
                                            id="form_logout">
                                            <a href="#"
                                                onclick="event.preventDefault(); this.closest('form').submit();"
                                                class="dropdown-item">
                                                @csrf
                                                {{ __('Log Out') }}
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @include('front_end.hooks.header_button')
                    @else
                        <li class="profile-header">
                            <a href="{{ route('customer.login', $store->slug) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="22"
                                    viewBox="0 0 16 22" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M13.3699 21.0448H4.60183C4.11758 21.0448 3.72502 20.6522 3.72502 20.168C3.72502 19.6837 4.11758 19.2912 4.60183 19.2912H13.3699C13.8542 19.2912 14.2468 18.8986 14.2468 18.4143V14.7756C14.2026 14.2836 13.9075 13.8492 13.4664 13.627C10.0296 12.2394 6.18853 12.2394 2.75176 13.627C2.31062 13.8492 2.01554 14.2836 1.9714 14.7756V20.168C1.9714 20.6522 1.57883 21.0448 1.09459 21.0448C0.610335 21.0448 0.217773 20.6522 0.217773 20.168V14.7756C0.256548 13.5653 0.986136 12.4845 2.09415 11.9961C5.95255 10.4369 10.2656 10.4369 14.124 11.9961C15.232 12.4845 15.9616 13.5653 16.0004 14.7756V18.4143C16.0004 19.8671 14.8227 21.0448 13.3699 21.0448ZM12.493 4.38406C12.493 1.96281 10.5302 0 8.10892 0C5.68767 0 3.72486 1.96281 3.72486 4.38406C3.72486 6.80531 5.68767 8.76812 8.10892 8.76812C10.5302 8.76812 12.493 6.80531 12.493 4.38406ZM10.7393 4.38483C10.7393 5.83758 9.56159 7.01526 8.10884 7.01526C6.6561 7.01526 5.47841 5.83758 5.47841 4.38483C5.47841 2.93208 6.6561 1.75439 8.10884 1.75439C9.56159 1.75439 10.7393 2.93208 10.7393 4.38483Z"
                                        fill="#183A40"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M13.3699 21.0448H4.60183C4.11758 21.0448 3.72502 20.6522 3.72502 20.168C3.72502 19.6837 4.11758 19.2912 4.60183 19.2912H13.3699C13.8542 19.2912 14.2468 18.8986 14.2468 18.4143V14.7756C14.2026 14.2836 13.9075 13.8492 13.4664 13.627C10.0296 12.2394 6.18853 12.2394 2.75176 13.627C2.31062 13.8492 2.01554 14.2836 1.9714 14.7756V20.168C1.9714 20.6522 1.57883 21.0448 1.09459 21.0448C0.610335 21.0448 0.217773 20.6522 0.217773 20.168V14.7756C0.256548 13.5653 0.986136 12.4845 2.09415 11.9961C5.95255 10.4369 10.2656 10.4369 14.124 11.9961C15.232 12.4845 15.9616 13.5653 16.0004 14.7756V18.4143C16.0004 19.8671 14.8227 21.0448 13.3699 21.0448ZM12.493 4.38406C12.493 1.96281 10.5302 0 8.10892 0C5.68767 0 3.72486 1.96281 3.72486 4.38406C3.72486 6.80531 5.68767 8.76812 8.10892 8.76812C10.5302 8.76812 12.493 6.80531 12.493 4.38406ZM10.7393 4.38483C10.7393 5.83758 9.56159 7.01526 8.10884 7.01526C6.6561 7.01526 5.47841 5.83758 5.47841 4.38483C5.47841 2.93208 6.6561 1.75439 8.10884 1.75439C9.56159 1.75439 10.7393 2.93208 10.7393 4.38483Z"
                                        fill="#183A40"></path>
                                </svg>
                            </a>
                        </li>
                    @endauth


                    <li class="menu-lnk has-item lang-dropdown">
                        <a href="#">
                            <span class="drp-text">{{ Str::upper($currantLang) }}</span>
                            <div class="lang-icn">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg"
                                    version="1.1" id="svg2223" xml:space="preserve" width="682.66669"
                                    height="682.66669" viewBox="0 0 682.66669 682.66669">
                                    <g id="g2229" transform="matrix(1.3333333,0,0,-1.3333333,0,682.66667)">
                                        <g id="g2231">
                                            <g id="g2233" clip-path="url(#clipPath2237)">
                                                <g id="g2239" transform="translate(497,256)">
                                                    <path
                                                        d="m 0,0 c 0,-132.548 -108.452,-241 -241,-241 -132.548,0 -241,108.452 -241,241 0,132.548 108.452,241 241,241 C -108.452,241 0,132.548 0,0 Z"
                                                        style="fill:none;stroke:#ffffff;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        id="path2241" />
                                                </g>
                                                <g id="g2243" transform="translate(376,256)">
                                                    <path
                                                        d="m 0,0 c 0,-132.548 -53.726,-241 -120,-241 -66.274,0 -120,108.452 -120,241 0,132.548 53.726,241 120,241 C -53.726,241 0,132.548 0,0 Z"
                                                        style="fill:none;stroke:#ffffff;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        id="path2245" />
                                                </g>
                                                <g id="g2247" transform="translate(256,497)">
                                                    <path d="M 0,0 V -482"
                                                        style="fill:none;stroke:#ffffff;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        id="path2249" />
                                                </g>
                                                <g id="g2251" transform="translate(15,256)">
                                                    <path d="M 0,0 H 482"
                                                        style="fill:none;stroke:#ffffff;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        id="path2253" />
                                                </g>
                                                <g id="g2255" transform="translate(463.8926,136)">
                                                    <path d="M 0,0 H -415.785"
                                                        style="fill:none;stroke:#ffffff;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        id="path2257" />
                                                </g>
                                                <g id="g2259" transform="translate(48.1079,377)">
                                                    <path d="M 0,0 H 415.785"
                                                        style="fill:none;stroke:#ffffff;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        id="path2261" />
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                        </a>
                        <div class="menu-dropdown">
                            <ul>
                                @foreach ($languages as $code => $language)
                                    <li><a href="{{ route('change.languagestore', [$code]) }}"
                                            class="@if ($language == $currantLang) active-language text-primary @endif">{{ ucFirst($language) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li class="cart-header" style="pointer-events: auto">
                        <a href="javascript:;">
                            <span class="desk-only icon-lable ">Cart: <span
                                    class="p_count">{!! \App\Models\Cart::CartCount($slug) ?? 0 !!}</span> items</span>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="17"
                                    viewBox="0 0 19 17" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M15.5698 10.627H6.97178C5.80842 10.6273 4.81015 9.79822 4.59686 8.65459L3.47784 2.59252C3.40702 2.20522 3.06646 1.92595 2.67278 1.93238H0.805055C0.360435 1.93238 0 1.57194 0 1.12732C0 0.682701 0.360435 0.322266 0.805055 0.322266H2.68888C3.85224 0.321937 4.85051 1.15101 5.0638 2.29465L6.18282 8.35672C6.25364 8.74402 6.5942 9.02328 6.98788 9.01686H15.5778C15.9715 9.02328 16.3121 8.74402 16.3829 8.35672L17.3972 2.88234C17.4407 2.64509 17.3755 2.40085 17.2195 2.21684C17.0636 2.03283 16.8334 1.92843 16.5922 1.93238H7.2455C6.80088 1.93238 6.44044 1.57194 6.44044 1.12732C6.44044 0.682701 6.80088 0.322266 7.2455 0.322266H16.5841C17.3023 0.322063 17.9833 0.641494 18.4423 1.19385C18.9013 1.74622 19.0907 2.4742 18.959 3.18021L17.9447 8.65459C17.7314 9.79822 16.7331 10.6273 15.5698 10.627ZM10.466 13.8478C10.466 12.5139 9.38464 11.4326 8.05079 11.4326C7.60617 11.4326 7.24573 11.7931 7.24573 12.2377C7.24573 12.6823 7.60617 13.0427 8.05079 13.0427C8.49541 13.0427 8.85584 13.4032 8.85584 13.8478C8.85584 14.2924 8.49541 14.6528 8.05079 14.6528C7.60617 14.6528 7.24573 14.2924 7.24573 13.8478C7.24573 13.4032 6.88529 13.0427 6.44068 13.0427C5.99606 13.0427 5.63562 13.4032 5.63562 13.8478C5.63562 15.1816 6.71693 16.2629 8.05079 16.2629C9.38464 16.2629 10.466 15.1816 10.466 13.8478ZM15.2963 15.4579C15.2963 15.0133 14.9358 14.6528 14.4912 14.6528C14.0466 14.6528 13.6862 14.2924 13.6862 13.8478C13.6862 13.4032 14.0466 13.0427 14.4912 13.0427C14.9358 13.0427 15.2963 13.4032 15.2963 13.8478C15.2963 14.2924 15.6567 14.6528 16.1013 14.6528C16.5459 14.6528 16.9064 14.2924 16.9064 13.8478C16.9064 12.5139 15.8251 11.4326 14.4912 11.4326C13.1574 11.4326 12.076 12.5139 12.076 13.8478C12.076 15.1816 13.1574 16.2629 14.4912 16.2629C14.9358 16.2629 15.2963 15.9025 15.2963 15.4579Z"
                                        fill="white"></path>
                                </svg>
                                <span class="count">{!! \App\Models\Cart::CartCount($slug) ?? 0 !!}</span>
                            </div>
                        </a>
                    </li>

                </ul>
                <div class="mobile-menu mobile-only">
                    <button class="mobile-menu-button">
                        <div class="one"></div>
                        <div class="two"></div>
                        <div class="three"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</header>
<!--header end here-->
@push('page-script')
@endpush
