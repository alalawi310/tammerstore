@extends('front_end.layouts.app')
@section('page-title')
{{ ucfirst($page->page_name ?? __('Home Page')) }}
@endsection
@section('content')
@include('front_end.sections.partision.header_section')
@php
    $theme_favicon = \App\Models\Utility::GetValueByName('theme_favicon', $currentTheme);
    $theme_favicons = get_file($theme_favicon, $currentTheme);
    $theme_logo = \App\Models\Utility::GetValueByName('theme_logo', $currentTheme);
    $theme_logo = get_file($theme_logo, $currentTheme);
    $currantLang = Cookie::get('LANGUAGE');
    if (!isset($currantLang)) {
        $currantLang = $store->default_language;
    }

@endphp
<div class="padding-top order-details">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-12">
                    <div class="common-banner-content">
                        <div class="row">
                            <div class="col-xl-5">
                                <div class="section-title">
                                    <h2>{{ __('Your Order Details') }}</h2>
                                </div>
                            </div>
                            <div class="col-xl-7">
                                <div
                                    class=" d-flex all-button-box justify-content-md-end justify-content-end text-end">
                                    <button type="submit" onclick="saveAsPDF();" title="Print" aria-label="Print"
                                        class="btn continue-btn  ">
                                        <i class="ti ti-printer" style="font-size:20px"> </i>
                                        <span class="btn-inner--text text-white">{{ __('Print') }}</span>

                                    </button>
                                    <button
                                        class="btn btn-sm btn-secondary "style="margin-left: 5px">{{ $order['order_status_text'] }}</button>
                                    @if (
                                        $order['payment_status'] == 'Unpaid' &&
                                            $order['order_status_text'] != 'Cancel' &&
                                            $order_data['delivered_status'] == 0)
                                        <a class="delstatus btn btn-sm btn-primary me-2 " style="margin-left: 5px"
                                            data-id="{{ $order['id'] }}">
                                            <i class="ti ti-trash " style="font-size:20px"></i>
                                            <span class="btn-inner--text text-white">{{ __('Order Cencel') }}</span>
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <section class="product-listing-section padding-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row order-details-modal product-modal-detail"  id="printableArea">
                            <div class="col-xxl-7">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <p class="mb-0"><b>{{ __('Items from Order') }} {{ $order['order_id'] }}</b>
                                        </p>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Item') }}</th>
                                                        <th>{{ __('Quantity') }}</th>
                                                        <th>{{ __('Total') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $variant = \App\Models\ProductVariant::where('id', $order['product']['variant_id'])->first();
                                                        $product = \App\Models\Product::where('id', $order['product']['product_id'])->first();
                                                    @endphp
                                                    <tr>
                                                        <td class="total">
                                                            <span class="h6 text-sm"> <a href="#">{{ $order['product']['name'] }}</a>
                                                            </span> <br>
                                                            <span class="text-sm"> {{ $order['product']['variant_name'] }} </span>
                                                        </td>
                                                        <td>
                                                            {{ $order['product']['qty'] }}
                                                        </td>
                                                        <td>
                                                            {{ currency_format_with_sym( ($order['final_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order['final_price']) }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-lg-6 ">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <b class="">{{ __('Shipping Information') }}</b>
                                            </div>
                                            <div class="card-body pt-0">
                                                <address class="mb-0 text-sm">
                                                    <ul class="row mt-4 align-items-center">
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('Name') }}</b></li>
                                                        <li class="col-sm-7 col-6 text-sm">
                                                            {{ !empty($order['delivery_informations']['name']) ? $order['delivery_informations']['name'] : '' }}
                                                        </li>
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('Email') }}</b></li>
                                                        <li class="col-sm-7 col-6 text-sm">
                                                            {{ !empty($order['delivery_informations']['email']) ? $order['delivery_informations']['email'] : '' }}
                                                        </li>
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('City') }}</b></li>
                                                        <li class="col-sm-7 col-6 text-sm">
                                                            {{ !empty($order['delivery_informations']['city']) ? $order['delivery_informations']['city'] : '' }}
                                                        </li>
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('State') }}</b></li>
                                                        <li class="col-sm-7 col-6 text-sm">
                                                            {{ !empty($order['delivery_informations']['state']) ? $order['delivery_informations']['state'] : '' }}
                                                        </li>
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('Country') }}</b></li>
                                                        <li class="col-sm-7 col-6 text-sm">
                                                            {{ !empty($order['delivery_informations']['country']) ? $order['delivery_informations']['country'] : '' }}
                                                        </li>
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('Postal Code') }}</b>
                                                        </li>
                                                        <li class="col-sm-7 col-6 text-sm">
                                                            {{ !empty($order['delivery_informations']['post_code']) ? $order['delivery_informations']['post_code'] : '' }}
                                                        </li>
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('Phone') }} </b></li>
                                                        <li class="col-sm-7 col-6 text-sm">
                                                            <a href="https://api.whatsapp.com/send?phone={{ !empty($order['delivery_informations']['phone']) ? $order['delivery_informations']['phone'] : '' }}&amp;text=Hi"
                                                                target="_blank">
                                                                {{ !empty($order['delivery_informations']['phone']) ? $order['delivery_informations']['phone'] : '' }}
                                                            </a>
                                                        </li>

                                                    </ul>
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-lg-6 ">
                                        <div class="card">
                                            <div class="card-header d-flex justify-content-between">
                                                <b class="">{{ __('Billing Information') }}</b>
                                            </div>
                                            <div class="card-body pt-0">
                                                <address class="mb-0 text-sm">
                                                    <ul class="row mt-4 align-items-center">
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('Name') }}</b></li>
                                                        <dd class="col-sm-7 col-6 text-sm pb-2">
                                                            {{ !empty($order['billing_informations']['name']) ? $order['billing_informations']['name'] : '' }}
                                                        </dd>
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('Email') }}</b></li>
                                                        <dd class="col-sm-7 col-6 text-sm">
                                                            {{ !empty($order['billing_informations']['email']) ? $order['billing_informations']['email'] : '' }}
                                                        </dd>
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('City') }}</b></li>
                                                        <dd class="col-sm-7 col-6 text-sm">
                                                            {{ !empty($order['billing_informations']['city']) ? $order['billing_informations']['city'] : '' }}
                                                        </dd>
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('State') }}</b></li>
                                                        <dd class="col-sm-7 col-6 text-sm">
                                                            {{ !empty($order['billing_informations']['state']) ? $order['billing_informations']['state'] : '' }}
                                                        </dd>
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('Country') }}</b></li>
                                                        <dd class="col-sm-7 col-6 text-sm">
                                                            {{ !empty($order['billing_informations']['country']) ? $order['billing_informations']['country'] : '' }}
                                                        </dd>
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('Postal Code') }}</b>
                                                        </li>
                                                        <dd class="col-sm-7 col-6 text-sm">
                                                            {{ !empty($order['billing_informations']['post_code']) ? $order['billing_informations']['post_code'] : '' }}
                                                        </dd>
                                                        <li class="col-sm-5 col-6 text-sm"><b>{{ __('Phone') }}</b></li>
                                                        <li class="col-sm-7 col-6 text-sm">
                                                            <a href="https://api.whatsapp.com/send?phone={{ !empty($order['billing_informations']['phone']) ? $order['billing_informations']['phone'] : '' }}&amp;text=Hi"
                                                                target="_blank">
                                                                {{ !empty($order['billing_informations']['phone']) ? $order['billing_informations']['phone'] : '' }}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @stack('showdigitalproductattachment')
                            </div>
                            <div class="col-xxl-5 col-md-6 col-12">
                                <div class="card  p-0">
                                    <div class="card-header d-flex justify-content-between pb-0">
                                        <b class="mb-4">{{ __('Extra Information') }}</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td>{{ __('Sub Total') }} :</td>
                                                        <td>{{ SetNumberFormat($order['final_price']) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('Estimated Tax') }} :</td>
                                                        <td>
                                                            {{ SetNumberFormat($order['tax_price']) }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('Apply Coupon') }} :</td>
                                                        <td>{{ !empty($order['coupon_info']['discount_amount']) ? SetNumberFormat($order['coupon_info']['discount_amount']) : SetNumberFormat(0) }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('Delivered Charges') }} :</td>
                                                        <td>{{ SetNumberFormat($order_data->shipping_price ?? 0) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('Grand Total') }} :</td>
                                                        <td><b>{{ SetNumberFormat($order['sub_total']) }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('Order Status') }} :</td>
                                                        <td>{{ $order['order_status_text'] }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @if (!empty($order_note))
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between">
                                            <p class="mb-0"><b>{{ __('Order updates for') }}
                                                    {{ $order['order_id'] }}</b>
                                            </p>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($order_note as $note)
                                                <div class="card">
                                                    <div class="card-header">
                                                        <span class="time">
                                                            {{ $i }} .
                                                            {{ $note->created_at->format('l jS \\of F Y, h:ia') }}
                                                        </span>
                                                        <span class="tl-btn licence-btn">
                                                            {{ $note->notes }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="social-media">
            @if(isset($section->footer->section->footer_link))
                <div class="container">
                    <ul class="social-links justify-content-end">
                        @for ($i = 0; $i < $section->footer->section->footer_link->loop_number ?? 1; $i++)
                            <li>
                                <a href="{{ $section->footer->section->footer_link->social_link->{$i} ?? '#'}}" target="_blank" id="social_link_{{ $i }}">
                                    <img src="{{ asset($section->footer->section->footer_link->social_icon->{$i}->image ?? 'themes/' . $currentTheme . '/assets/images/youtube.svg') }}" class="{{ 'social_icon_'. $i .'_preview' }}" alt="icon" id="social_icon_{{ $i }}">
                                </a>
                            </li>
                        @endfor
                    </ul>
                </div>
            @endif
        </div>
    </div>
    @include('front_end.sections.partision.footer_section')
@endsection
@push('scripts')
<script>
        var filename = $('#filesname').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();


        }
        $(document).on('click', '.delstatus', function() {

            var order_id = $(this).attr('data-id');
            var data = {
                order_id: order_id,
                order_status: 'cancel',
            }
            $.ajax({
                url: '{{ route('status.cancel', $store->slug) }}',
                data: data,
                type: 'post',
                success: function(data) {
                    $('#loader').fadeOut();
                    if (data.status == 'error') {
                        show_toastr('{{ __('Error') }}', data.message, 'error')
                    } else {
                        show_toastr('{{ __('Success') }}', data.message, 'success')
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });

        document.querySelectorAll('.downloadable_product_variant').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const downloadLink = document.querySelector('.download_prod_' + productId);
                if (downloadLink) {
                    downloadLink.click();
                } else {
                    console.error('Download link not found for product ID:', productId);
                }
            });
        });
    </script>
@endpush



