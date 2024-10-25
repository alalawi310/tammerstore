<!--Mobile Menu-->
<div class="mobile-menu-wrapper">
    <div class="menu-close-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="35" height="34" viewBox="0 0 35 34" fill="none">
            <line x1="2.29695" y1="1.29289" x2="34.1168" y2="33.1127" stroke="white" stroke-width="2" />
            <line x1="0.882737" y1="33.1122" x2="32.7025" y2="1.29242" stroke="white" stroke-width="2" />
        </svg>
    </div>
    <div class="mobile-menu-bar">
        <ul class="">
            @if (!empty($topNavItems))
                @foreach ($topNavItems as $key => $nav)
                    @if (!empty($nav->children[0]))
                        <li class="mobile-item has-children">
                            <a href="#"  class="acnav-label">
                                @if ($nav->title == null)
                                    {{ $nav->title }}
                                @else
                                    {{ $nav->title }}
                                @endif
                                <svg class="menu-open-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="11"
                                    viewBox="0 0 20 11">
                                    <path fill="#24272a"
                                        d="M.268 1.076C.373.918.478.813.584.76l.21.474c.79.684 2.527 2.158 5.21 4.368 2.738 2.21 4.159 3.316 4.264 3.316.474-.053 1.158-.369 1.947-1.053.842-.631 1.632-1.42 2.474-2.368.895-.948 1.737-1.842 2.632-2.58.842-.789 1.578-1.262 2.105-1.42l.316.684c0 .21-.106.474-.316.737-.053.21-.263.421-.474.579-.053.052-.316.21-.737.474l-.526.368c-.421.263-1.105.947-2.158 2l-1.105 1.053-2.053 1.947c-1 .947-1.579 1.421-1.842 1.421-.263 0-.684-.263-1.158-.895-.526-.631-.842-1-1.052-1.105l-.737-.579c-.316-.316-.527-.474-.632-.474l-5.42-4.315L.267 2.339l-.105-.421-.053-.369c0-.157.053-.315.158-.473z">
                                    </path>
                                </svg>
                                <svg class="close-menu-ioc" xmlns="http://www.w3.org/2000/svg" width="20" height="18"
                                    viewBox="0 0 20 18">
                                    <path fill="#24272a"
                                        d="M19.95 16.75l-.05-.4-1.2-1-5.2-4.2c-.1-.05-.3-.2-.6-.5l-.7-.55c-.15-.1-.5-.45-1-1.1l-.1-.1c.2-.15.4-.35.6-.55l1.95-1.85 1.1-1c1-1 1.7-1.65 2.1-1.9l.5-.35c.4-.25.65-.45.75-.45.2-.15.45-.35.65-.6s.3-.5.3-.7l-.3-.65c-.55.2-1.2.65-2.05 1.35-.85.75-1.65 1.55-2.5 2.5-.8.9-1.6 1.65-2.4 2.3-.8.65-1.4.95-1.9 1-.15 0-1.5-1.05-4.1-3.2C3.1 2.6 1.45 1.2.7.55L.45.1c-.1.05-.2.15-.3.3C.05.55 0 .7 0 .85l.05.35.05.4 1.2 1 5.2 4.15c.1.05.3.2.6.5l.7.6c.15.1.5.45 1 1.1l.1.1c-.2.15-.4.35-.6.55l-1.95 1.85-1.1 1c-1 1-1.7 1.65-2.1 1.9l-.5.35c-.4.25-.65.45-.75.45-.25.15-.45.35-.65.6-.15.3-.25.55-.25.75l.3.65c.55-.2 1.2-.65 2.05-1.35.85-.75 1.65-1.55 2.5-2.5.8-.9 1.6-1.65 2.4-2.3.8-.65 1.4-.95 1.9-1 .15 0 1.5 1.05 4.1 3.2 2.6 2.15 4.3 3.55 5.05 4.2l.2.45c.1-.05.2-.15.3-.3.1-.15.15-.3.15-.45z">
                                    </path>
                                </svg>
                            </a>
                            <ul class="mobile_menu_inner acnav-list">
                                @foreach ($nav->children[0] as $childNav)
                                    @if ($childNav->type == 'custom')
                                        <li class="menu-h-link"><a href="{{ url($childNav->slug) }}" target="{{ $childNav->target }}">
                                            @if ($childNav->title == null)
                                                {{ $childNav->title }}
                                            @else
                                                {{ $childNav->title }}
                                            @endif
                                        </a></li>
                                    @elseif($childNav->type == 'category')
                                        <li class="menu-h-link"><a href="{{ url($slug.'/'.$childNav->slug) }}" target="{{ $childNav->target }}">
                                            @if ($childNav->title == null)
                                                {{ $childNav->title }}
                                            @else
                                                {{ $childNav->title }}
                                            @endif
                                        </a></li>
                                    @else
                                        <li class="menu-h-link"><a href="{{ url($slug.'/'.$childNav->slug) }}" target="{{ $childNav->target }}">
                                            @if ($childNav->title == null)
                                                {{ $childNav->title }}
                                            @else
                                                {{ $childNav->title }}
                                            @endif
                                        </a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @else
                        @if ($nav->type == 'custom')
                            <li class="mobile-item">
                                <a href="{{ url($nav->slug) }}" target="{{ $nav->target }}">
                                    @if ($nav->title == null)
                                        {{ $nav->title }}
                                    @else
                                        {{ $nav->title }}
                                    @endif
                                </a>
                            </li>
                        @elseif($nav->type == 'category')
                            <li class="mobile-item">
                                <a href="{{  url($slug.'/'.$nav->slug) }}" target="{{ $nav->target }}" target="{{ $nav->target }}">
                                    @if ($nav->title == null)
                                        {{ $nav->title }}
                                    @else
                                        {{ $nav->title }}
                                    @endif
                                </a>
                            </li>
                        @else
                            <li class="mobile-item">
                                <a href="{{  url($slug.'/custom/'.$nav->slug) }}"
                                    target="{{ $nav->target }}">
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

            <li class="mobile-item has-children">
                <a href="javascript:void(0)" class="acnav-label">
                    {{ __('Pages')}}
                    <svg class="menu-open-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="11"
                        viewBox="0 0 20 11">
                        <path fill="#24272a"
                            d="M.268 1.076C.373.918.478.813.584.76l.21.474c.79.684 2.527 2.158 5.21 4.368 2.738 2.21 4.159 3.316 4.264 3.316.474-.053 1.158-.369 1.947-1.053.842-.631 1.632-1.42 2.474-2.368.895-.948 1.737-1.842 2.632-2.58.842-.789 1.578-1.262 2.105-1.42l.316.684c0 .21-.106.474-.316.737-.053.21-.263.421-.474.579-.053.052-.316.21-.737.474l-.526.368c-.421.263-1.105.947-2.158 2l-1.105 1.053-2.053 1.947c-1 .947-1.579 1.421-1.842 1.421-.263 0-.684-.263-1.158-.895-.526-.631-.842-1-1.052-1.105l-.737-.579c-.316-.316-.527-.474-.632-.474l-5.42-4.315L.267 2.339l-.105-.421-.053-.369c0-.157.053-.315.158-.473z">
                        </path>
                    </svg>
                    <svg class="close-menu-ioc" xmlns="http://www.w3.org/2000/svg" width="20" height="18"
                        viewBox="0 0 20 18">
                        <path fill="#24272a"
                            d="M19.95 16.75l-.05-.4-1.2-1-5.2-4.2c-.1-.05-.3-.2-.6-.5l-.7-.55c-.15-.1-.5-.45-1-1.1l-.1-.1c.2-.15.4-.35.6-.55l1.95-1.85 1.1-1c1-1 1.7-1.65 2.1-1.9l.5-.35c.4-.25.65-.45.75-.45.2-.15.45-.35.65-.6s.3-.5.3-.7l-.3-.65c-.55.2-1.2.65-2.05 1.35-.85.75-1.65 1.55-2.5 2.5-.8.9-1.6 1.65-2.4 2.3-.8.65-1.4.95-1.9 1-.15 0-1.5-1.05-4.1-3.2C3.1 2.6 1.45 1.2.7.55L.45.1c-.1.05-.2.15-.3.3C.05.55 0 .7 0 .85l.05.35.05.4 1.2 1 5.2 4.15c.1.05.3.2.6.5l.7.6c.15.1.5.45 1 1.1l.1.1c-.2.15-.4.35-.6.55l-1.95 1.85-1.1 1c-1 1-1.7 1.65-2.1 1.9l-.5.35c-.4.25-.65.45-.75.45-.25.15-.45.35-.65.6-.15.3-.25.55-.25.75l.3.65c.55-.2 1.2-.65 2.05-1.35.85-.75 1.65-1.55 2.5-2.5.8-.9 1.6-1.65 2.4-2.3.8-.65 1.4-.95 1.9-1 .15 0 1.5 1.05 4.1 3.2 2.6 2.15 4.3 3.55 5.05 4.2l.2.45c.1-.05.2-.15.3-.3.1-.15.15-.3.15-.45z">
                        </path>
                    </svg>
                </a>
                <ul class="mobile_menu_inner acnav-list">
                    <li class="menu-h-link">
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
                    </li>
                </ul>
            </li>
            @include('front_end.hooks.header_link')

            <li class="mobile-item has-children lang-dropdown">
                <a href="javascript:void(0)" class="acnav-label">
                {{ Str::upper($currantLang) }}
                    <svg class="menu-open-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="11"
                        viewBox="0 0 20 11">
                        <path fill="#24272a"
                            d="M.268 1.076C.373.918.478.813.584.76l.21.474c.79.684 2.527 2.158 5.21 4.368 2.738 2.21 4.159 3.316 4.264 3.316.474-.053 1.158-.369 1.947-1.053.842-.631 1.632-1.42 2.474-2.368.895-.948 1.737-1.842 2.632-2.58.842-.789 1.578-1.262 2.105-1.42l.316.684c0 .21-.106.474-.316.737-.053.21-.263.421-.474.579-.053.052-.316.21-.737.474l-.526.368c-.421.263-1.105.947-2.158 2l-1.105 1.053-2.053 1.947c-1 .947-1.579 1.421-1.842 1.421-.263 0-.684-.263-1.158-.895-.526-.631-.842-1-1.052-1.105l-.737-.579c-.316-.316-.527-.474-.632-.474l-5.42-4.315L.267 2.339l-.105-.421-.053-.369c0-.157.053-.315.158-.473z">
                        </path>
                    </svg>
                    <svg class="close-menu-ioc" xmlns="http://www.w3.org/2000/svg" width="20" height="18"
                        viewBox="0 0 20 18">
                        <path fill="#24272a"
                            d="M19.95 16.75l-.05-.4-1.2-1-5.2-4.2c-.1-.05-.3-.2-.6-.5l-.7-.55c-.15-.1-.5-.45-1-1.1l-.1-.1c.2-.15.4-.35.6-.55l1.95-1.85 1.1-1c1-1 1.7-1.65 2.1-1.9l.5-.35c.4-.25.65-.45.75-.45.2-.15.45-.35.65-.6s.3-.5.3-.7l-.3-.65c-.55.2-1.2.65-2.05 1.35-.85.75-1.65 1.55-2.5 2.5-.8.9-1.6 1.65-2.4 2.3-.8.65-1.4.95-1.9 1-.15 0-1.5-1.05-4.1-3.2C3.1 2.6 1.45 1.2.7.55L.45.1c-.1.05-.2.15-.3.3C.05.55 0 .7 0 .85l.05.35.05.4 1.2 1 5.2 4.15c.1.05.3.2.6.5l.7.6c.15.1.5.45 1 1.1l.1.1c-.2.15-.4.35-.6.55l-1.95 1.85-1.1 1c-1 1-1.7 1.65-2.1 1.9l-.5.35c-.4.25-.65.45-.75.45-.25.15-.45.35-.65.6-.15.3-.25.55-.25.75l.3.65c.55-.2 1.2-.65 2.05-1.35.85-.75 1.65-1.55 2.5-2.5.8-.9 1.6-1.65 2.4-2.3.8-.65 1.4-.95 1.9-1 .15 0 1.5 1.05 4.1 3.2 2.6 2.15 4.3 3.55 5.05 4.2l.2.45c.1-.05.2-.15.3-.3.1-.15.15-.3.15-.45z">
                        </path>
                    </svg>
                </a>

                <ul class="mobile_menu_inner acnav-list">
                    <li class="menu-h-link">
                        <ul>
                            @foreach ($languages as $code => $language)
                                <li><a href="{{ route('change.languagestore', [$code]) }}"
                                        class="@if ($language == $currantLang) active-language text-primary @endif">{{  ucFirst($language) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!--End Mobile Menu-->
<div class="modal modal-popup" id="commanModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-inner lg-dialog" role="document">
            <div class="modal-content">
                <div class="popup-content">
                    <div class="modal-header  popup-header align-items-center">
                        <div class="modal-title">
                            <h6 class="mb-0" id="modelCommanModelLabel"></h6>
                        </div>
                        <button type="button" class="close close-button" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>

            </div>
        </div>
    </div>
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}{{ '?v=' . time() }}" >

        <!-- [ Main Content ] start -->
        <div class="wrapper">
            <div class="pct-customizer">
                <div class="pct-c-btn">
                    <button class="btn btn-primary" id="pct-toggler" data-toggle="tooltip"
                        data-bs-original-title="Order Track" aria-label="Order Track">
                        <i class='fas fa-shipping-fast' style='font-size:24px;'></i>
                    </button>
                </div>
                <div class="pct-c-content">
                    <div class="pct-header bg-primary">
                        <h5 class="mb-0 f-w-500">{{ 'Order Tracking' }}</h5>
                    </div>
                    <div class="pct-body">
                        {{ Form::open(['route' => ['order.track', $slug], 'method' => 'POST', 'id' => 'choice_form', 'enctype' => 'multipart/form-data']) }}
                        <div class="form-group col-md-12">
                            {!! Form::label('order_number', __('Order Number'), ['class' => 'form-label']) !!}
                            {!! Form::text('order_number', null, ['class' => 'form-control', 'placeholder' => 'Enter Your Order Id']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('email', __('Email Address'), ['class' => 'form-label']) !!}
                            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Enter email']) !!}
                        </div>
                        <button type="submit" class="btn justify-contrnt-end">{{ __('Submit') }}</button>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="addon-side-btn">
            @stack('couponListButton')
            @stack('SpinwheelButton')
        </div>
        @if((isset($loader_show) && $loader_show != 'no') || !isset($loader_show))
        <div id="loader" class="loader-wrapper" style="display: none;">
        <span class="site-loader"> </span>
        <h3 class="loader-content"> {{ __('Loading . . .') }} </h3>
        </div>
        @endif

        <div class="overlay cart-overlay"></div>
        <div class="cartDrawer cartajaxDrawer"></div>
        <div class="overlay wish-overlay"></div>
        <div class="wishajaxDrawer"></div>

    <!-- [ Main Content ] end -->
   <!--  jQuery Validation  -->
   <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
   <script src="{{ asset('js/jquery-cookie.min.js') }}"></script>
   <script src="{{ asset('js/loader.js') }}"></script>
@stack('recentViewModelPopup')

@stack('subscribeStorePopup')
@stack('countDownPopup')
@stack('tawktoModelPopup')
@stack('purchaseNotificationPopup')
@stack('wizzchatModelPopup')
<!--scripts end here-->
@if(isset($storejs))
{!! $storejs !!}
@endif
<!--scripts start here-->
<script>
    var guest = '{{ Auth::guest() }}';
    var filterBlog = "{{ route('blogs.filter.view',$store->slug) }}";
    var cartlistSidebar = "{{ route('cart.list.sidebar',$store->slug) }}";
    var ProductCart = "{{ route('product.cart',$store->slug) }}";
    var addressbook_data = "{{ route('get.addressbook.data', $store->slug) }}";
    var shippings_data = "{{ route('get.shipping.data', $store->slug) }}";
    var get_shippings_data = "{{ route('get.shipping.data', $store->slug) }}";
    var shippings_methods = "{{ route('shipping.method', $store->slug) }}";
    var apply_coupon = "{{ route('applycoupon', $store->slug) }}";
    var paymentlist = "{{ route('paymentlist', $store->slug) }}";
    var additionalnote = "{{ route('additionalnote', $store->slug) }}";
    var state_list = "{{ route('states.list', $store->slug) }}";
    var city_list = "{{ route('city.list', $store->slug) }}";
    var changeCart = "{{ route('change.cart', $store->slug) }}";
    var wishListSidebar = "{{ route('wish.list.sidebar', $store->slug) }}";
    var removeWishlist = "{{ route('delete.wishlist', $store->slug) }}";
    var addProductWishlist = "{{ route('product.wishlist', $store->slug) }}";
    var isAuthenticated = "{{ auth('customers')->check() ? 'true' : 'false' }}";
    var removeCart = "{{  route('cart.remove', $store->slug)  }}";
    var productPrice = "{{ route('product.price', $store->slug) }}";
    var wishList = "{{ route('wish.list',$store->slug) }}";
    var whatsappNumber = "{{ $whatsapp_contact_number ?? '' }}";
    var taxes_data = "{{ route('get.tax.data', $store->slug) }}";
    var searchProductGlobaly = "{{ route('search.product', $store->slug) }}";
    var loginUrl = "{{ route('customer.login', $store->slug) }}";
    var payfast_payment_guest = "{{ route('place.order.guest', $store->slug) }}";
    var payfast_payment = "{{ route('payment.process', $store->slug) }}";
    var site_url = $('meta[name="base-url"]').attr('content');
    var size_module_active = {{ module_is_active('SizeGuideline') ? 'true' : 'false' }};
    var site_url = $('meta[name="base-url"]').attr('content');
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://d3js.org/d3.v3.min.js"></script>
<script src="{{ asset('assets/js/front-theme.js') }}" defer="defer"></script>
    @if (isset($store->enable_pwa_store) && $store->enable_pwa_store == 'on')
        <script type="text/javascript">
            const container = document.querySelector("body")

            const coffees = [];

            if ("serviceWorker" in navigator) {
                window.addEventListener("load", function() {
                    navigator.serviceWorker
                        .register("{{ asset('serviceWorker.js') }}")
                        .then(res => console.log("service worker registered"))
                        .catch(err => console.log("service worker not registered", err))

                })
            }
        </script>
    @endif

    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $google_analytic ?? '' }}"></script>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '{{ $google_analytic ?? '' }}');
    </script>

{{-- facebook pixel code --}}
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $fbpixel_code ?? '' }}');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=0000&ev=PageView&noscript={{ $fbpixel_code ?? '' }}" /></noscript>

@if(\Request::route()->getName() == 'page.contact_us')
    <script>
        $(document).ready(function() {
            // Assuming $slug is defined somewhere in your Blade template
            var slug = "{{ $slug }}"; // Replace with actual value
            var mobile = "{{ $store->user->mobile ?? '+123 456-78-90'}}";
            var tel = "tel:{{ $store->user->mobile ?? '+1234567890'}}";
            var email = "{{ $store->user->email ?? 'shop@company.com'}}";
            var mailto = "mailto:{{ $store->user->email ?? 'shop@company.com'}}";
            var storeAddress = "{{ \App\Models\Utility::GetValueByName('store_address', $store->theme_id, $store->id) ?? '123 New Street, New City, NY, 10001'}}";
            // Update form action attribute
            var newAction = "{{ route('contacts.store') }}/" + slug;
            $('.contact-form').attr('action', newAction);

            $('ul.col-sm-6.col-12 li:eq(0) p a').text(mobile); // New phone number
            $('ul.col-sm-6.col-12 li:eq(0) p a').attr('href', tel); // New tel link

            // Change "Email"
            $('ul.col-sm-6.col-12 li:eq(1) p a').text(email); // New email address
            $('ul.col-sm-6.col-12 li:eq(1) p a').attr('href', mailto); // New mailto link

            // Change "Address"
            $('ul.col-sm-6.col-12:eq(1) li p.address').text(storeAddress); // New address
        });
    </script>
@endif