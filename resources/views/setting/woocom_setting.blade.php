<div class="card" id="Woocommerce_Setting">
    {{ Form::open(['route' => 'woocommerce.settings', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="card-header d-flex justify-content-between ">
        <h5 class=""> {{ __('Woocommerce Settings') }} </h5>
        {!! Form::hidden('woocommerce_setting_enabled', 'off') !!}
        <div class="form-check form-switch d-inline-block">
            {!! Form::checkbox(
            'woocommerce_setting_enabled',
            'on',
            isset($setting['woocommerce_setting_enabled']) && $setting['woocommerce_setting_enabled'] == 'on',
            [
            'class' => 'form-check-input',
            'id' => 'woocommerce_setting_enabled',
            ],
            ) !!}
            <label class="custom-control-label form-control-label" for="woocommerce_setting_enabled"></label>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-lg-12 form-group">
                {!! Form::label('woocommerce_store_url', __('Store Url'), ['class' => 'form-label']) !!}
                {!! Form::text(
                'woocommerce_store_url',
                !empty($setting['woocommerce_store_url']) ? $setting['woocommerce_store_url'] : '',
                [
                'class' => 'form-control',
                'placeholder' => 'Woocommerce store url',
                ],
                ) !!}
            </div>
            <div class="col-lg-12 form-group">
                {!! Form::label('woocommerce_consumer_key', __('Consumer Key'), ['class' => 'form-label']) !!}
                {!! Form::text(
                'woocommerce_consumer_key',
                !empty($setting['woocommerce_consumer_key']) ? $setting['woocommerce_consumer_key'] : '',
                [
                'class' => 'form-control',
                'placeholder' => 'Woocommerce consumer key',
                ],
                ) !!}
            </div>
            <div class="col-lg-12 form-group">
                {!! Form::label('woocommerce_consumer_secret', __('Consumer Secret'), ['class' => 'form-label']) !!}
                {!! Form::text(
                'woocommerce_consumer_secret',
                !empty($setting['woocommerce_consumer_secret']) ? $setting['woocommerce_consumer_secret'] : '',
                ['class' => 'form-control', 'placeholder' => 'Woocommerce consumer secret'],
                ) !!}
            </div>

            <div class="col-lg-12 text-end">
                <input type="submit" value="{{ __('Save Changes') }}" class="btn-submit btn btn-primary">
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>