<div class="card" id="shopify_Setting">
    {{ Form::open(['route' => 'shopify.settings', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="card-header d-flex justify-content-between ">
        <h5 class=""> {{ __('Shopify Settings') }} </h5>
        {!! Form::hidden('shopify_setting_enabled', 'off') !!}
        <div class="form-check form-switch d-inline-block">
            {!! Form::checkbox(
            'shopify_setting_enabled',
            'on',
            isset($setting['shopify_setting_enabled']) && $setting['shopify_setting_enabled'] == 'on',
            [
            'class' => 'form-check-input',
            'id' => 'shopify_setting_enabled',
            ],
            ) !!}
            <label class="custom-control-label form-control-label" for="shopify_setting_enabled"></label>
        </div>

    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="form-group col-md-12">
                {{ Form::label('shopify_store_url', __('Shopify store url'), ['class' => 'form-label']) }}
                <div class="input-group">
                    {{ Form::text('shopify_store_url', !empty($setting['shopify_store_url']) ? $setting['shopify_store_url'] : '', ['class' => 'form-control', 'placeholder' => __('Shopify store url')]) }}
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">{{ __('.myshopify.com') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 form-group">
                {!! Form::label('shopify_access_token', __('Shopify access token'), ['class' => 'form-label']) !!}
                {!! Form::text(
                'shopify_access_token',
                !empty($setting['shopify_access_token']) ? $setting['shopify_access_token'] : '',
                [
                'class' => 'form-control',
                'placeholder' => 'Shopify Access Token',
                ],
                ) !!}
            </div>
            <div class="col-lg-12 text-end">
                <input type="submit" value="{{ __('Save Changes') }}" class="btn-submit btn btn-primary">
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>