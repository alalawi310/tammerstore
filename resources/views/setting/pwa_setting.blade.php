<div class="card" id="Pwa_Setting">
    {{ Form::model($store_settings, ['route' => ['pwa.setting', $store_settings->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
    <div class="card-header">
        <div class="row ">
            <div class="col-6">
                <h5>{{ __('PWA Settings') }}</h5>
                <small></small>
            </div>
            <div class="col-6 text-end">
                <div class="custom-control form-switch ps-0">
                    <input type="checkbox" class="form-check-input enable_pwa_store" name="pwa_store" id="pwa_store"
                        {{ ($store_settings->enable_pwa_store ?? '') == 'on' ? 'checked=checked' : '' }}>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="form-group col-md-6 pwa_is_enable">
                {{ Form::label('pwa_app_title', __('App Title'), ['class' => 'form-label']) }}
                {{ Form::text('pwa_app_title', !empty($pwa_data->name) ? $pwa_data->name : '', ['class' => 'form-control', 'placeholder' => __('App Title')]) }}
            </div>

            <div class="form-group col-md-6 pwa_is_enable">
                {{ Form::label('pwa_app_name', __('App Name'), ['class' => 'form-label']) }}
                {{ Form::text('pwa_app_name', !empty($pwa_data->short_name) ? $pwa_data->short_name : '', ['class' => 'form-control', 'placeholder' => __('App Name')]) }}
            </div>

            <div class="form-group col-md-6 pwa_is_enable">
                {{ Form::label('pwa_app_background_color', __('App Background Color'), ['class' => 'form-label']) }}
                {{ Form::color('pwa_app_background_color', !empty($pwa_data->background_color) ? $pwa_data->background_color : '', ['class' => 'form-control color-picker', 'placeholder' => __('18761234567')]) }}
            </div>

            <div class="form-group col-md-6 pwa_is_enable">
                {{ Form::label('pwa_app_theme_color', __('App Theme Color'), ['class' => 'form-label']) }}
                {{ Form::color('pwa_app_theme_color', !empty($pwa_data->theme_color) ? $pwa_data->theme_color : '', ['class' => 'form-control color-picker', 'placeholder' => __('18761234567')]) }}
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
        </div>
    </div>
    {{ Form::close() }}
</div>