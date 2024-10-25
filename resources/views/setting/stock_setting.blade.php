@php
$notifications = isset($setting['notification']) ? json_decode($setting['notification']) : [];
@endphp
<div class="card" id="Stock_Setting">
    {{ Form::model($setting, ['route' => 'stock.settings', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
    <div class="card-header">
        <h5 class="">
            {{ __('Stock Settings') }}
        </h5>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="form-group col-md-6">
                {{ Form::label('low_stock_threshold', __('Low Stock Threshold'), ['class' => 'form-label']) }}
                {{ Form::number('low_stock_threshold', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('out_of_stock_threshold', __('Out of Stock Threshold'), ['class' => 'form-label']) }}
                {{ Form::number('out_of_stock_threshold', null, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="row">
            @if (auth()->user()->type == 'admin')
                <div class="form-group col-md-6">
                    <div class="custom-control form-switch p-0">
                        <label class="form-check-label form-label"
                            for="stock_management">{{ __(' Enable Stock Management') }}</label><br>
                        <input type="checkbox" class="form-check-input"
                            data-toggle="switchbutton" data-onstyle="primary"
                            name="stock_management" id="stock_management"
                            {{ isset($setting['stock_management']) && $setting['stock_management'] == 'on' ? 'checked="checked"' : '' }}>
                    </div>
                </div>
            @endif
            @if (auth()->user()->type == 'admin')
                <div class="form-group col-md-6">
                    {{ Form::label('notifications', __('Notifications'), ['class' => 'form-label']) }}
                    <div class="form-group row">
                        <div class="col-9">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    value="enable_low_stock" id="low_stock"
                                    name="notification[]"
                                    {{ in_array('enable_low_stock', $notifications) ? 'checked' : '' }}>
                                <label class="form-check-label" for="low_stock">
                                    {{ __('Low stock notifications') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    value="enable_out_of_stock" id="out_of_stock"
                                    name="notification[]"
                                    {{ in_array('enable_out_of_stock', $notifications) ? 'checked' : '' }}>
                                <label class="form-check-label" for="out_of_stock">
                                    {{ __('Out of stock notifications') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="col-lg-12">
        <div class="text-end">
            <div class="card-footer">
                <div class="col-sm-12 px-2">
                    <div class="d-flex justify-content-end">
                        {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>