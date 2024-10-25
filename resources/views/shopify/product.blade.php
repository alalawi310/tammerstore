@extends('layouts.app')

@section('page-title', __('Product'))

@section('action-button')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Product') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Cover Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Variant') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product_data)
                                    <tr>
                                        <td>
                                            <img src="{{ !empty($product_data['image']) ? get_file($product_data['image']['src'], APP_THEME()) : asset(Storage::url('uploads/woocommerce.png')) }}"
                                                alt="" width="100" class="cover_img">
                                        </td>
                                        <td> {{ $product_data['title'] }} </td>
                                        <td> {{ $product_data['product_type'] }} </td>
                                        @if ($product_data['variants'][0]['title'] == 'Default Title')
                                            <td> {{ __('no variant') }} </td>
                                        @else
                                            <td> {{ __('in variant') }} </td>
                                        @endif
                                        <td class="text-end">
                                            @if (in_array($product_data['id'], $upddata))
                                                <a href="{{ route('shopify_product.edit', $product_data['id']) }}"
                                                    class="btn btn-sm btn-info" data-title="{{ __('Sync Again') }}">
                                                    <i class="ti ti-refresh " data-bs-toggle="tooltip"
                                                        title="Sync Again"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('shopify_product.show', $product_data['id']) }}"
                                                    class="btn btn-sm btn-primary" data-title="{{ __('Add Product') }}">
                                                    <i class="ti ti-plus" data-bs-toggle="tooltip"
                                                        title="{{ __('Add Product') }}"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination">
                        @if ($prev_page_info)
                            <a href="{{ route('shopify_product.index', ['page_info' => $prev_page_info, 'limit' => $limit]) }}"
                                class="btn btn-secondary">
                                {{ __('Previous') }}
                            </a>
                        @endif
                        @if ($next_page_info)
                            <a href="{{ route('shopify_product.index', ['page_info' => $next_page_info, 'limit' => $limit]) }}"
                                class="btn btn-secondary">
                                {{ __('Next') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-script')
    <script>
        $(document).ready(function() {
            const totalEntries = {{ $total_products }};
            $('.dataTable-info').text('Showing ' + totalEntries + ' entries');
            $('.dataTable-dropdown').hide();
        });
    </script>
@endpush
