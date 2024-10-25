@extends('layouts.app')

@section('page-title')
    {{ __('Product') }}
@endsection

@php
    $logo = asset(Storage::url('uploads/profile/'));
    $admin = getAdminAllSetting();

@endphp


@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __('Product') }}</li>
@endsection

@section('action-button')
{{-- @permission('Create Product') --}}
    <div class="text-end gap-2 d-flex all-button-box justify-content-md-end justify-content-center">
        @if (module_is_active('ImportExport'))
            @permission('product import')
                @include('importexport::import.button',['module'=>'product'])
            @endpermission
            @permission('product export')
                @include('importexport::export.button',['module'=>'product'])
            @endpermission
        @endif

        <a href="{{ route('product.create') }}" class="btn btn-sm btn-primary" data-title="{{__('Create New Product')}}"
            data-toggle="tooltip" title="{{ __('Create New Product') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
{{-- @endpermission --}}
@endsection

@push('custom-script')
    <script>

    </script>
@endpush

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
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Sub Category') }}</th>
                                    <th>{{ __('Brand') }}</th>
                                    <th>{{ __('Label') }}</th>
                                    <th>{{ __('Cover Image') }}</th>
                                    <th>{{ __('Varient') }}</th>
                                    <th>{{ __('Review') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Stock Status') }}</th>
                                    <th>{{ __('Stock Quantity') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($products as $product)
                                    <tr>
                                        <td> {{ $product->name }} </td>
                                        <td> {{ !empty($product->ProductData) ? $product->ProductData->name : '' }}
                                        </td>
                                        <td>{{ !empty($product->SubCategoryctData) ? $product->SubCategoryctData->name : '' }}
                                        </td>
                                        <td>{{ !empty($product->brand) ? $product->brand->name : '-' }}
                                        </td>
                                        <td>{{ !empty($product->label) ? $product->label->name : '-' }}
                                        </td>
                                        <td>
                                            @if(isset($product->cover_image_path) && !empty($product->cover_image_path))
                                            <img src="{{ get_file($product->cover_image_path, APP_THEME()) }}"
                                                alt="" width="100" class="cover_img{{ $product->id }}">
                                            @endif
                                        </td>
                                        <td> {{ $product->variant_product == 1 ? 'has variant' : 'no variant' }} </td>
                                        <td> <i class="ti ti-star text-warning "></i>{{ $product->average_rating}} </td>
                                        @if ($product->variant_product == 0)
                                        <td>{{  currency_format_with_sym($product->price, auth()->user()->current_store, APP_THEME()) ?? SetNumberFormat($product->price) }} </td>
                                        @else
                                        <td>{{ __('In Variant') }}</td>
                                        @endif
                                        <td>
                                            @if ($product->variant_product == 1)
                                                <span
                                                    class="badge badge-80 rounded p-2 f-w-600  bg-light-warning">{{ __('In Variant') }}</span>
                                            @else
                                                @if ($product->track_stock == 0)
                                                    @if ($product->stock_status == 'out_of_stock')
                                                        <span class="badge badge-80 rounded p-2 f-w-600  bg-light-danger">
                                                            {{ __('Out of stock') }}</span>
                                                    @elseif ($product->stock_status == 'on_backorder')
                                                        <span class="badge badge-80 rounded p-2 f-w-600  bg-light-warning">
                                                            {{ __('On Backorder') }}</span>
                                                    @else
                                                        <span class="badge badge-80 rounded p-2 f-w-600  bg-light-primary">
                                                            {{ __('In stock') }}</span>
                                                    @endif
                                                @else
                                                    @if ($product->product_stock <= (isset($admin['out_of_stock_threshold']) ? $admin['out_of_stock_threshold'] : 0))
                                                        <span class="badge badge-80 rounded p-2 f-w-600  bg-light-danger">
                                                            {{ __('Out of stock') }}</span>
                                                    @else
                                                        <span class="badge badge-80 rounded p-2 f-w-600  bg-light-primary">
                                                            {{ __('In stock') }}</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->variant_product == 1)
                                                <span class=""> - </span>
                                            @else
                                                @if ($product->product_stock <= 0)
                                                    -
                                                @else
                                                    <span>
                                                        {{ $product->product_stock }}
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1">
                                                {{-- @permission('Edit Products') --}}
                                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-info"
                                                         data-title="{{ __('Edit Product') }}" data-bs-toggle="tooltip"
                                                         title="{{__('Edit') }}">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                                {{-- @endpermission --}}
                                                {{-- @permission('Delete Products') --}}
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['product.destroy', $product->id],
                                                    'class' => 'd-inline',
                                                ]) !!}
                                                <button type="button" class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip"
                                                title="{{__('Delete') }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                                {!! Form::close() !!}
                                            {{-- @endpermission --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-script')


@endpush

