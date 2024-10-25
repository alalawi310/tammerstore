<div class="card" id="Tax_Option_Setting">
    {{ Form::open(['route' => 'tax-option.settings', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
        <div class="card-header">
            <div class="row g-0">
                <div class="col-6">
                    <h5> {{ __('Tax Option Settings') }} </h5>
                    <small>{{ __('Edit your Tax Option Settings') }}</small>
                </div>
            </div>
        </div>
        <div class="">
            <div class="row g-0">
                <div class="card-body table-border-style">
                    <div class="form-group row">
                        <label class="col-4 col-form-label">{{ __('Prices entered with tax') }}</label>
                        <div class="col-6">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input type" id="customRadio5" name="price_type" value="inclusive" {{ isset($tax_option['price_type']) && $tax_option['price_type'] == 'inclusive' ? 'checked="checked"' : '' }}>
                                <label class="custom-control-label form-label" for="customRadio5">{{__('Yes, I will enter prices inclusive of tax')}}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input type" id="customRadio6" name="price_type" value="exclusive" {{ isset($tax_option['price_type']) && $tax_option['price_type'] == 'exclusive' ? 'checked="checked"' : '' }}>
                                <label class="custom-control-label form-label" for="customRadio6">{{__('No, I will enter prices exclusive of tax')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group col-md-12">
                            {!! Form::label('', __('Rounding'), ['class' => 'form-label']) !!}
                        </div>
                        <div class="form-group  col-md-4">
                            {!! Form::label('', __('Tax Class'), ['class' => 'form-label']) !!}
                            {!! Form::select('tax_id', $taxes, isset($tax_option['tax_id']) ? $tax_option['tax_id'] : null, [
                                'class' => 'form-control',
                                'data-role' => 'tagsinput',
                                'id' => 'tax_id',
                                'data-val' => isset($tax_option['tax_id']) ? $tax_option['tax_id'] : null,
                            ]) !!}
                        </div>
                        <div class="form-group col-4">
                            {!! Form::label('', __('Display prices in the shop'), ['class' => 'form-label']) !!}
                            {!! Form::select(
                                'shop_price',
                                [
                                    'including' => __('Including Tax'),
                                    'exclusive' => __('Exclusive Tax'),
                                ],
                                $tax_option['shop_price'] ?? null,
                                [
                                    'class' => 'form-control select',
                                    'data-role' => 'tagsinput',
                                    'id' => 'shop_price',
                                    'name' => 'shop_price',
                                ],
                            ) !!}
                        </div>
                        <div class="form-group col-4">
                            {!! Form::label('', __('Display prices during cart and checkout'), ['class' => 'form-label']) !!}
                            {!! Form::select(
                                'checkout_price',
                                [
                                    'including' => __('Including Tax'),
                                    'exclusive' => __('Exclusive Tax'),
                                ],
                                $tax_option['checkout_price'] ?? null,
                                [
                                    'class' => 'form-control select',
                                    'data-role' => 'tagsinput',
                                    'id' => 'checkout_price',
                                    'name' => 'checkout_price',
                                ],
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group col-4">
                            {!! Form::label('', __('Display tax totals'), ['class' => 'form-label']) !!}
                            {!! Form::select(
                                'display_tax_option',
                                [
                                    'single_total' => __('As a single total'),
                                    'itemized' => __('Itemized'),
                                ],
                                $tax_option['display_tax_option'] ?? null,
                                [
                                    'class' => 'form-control select',
                                    'data-role' => 'tagsinput',
                                    'id' => 'display_tax_option',
                                    'name' => 'display_tax_option',
                                ],
                            ) !!}
                        </div>
                        <div class="form-group col-4">
                            {!! Form::label('price_suffix', __('Price Display Suffix'), ['class' => 'form-label']) !!}
                            {!! Form::text('price_suffix', !empty($tax_option['price_suffix']) ? $tax_option['price_suffix'] : '', [
                                'class' => 'form-control',
                                'placeholder' => 'Price Display Suffix',
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 text-end">
                            <input type="submit" value="{{ __('Save Changes') }}"
                                class="btn-submit btn btn-primary">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
</div>