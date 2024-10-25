<!--footer start here-->
<footer class="site-footer" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
        <div class="footer-top">
            <div class="container">
                @include('front_end.hooks.footer_link')
                <div class="footer-row">

                        <div class="footer-col footer-link footer-link-1">
                            <div class="footer-widget">
                                <div class="logo-col">
                                    <img src="{{ get_file(((isset($theme_logo) && !empty($theme_logo)) ? $theme_logo : 'themes/' . $currentTheme . '/assets/images/logo.png'), $currentTheme) }}"
                                    alt="Logo">
                                </div>
                                <p id="{{ $section->footer->section->description->slug ?? '' }}_preview"> {!! $section->footer->section->description->text ?? '' !!}</p>

                                <ul class="footer-list-social" role="list">
                                    @if(isset($section->footer->section->footer_link))
                                        @for ($i = 0; $i < $section->footer->section->footer_link->loop_number ?? 1; $i++)
                                            <li>
                                                <a href="{{ $section->footer->section->footer_link->social_link->{$i} ?? '#'}}" target="_blank" id="social_link_{{ $i }}">
                                                    <img src="{{ get_file($section->footer->section->footer_link->social_icon->{$i}->image ?? 'themes/' . $currentTheme . '/assets/img/youtube.png', $currentTheme) }}" class="{{ 'social_icon_'. $i .'_preview' }}" alt="icon" id="social_icon_{{ $i }}">
                                                </a>
                                            </li>
                                        @endfor
                                    @endif
                                </ul>
                            </div>
                        </div>


                        @if(isset($section->footer->section->footer_menu_type))
                            @for ($i = 0; $i < $section->footer->section->footer_menu_type->loop_number ?? 1; $i++)
                            <div class="footer-col footer-link footer-link-1">
                                <div class="footer-widget">
                                    <h4> {{ $section->footer->section->footer_menu_type->footer_title->{$i} ?? ''}} </h4>
                                    @php
                                        $footer_menu_id = $section->footer->section->footer_menu_type->footer_menu_ids->{$i} ?? '';
                                        $footer_menu = get_nav_menu($footer_menu_id);
                                    @endphp
                                    <ul>
                                        @if (!empty($footer_menu))
                                            @foreach ($footer_menu as $key => $nav)
                                                @if ($nav->type == 'custom')
                                                    <li><a href="{{ url($nav->slug) }}"
                                                            target="{{ $nav->target }}">
                                                            @if ($nav->title == null)
                                                                {{ $nav->title }}
                                                            @else
                                                                {{ $nav->title }}
                                                            @endif
                                                        </a></li>
                                                @elseif($nav->type == 'category')
                                                    <li><a href="{{ url($slug.'/'.$nav->slug) }}"
                                                            target="{{ $nav->target }}">
                                                            @if ($nav->title == null)
                                                                {{ $nav->title }}
                                                            @else
                                                                {{ $nav->title }}
                                                            @endif
                                                        </a></li>
                                                @elseif($nav->type == 'brand')
                                                    <li><a href="{{ url($slug.'/'.$nav->slug) }}"
                                                            target="{{ $nav->target }}">
                                                            @if ($nav->title == null)
                                                                {{ $nav->title }}
                                                            @else
                                                                {{ $nav->title }}
                                                            @endif
                                                        </a></li>
                                                @else
                                                    <li><a href="{{ url($slug.'/custom/'.$nav->slug) }}"
                                                            target="{{ $nav->target }}">
                                                            @if ($nav->title == null)
                                                                {{ $nav->title }}
                                                            @else
                                                                {{ $nav->title }}
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            @endfor
                        @endif

                        <div class="footer-col footer-subscribe-col">
                            <div class="footer-widget">
                                <h4>Subscribe newsletter and get -20% off</h4>
                                <form class="footer-subscribe-form" action="{{ route("newsletter.store",$slug) }}" method="post">
                                    @csrf
                                    <div class="input-box">
                                    <input type="email" placeholder="Type your address email......" name="email">
                                    <button class="btn-subscibe">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.6883 2.12059C14.0686 1.54545 15.4534 2.93023 14.8782 4.31056L10.9102 13.8338C10.1342 15.6962 7.40464 15.3814 7.07295 13.3912L6.5779 10.4209L3.60764 9.92589C1.61746 9.5942 1.30266 6.8646 3.16509 6.08859L12.6883 2.12059ZM13.6416 3.79527C13.7566 3.51921 13.4796 3.24225 13.2036 3.35728L3.68037 7.32528C3.05956 7.58395 3.1645 8.49381 3.82789 8.60438L6.79816 9.09942C7.36282 9.19353 7.80531 9.63602 7.89942 10.2007L8.39446 13.171C8.50503 13.8343 9.41489 13.9393 9.67356 13.3185L13.6416 3.79527Z" fill="#12131A"></path>
                                        </svg>
                                    </button>
                                </div>

                                </form>
                            </div>
                        </div>
                    </div>

            </div>

        </div>
    </footer>
<!--footer end here-->
<!--serch popup ends here-->
<div class="search-popup">
    <div class="close-search">
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
            <path
                d="M27.7618 25.0008L49.4275 3.33503C50.1903 2.57224 50.1903 1.33552 49.4275 0.572826C48.6647 -0.189868 47.428 -0.189965 46.6653 0.572826L24.9995 22.2386L3.33381 0.572826C2.57102 -0.189965 1.3343 -0.189965 0.571605 0.572826C-0.191089 1.33562 -0.191186 2.57233 0.571605 3.33503L22.2373 25.0007L0.571605 46.6665C-0.191186 47.4293 -0.191186 48.666 0.571605 49.4287C0.952952 49.81 1.45285 50.0007 1.95275 50.0007C2.45266 50.0007 2.95246 49.81 3.3339 49.4287L24.9995 27.763L46.6652 49.4287C47.0465 49.81 47.5464 50.0007 48.0463 50.0007C48.5462 50.0007 49.046 49.81 49.4275 49.4287C50.1903 48.6659 50.1903 47.4292 49.4275 46.6665L27.7618 25.0008Z"
                fill="white"></path>
        </svg>
    </div>
    <div class="search-form-wrapper">
        <form>
            <div class="form-inputs">
                <input type="search" placeholder="Search Product..." class="form-control search_input" list="products"
                    name="search_product" id="product">
                <datalist id="products">
                    @foreach ($search_products as $pro_id => $pros)
                            <option value="{{$pros}}"></option>
                    @endforeach
                </datalist>
                <button type="submit" class="btn search_product_globaly">
                    <svg>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M0.000169754 6.99457C0.000169754 10.8576 3.13174 13.9891 6.99473 13.9891C8.60967 13.9891 10.0968 13.4418 11.2807 12.5226C11.3253 12.6169 11.3866 12.7053 11.4646 12.7834L17.0603 18.379C17.4245 18.7432 18.015 18.7432 18.3792 18.379C18.7434 18.0148 18.7434 17.4243 18.3792 17.0601L12.7835 11.4645C12.7055 11.3864 12.6171 11.3251 12.5228 11.2805C13.442 10.0966 13.9893 8.60951 13.9893 6.99457C13.9893 3.13157 10.8577 0 6.99473 0C3.13174 0 0.000169754 3.13157 0.000169754 6.99457ZM1.86539 6.99457C1.86539 4.1617 4.16187 1.86522 6.99473 1.86522C9.8276 1.86522 12.1241 4.1617 12.1241 6.99457C12.1241 9.82743 9.8276 12.1239 6.99473 12.1239C4.16187 12.1239 1.86539 9.82743 1.86539 6.99457Z">
                        </path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
<!--serch popup ends here-->
<div class="overlay cart-overlay"></div>
<div class="cartDrawer cartajaxDrawer">

</div>

























