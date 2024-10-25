@extends('layouts.app')

@section('page-title')
    {{ __('Product') }}
@endsection

@php
    $logo = asset(Storage::url('uploads/profile/'));
@endphp

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('product.index') }}">{{ __('Product') }}</a></li>
    <li class="breadcrumb-item" aria-current="page">{{ __('Detail') }}</li>
@endsection
@section('action-button')
    <div class=" text-end d-flex gap-2 all-button-box justify-content-md-end justify-content-center">
        <a href="{{ url()->previous() }}" class="btn  btn-primary " data-title="{{ __('Back') }}"
            title="{{ __('Back') }}">
            <i class="ti ti-arrow-back"></i> <span class="ms-2 me-2">{{ __('Back') }}</span> </a>

        <a href="{{ route('product.edit', $product->id) }}" class="btn  btn-primary " data-title="{{ __('Edit') }}"
            title="{{ __('Edit') }}">
            <i class="ti ti-pencil py-1"></i> <span class="ms-2 me-2">{{ __('Edit') }}</span> </a>
    </div>
@endsection
@php
    $plan = \App\Models\Plan::find(\Auth::user()->plan_id);
    $theme_name = !empty(APP_THEME()) ? APP_THEME() : env('DATA_INSERT_APP_THEME');
    $stock_management = \App\Models\Utility::GetValueByName('stock_management', $theme_name);
    $low_stock_threshold = \App\Models\Utility::GetValueByName('low_stock_threshold', $theme_name);
@endphp
@section('content')
    <div class="row pt-4">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <h5 class="mb-3">{{ __('Main Informations') }}</h5>
                    <div class="card border">
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <div class="form-group col-12">
                                    {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}
                                    {!! Form::text('name', $product->name, ['class' => 'form-control name']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 parmalink ">
                                    {!! Form::label('', __('Permalink'), ['class' => 'form-label col-md-3']) !!}
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="input-group-text col-12" id="basic-addon2">{{ $link }}</span>
                                        {!! Form::text('slug', $product->slug, ['class' => 'form-control slug col-12']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    {!! Form::label('', __('Category'), ['class' => 'form-label']) !!}
                                    {!! Form::select('maincategory_id', $MainCategory, $product->maincategory_id, [
                                        'class' => 'form-control',
                                        'data-role' => 'tagsinput',
                                        'id' => 'maincategory_id',
                                    ]) !!}
                                </div>
                                <div class="form-group  col-12 subcategory_id_div" data_val='0'>
                                    {!! Form::label('', __('Subcategory'), ['class' => 'form-label']) !!}
                                    <span>
                                        {!! Form::select('subcategory_id', $SubCategory, $product->subcategory_id, [
                                            'class' => 'form-control',
                                            'data-role' => 'tagsinput',
                                            'id' => 'subcategory-dropdown',
                                        ]) !!}
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-12 switch-width">
                                    {{ Form::label('tax_id', __('Taxs'), ['class' => ' form-label']) }}
                                    <select name="tax_id[]" data-role="tagsinput" id="tax_id" multiple>

                                        @foreach ($Tax as $Key => $tax)
                                            <option @if (in_array($Key, $get_tax)) selected @endif
                                                value={{ $Key }}>
                                                {{ $tax }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-md-6 col-12">
                                    {!! Form::label('', __('Tax Status'), ['class' => 'form-label']) !!}
                                    {!! Form::select('tax_status', $Tax_status, $product->tax_status, [
                                        'class' => 'form-control',
                                        'data-role' => 'tagsinput',
                                        'id' => 'tax_id',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group  col-12" data_val='0'>
                                    {!! Form::label('', __('Brand'), ['class' => 'form-label']) !!}
                                    {!! Form::select('brand_id', $brands, $product->brand_id, [
                                        'class' => 'form-control',
                                        'data-role' => 'tagsinput',
                                        'id' => 'brand-dropdown',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group  col-12" data_val='0'>
                                    {!! Form::label('', __('Label'), ['class' => 'form-label']) !!}
                                    {!! Form::select('label_id', $labels, $product->label_id, [
                                        'class' => 'form-control',
                                        'data-role' => 'tagsinput',
                                        'id' => 'label-dropdown',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    {!! Form::label('', __('Tags'), ['class' => 'form-label']) !!}
                                    <select name ="tag_id[]" class="select2 form-control" id="tag_id" multiple required>
                                        @foreach ($tag as $key => $t)
                                            <option @if (in_array($key, $get_tags)) selected @endif
                                                value="{{ $key }}">
                                                {{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    {!! Form::label('', __('Shipping'), ['class' => 'form-label']) !!}
                                    {!! Form::select('shipping_id', $Shipping, $product->shipping_id, [
                                        'class' => 'form-control',
                                        'data-role' => 'tagsinput',
                                        'id' => 'shipping_id',
                                    ]) !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-12 product-weight">
                                    {!! Form::label('', __('Weight(Kg)'), ['class' => 'form-label ']) !!}
                                    {!! Form::number('product_weight',  $product->product_weight, ['class' => 'form-control', 'min' => '0', 'step' => '0.01']) !!}
                                </div>
                            </div>
                            <div class="row product-price-div">
                                <div class="form-group col-md-6 col-12 product_price">
                                    {!! Form::label('', __('Price'), ['class' => 'form-label']) !!}
                                    {!! Form::number('price',  $product->price, ['class' => 'form-control', 'min' => '0', 'step' => '0.01']) !!}
                                </div>
                                <div class="form-group col-md-6 col-12">
                                    {!! Form::label('', __('Sale Price'), ['class' => 'form-label']) !!}
                                    {!! Form::number('sale_price',  $product->sale_price, ['class' => 'form-control', 'min' => '0', 'step' => '0.01']) !!}
                                </div>
                            </div>
                            <div class="product-stock-div">
                                <hr>
                                <h4>{{ __('Product Stock') }}</h4>
                                <div class="row">
                                    @if ($stock_management == 'on')
                                        <div class="form-group col-md-6 col-12">
                                            {!! Form::label('', __('Stock Management'), ['class' => 'form-label']) !!}
                                            <div class="form-check form-switch">
                                                <input type="hidden" name="track_stock" value="0">
                                                {!! Form::checkbox('track_stock', 1, $product->track_stock, [
                                                    'class' => 'form-check-input enable_product_stock',
                                                    'id' => 'enable_product_stock',
                                                ]) !!}
                                                <label class="form-check-label" for="enable_product_stock"></label>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group col-md-6 col-12 product_stock">
                                            {!! Form::label('', __('Stock Management'), ['class' => 'form-label']) !!}<br>
                                            <label name="trending" value=""><small>{{ __('Disabled in') }} <a
                                                        href="{{ route('setting.index') . '#Brand_Setting ' }}">
                                                        {{ __('store') }}
                                                        {{ __('setting') }}</a></small></label>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="form-group col-12 stock_stats stock_div_status">
                                        {!! Form::label('', __('Stock Status:'), ['class' => 'form-label']) !!}
                                        <div class="col-mb-9">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input code" type="radio" id="in_stock"
                                                    value="in_stock" name="stock_status"
                                                    {{ $product->stock_status == 'in_stock' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="   ">
                                                    {{ __('In Stock') }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input code" type="radio" id="out_of_stock"
                                                    value="out_of_stock" name="stock_status"
                                                    {{ $product->stock_status == 'out_of_stock' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="out_of_stock">
                                                    {{ __('Out of stock') }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input code" type="radio" id="on_backorder"
                                                    value="on_backorder" name="stock_status"
                                                    {{ $product->stock_status == 'on_backorder' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="on_backorder">
                                                    {{ __('On Backorder') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($stock_management == 'on')
                                    <div class="row" id="options">
                                        <div class="form-group col-md-6 col-12 product_stock">
                                            {!! Form::label('', __('Stock'), ['class' => 'form-label']) !!}
                                            {!! Form::number('product_stock', $product->product_stock, ['class' => 'form-control productStock']) !!}
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            {!! Form::label('', __('Low stock threshold'), ['class' => 'form-label']) !!}
                                            {!! Form::number('low_stock_threshold', $low_stock_threshold, ['class' => 'form-control', 'min' => '0']) !!}
                                        </div>
                                        <div class="col-12 mb-3">
                                            {!! Form::label('', __('Allow BackOrders:'), ['class' => 'form-label']) !!}
                                            <div class="form-check m-1">
                                                <input type="radio" id="not_allow" value="not_allow"
                                                    name="stock_order_status" class="form-check-input code"
                                                    {{ $product->stock_order_status == 'not_allow' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="not_allow">{{ __('Do Not Allow') }}</label>
                                            </div>
                                            <div class="form-check m-1">
                                                <input type="radio" id="notify_customer" value="notify_customer"
                                                    name="stock_order_status" class="form-check-input code"
                                                    {{ $product->stock_order_status == 'notify_customer' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="notify_customer">{{ __('Allow, But notify customer') }}</label>
                                            </div>
                                            <div class="form-check m-1">
                                                <input type="radio" id="allow" value="allow"
                                                    name="stock_order_status" class="form-check-input code"
                                                    {{ $product->stock_order_status == 'allow' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="allow">{{ __('Allow') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @stack('editCartQuantityControlFilds')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <h5 class="mb-3">{{ __('Product Image') }}</h5>
                    <div class="card border">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        
                                        <div class="form-group pt-3">
                                            <div class="row gy-3 gx-3">
                                                @foreach ($product_image as $file)
                                                    <div class="col-sm-6 product_Image {{ 'delete_img_' . $file->id }}"
                                                        data-id="{{ $file->id }}">
                                                        <div
                                                            class="position-relative p-2 border rounded border-primary overflow-hidden rounded">
                                                            <img src="{{ get_file($file->image_path, APP_THEME()) }}"
                                                                alt="" class="w-100">
                                                            <div
                                                                class="position-absolute text-center top-50 end-0 start-0 pb-3">
                                                                <a href="{{ get_file($file->image_path, APP_THEME()) }}"
                                                                    download=""
                                                                    data-original-title="{{ __('Download') }}"
                                                                    class="btn btn-sm btn-primary me-2"><i
                                                                        class="ti ti-download"></i></a>
                                                                <a class="btn btn-sm btn-danger bg-danger deleteRecord"
                                                                    name="deleteRecord" data-id="{{ $file->id }}"><i
                                                                        class="ti ti-trash"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cover_image"
                                            class="col-form-label">{{ __('Upload Cover Image') }}</label>
                                        <img id="upcoverImg"
                                            src="{{ get_file($product->cover_image_path, APP_THEME()) }}" width="20%"
                                            class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-12" id="downloadable-product-div">
                                    <div class="form-group">
                                        <div class="choose-file">
                                            <label for="downloadable_product"
                                                class="form-label">{{ __('Downloadable Product') }}</label>
                                            @if ($product->downloadable_product != '')
                                                <img src="{{ get_file($product->downloadable_product, APP_THEME()) }} "
                                                    width="20%">
                                                <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 col-12" id="preview_type">
                                    {{ Form::label('preview_type', __('Preview Type'), ['class' => 'form-label']) }}
                                    {{ Form::select('preview_type', $preview_type, null, ['class' => 'form-control font-style', 'id' => 'preview_type']) }}
                                </div>
                                <div class="form-group  col-md-6 col-12" id="preview-video-div">
                                    <div class="form-group">
                                        <div class="choose-file">
                                            <label for="preview_video"
                                                class="form-label">{{ __('Preview Video') }}</label>
                                          
                                            @if ($product->preview_content != '')
                                                <a href="{{ get_file($product->preview_content, APP_THEME()) }}"
                                                    target="_blank">
                                                    <video height="100px" controls="" class="mt-2">
                                                        <source id="preview_video"
                                                            src="{{ get_file($product->preview_content, APP_THEME()) }}"
                                                            type="video/mp4">
                                                    </video>
                                                </a>
                                            @endif
                                            <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6 col-12 ml-auto d-none" id="preview-iframe-div">
                                    {{ Form::label('preview_iframe', __('Preview iFrame'), ['class' => 'form-label']) }}
                                    <textarea name="preview_iframe" id="preview_iframe" class="form-control font-style" rows="2" value="">{{ $product->preview_type == 'iFrame' ? $product->preview_content : '' }}</textarea>
                                </div>
                                <div class="form-group col-md-6 col-12" id="video_url_div">
                                    {{ Form::label('video_url', __('Video URL'), ['class' => 'form-label']) }}
                                    <input class="form-control font-style" name="video_url" type="text"
                                        id="video_url"
                                        value="{{ $product->preview_type == 'Video Url' ? $product->preview_content : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @stack('editsizeguidefields')
                </div>
                <div class="col-lg-4 col-12">
                    <h5 class="mb-3">{{ __('About product') }}</h5>
                    <div class="card border">
                        <div class="card-body">
                            <div class="form-group">
                                {{ Form::label('description', __('Product Description'), ['class' => 'form-label']) }}
                                {{ Form::textarea('description', $product->description, ['class' => 'form-control  summernote-simple-product', 'rows' => 1, 'placeholder' => __('Product Description'), 'id' => 'description']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('specification', __('Product Specification'), ['class' => 'form-label']) }}
                                {{ Form::textarea('specification', $product->specification, ['class' => 'form-control  summernote-simple-product', 'rows' => 1, 'placeholder' => __('Product Specification'), 'id' => 'specification']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('detail', __('Product Details'), ['class' => 'form-label']) }}
                                {{ Form::textarea('detail', $product->detail, ['class' => 'form-control  summernote-simple-product', 'rows' => 1, 'placeholder' => __('Product Details'), 'id' => 'detail']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <h5 class="mb-3">{{ __('Main Informations') }}</h5>
                    <div class="card border">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-lg-3 col-md-6  col-12">
                                    {!! Form::label('', __('Display Variants'), ['class' => 'form-label']) !!}
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="variant_product" value="0">
                                        {!! Form::checkbox('variant_product', 1, $product->variant_product, [
                                            'class' => 'form-check-input enable_product_variant',
                                            'id' => 'enable_product_variant',
                                        ]) !!}
                                        <label class="form-check-label" for="enable_product_variant"></label>
                                    </div>
                                </div>
                                <div class="form-group col-lg-3 col-md-6  col-12">
                                    {!! Form::label('', __('Trending'), ['class' => 'form-label']) !!}
                                    <div class="form-check form-switch">
                                        {!! Form::hidden('trending', 0) !!}
                                        {!! Form::checkbox('trending', 1, $product->trending, ['class' => 'form-check-input', 'id' => 'trending_product']) !!}
                                        {!! Form::label('', '', ['class' => 'form-check-label', 'for' => 'trending_product']) !!}
                                    </div>
                                </div>
                                <div class="form-group col-lg-3 col-md-6  col-12">
                                    {!! Form::label('', __('Display Product'), ['class' => 'form-label']) !!}
                                    <div class="form-check form-switch">
                                        {!! Form::hidden('status', 0) !!}
                                        {!! Form::checkbox('status', 1, $product->status, ['class' => 'form-check-input', 'id' => 'status']) !!}
                                        {!! Form::label('', '', ['class' => 'form-check-label', 'for' => 'status']) !!}
                                    </div>
                                </div>
                                <div class="form-group col-lg-3 col-md-6  col-12">
                                    {!! Form::label('', __('Custom  Field'), ['class' => 'form-label']) !!}
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="custom_field_status" value="0">

                                        {!! Form::checkbox('custom_field_status', 1, $product->custom_field_status, [
                                            'class' => 'form-check-input',
                                            'id' => 'enable_custom_field',
                                        ]) !!}
                                        <label class="form-check-label" for="enable_custom_field"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            {!! Form::label('', __('Product Attribute'), ['class' => 'form-label']) !!}
                                            {!! Form::select('attribute_id[]', $ProductAttribute, $get_datas, [
                                                'class' => 'form-control product_attribute attribute_id',
                                                'multiple' => 'multiple',
                                                'data-role' => 'tagsinput',
                                                'id' => 'attribute_id',
                                            ]) !!}


                                            <small>{{ __('Choose the attributes of this product and then input values of each attribute') }}</small>
                                        </div>

                                        <div class="attribute_options" id="attribute_options">
                                            @if ($product->product_attribute == 'NULL')
                                            @else
                                                @if (isset($product->product_attribute))
                                                    @foreach (json_decode($product->product_attribute) as $key => $choice_option)
                                                        @php

                                                            $value = implode(',', $choice_option->values);
                                                            $idsArray = explode('|', $value);
                                                            $get_datas = \App\Models\ProductAttributeOption::whereIn(
                                                                'id',
                                                                $idsArray,
                                                            )
                                                                ->get()
                                                                ->pluck('terms')
                                                                ->toArray();
                                                            $get_data = implode(',', $get_datas);
                                                            $option = \App\Models\ProductAttributeOption::where(
                                                                'attribute_id',
                                                                $choice_option->attribute_id,
                                                            )
                                                                ->get()
                                                                ->pluck('terms')
                                                                ->toArray();

                                                            $attribute_id = $choice_option->attribute_id;

                                                            $visible_attribute = isset(
                                                                $choice_option->{'visible_attribute_' . $attribute_id},
                                                            )
                                                                ? $choice_option->{'visible_attribute_' . $attribute_id}
                                                                : 0;
                                                            $for_variation = isset(
                                                                $choice_option->{'for_variation_' . $attribute_id},
                                                            )
                                                                ? $choice_option->{'for_variation_' . $attribute_id}
                                                                : 0;
                                                        @endphp

                                                        <div class="card">
                                                            <div class="card-body">
                                                                <input type="hidden" name="attribute_no[]"
                                                                    value="{{ $choice_option->attribute_id }}">
                                                                <div class="form-group row col-12">
                                                                    <div class="form-group col-md-6">
                                                                        <label
                                                                            for="attribute_id">{{ \App\Models\ProductAttribute::find($choice_option->attribute_id)->name }}:</label>
                                                                    </div>
                                                                    <div
                                                                        class="form-group col-md-6 text-end d-flex all-button-box justify-content-md-end justify-content-center">
                                                                        <a href="#"
                                                                            class="btn btn-sm btn-primary add_attribute"
                                                                            data-ajax-popup="true"
                                                                            data-title="{{ __('Add Attribute Option') }}"
                                                                            data-size="md"
                                                                            data-url="{{ route('product-attribute-option.create', $choice_option->attribute_id) }}"
                                                                            data-toggle="tooltip">
                                                                            <i
                                                                                class="ti ti-plus">{{ __('Add Attribute option') }}</i></a>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row col-12 parent-clase">
                                                                    <div class="form-group col-md-5">
                                                                        <div class="form-chec1k form-switch">
                                                                            {!! Form::hidden('visible_attribute_' . $choice_option->attribute_id, 0) !!}
                                                                            {!! Form::checkbox('visible_attribute_' . $choice_option->attribute_id, 1, $visible_attribute == 1, [
                                                                                'class' => 'form-check-input',
                                                                                'id' => 'visible_attribute_' . $choice_option->attribute_id,
                                                                            ]) !!}
                                                                            {!! Form::label('', __('Visible on the product page'), [
                                                                                'class' => 'form-check-label',
                                                                                'for' => 'visible_attribute_' . $choice_option->attribute_id,
                                                                            ]) !!}
                                                                        </div>
                                                                        <div style="margin-top: 9px;"></div>
                                                                        <div
                                                                            class="use_for_variation form-chec1k form-switch">

                                                                            {!! Form::hidden('for_variation_' . $choice_option->attribute_id, 0) !!}
                                                                            {!! Form::checkbox('for_variation_' . $choice_option->attribute_id, 1, $for_variation == 1, [
                                                                                'class' => 'form-check-input input-options enable_variation_' . $choice_option->attribute_id,
                                                                                'id' => 'for_variation_' . $choice_option->attribute_id,
                                                                                'data-enable-variation' => 'enable_variation_' . $choice_option->attribute_id,
                                                                                'data-id' => $choice_option->attribute_id,
                                                                            ]) !!}
                                                                            {!! Form::label('', __('Used for variations'), [
                                                                                'class' => 'form-check-label',
                                                                                'for' => 'for_variation_' . $choice_option->attribute_id,
                                                                            ]) !!}
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-7">
                                                                        <select
                                                                            name="attribute_options_{{ $choice_option->attribute_id }}[]"
                                                                            data-role="tagsinput"
                                                                            id="attribute_options_{{ $choice_option->attribute_id }}"
                                                                            multiple class="attribute_option_data">
                                                                            @foreach ($option as $key => $f)
                                                                                <option
                                                                                    @if (in_array($f, $get_datas)) selected @endif>
                                                                                    {{ $f }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </div>
                                        <div class="attribute_combination" id="attribute_combination">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12" style="display: none;" id="custom_value">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <div id="custom_field_repeater_basic">
                                                <!--begin::Form group-->
                                                <div class="form-group">
                                                    <div data-repeater-list="custom_field_repeater_basic">
                                                        @if (!empty($product->custom_field))
                                                            @foreach (json_decode($product->custom_field, true) as $item)
                                                                <div data-repeater-item>
                                                                    <div class="form-group row">
                                                                        <div class="col-md-6">
                                                                            {!! Form::label('', __('Custom Field'), ['class' => 'form-label']) !!}
                                                                            {!! Form::text('custom_field', $item['custom_field'], ['class' => 'form-control']) !!}
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            {!! Form::label('', __('Custom Value'), ['class' => 'form-label']) !!}
                                                                            {!! Form::text('custom_value', $item['custom_value'], [
                                                                                'id' => 'answer',
                                                                                'rows' => 4,
                                                                                'class' => 'form-control',
                                                                            ]) !!}

                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <a href="javascript:;" data-repeater-delete
                                                                                class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                                                <i class="la la-trash-o"></i>Delete
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div data-repeater-item>
                                                                <div class="form-group row">
                                                                    <div class="col-md-6">
                                                                        {!! Form::label('', __('Custom Field'), ['class' => 'form-label']) !!}
                                                                        {!! Form::text('custom_field', null, ['class' => 'form-control']) !!}
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        {!! Form::label('', __('Custom Value'), ['class' => 'form-label']) !!}
                                                                        {!! Form::text('custom_value', null, ['id' => 'answer', 'rows' => 2, 'class' => 'form-control']) !!}

                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <a href="javascript:;" data-repeater-delete
                                                                            class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                                            <i class="la la-trash-o"></i>Delete
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <!--end::Form group-->

                                                <!--begin::Form group-->
                                                <div class="form-group mt-2 mb-0">
                                                    <a href="javascript:;" data-repeater-create
                                                        class="btn btn-light-primary">
                                                        <i class="ti ti-plus"></i>
                                                    </a>
                                                </div>
                                                <!--end::Form group-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @stack('editwholesalefields')
                        @stack('EditProductPageSetting')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-script')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/repeater.js') }}"></script>
    <script src="{{ asset('assets/css/summernote/summernote-bs4.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('input').attr('readonly', true);
            $('select').attr('readonly', true);
            attribute_option_data();
            type();

            // tag
            $('.select2').select2({
                tags: true,
                createTag: function(params) {
                    var term = $.trim(params.term);
                    if (term === '') {
                        return null;
                    }
                    return {
                        id: term,
                        text: term,
                        newTag: true
                    };
                }
            });
            
            

            function type() {
                if ($('#enable_product_stock').is(":checked") == true) {
                    $("#options").show();
                    $('.stock_div_status').hide();
                } else {
                    $("#options").hide();
                    $('.stock_div_status').show();
                }
            }

            // prview video
            $("#preview_type").change(function() {
                $(this).find("option:selected").each(function() {
                    var optionValue = $(this).attr("value");
                    if (optionValue == 'Video Url') {

                        $('#video_url_div').removeClass('d-none');
                        $('#video_url_div').addClass('d-block');

                        $('#preview-iframe-div').addClass('d-none');
                        $('#preview-iframe-div').removeClass('d-block');

                        $('#preview-video-div').addClass('d-none');
                        $('#preview-video-div').removeClass('d-block');

                    } else if (optionValue == 'iFrame') {
                        $('#video_url_div').addClass('d-none');
                        $('#video_url_div').removeClass('d-block');

                        $('#preview-iframe-div').removeClass('d-none');
                        $('#preview-iframe-div').addClass('d-block');

                        $('#preview-video-div').addClass('d-none');
                        $('#preview-video-div').removeClass('d-block');

                    } else if (optionValue == 'Video File') {

                        $('#video_url_div').addClass('d-none');
                        $('#video_url_div').removeClass('d-block');

                        $('#preview-iframe-div').addClass('d-none');
                        $('#preview-iframe-div').removeClass('d-block');

                        $('#preview-video-div').removeClass('d-none');
                        $('#preview-video-div').addClass('d-block');
                    }
                });
            }).change();
        });
        $(document).ready(function() {
            if ($('#enable_custom_field').prop('checked') == true) {
                $('#custom_value').show();
            }

            $(document).on("change", "#enable_custom_field", function() {
                $('#custom_value').hide();
                if ($(this).prop('checked') == true) {
                    $('#custom_value').show();
                }
            });
        });
    </script>
    <script>
        //variation option on off
        if ($('.enable_product_variant').prop('checked') == true) {
            var inputValue = $('.attribute_option_data').val();
            if (inputValue != []) {
                var b = $('.attribute_option_data').closest('.parent-clase').find('.input-options');
                var enableVariationValue = b.data('enable-variation');
                var dataid = b.attr('data-id');
                $('.enable_variation_' + dataid).on('change', function() {
                    if ($('.enable_variation_' + dataid).prop('checked') == true) {
                        update_attribute();
                    } else {
                        $('.attribute_options_datas').empty();
                    }
                });

            }
        }
       
        // edit attribute data
        function attribute_option_data() {
            $.ajax({
                type: "PUT",
                url: '{{ route('products.attribute_combination_data') }}',
                data: $('#choice_form').serialize(),
                success: function(data) {
                    $('#loader').fadeOut();
                    $('.attribute_combination').html(data);
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }

        
        // variations form
        function update_attribute() {
            $.ajax({
                type: "PUT",
                url: '{{ route('products.attribute_combination_edit') }}',
                data: $('#choice_form').serialize(),
                success: function(data) {
                    $('#loader').fadeOut();
                    $('.attribute_combination').html(data);
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }

    </script>

    {{-- Dropzones  --}}
    <script>
        

        // Product Attribute
        $(document).ready(function() {
            $(document).on("change", ".product_attribute", function() {

                if ($('.enable_product_variant').prop('checked') == true) {
                    $(".use_for_variation").removeClass("d-none");
                } else {
                    $(".use_for_variation").addClass("d-none");
                }
            });

            if ($('#enable_product_variant').prop('checked') == true) {
                $('.product-price-div').hide();
                $('.product-stock-div').hide();
                $('.product-weight').hide();
            }
        });
    </script>
@endpush
