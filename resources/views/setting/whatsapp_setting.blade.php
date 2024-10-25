
<div class="card" id="whatsapp_Setting">
    {{ Form::open(['route' => 'whatsapp.settings', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="card-header d-flex justify-content-between ">
        <div class="Whatsapp-title-div">
            <h5 class=""> {{ __('Whatsapp Settings') }} </h5>
            <small>{{ __('WhatsApp live support setting for customers') }}</small>
        </div>
        {!! Form::hidden('whatsapp_setting_enabled', 'off') !!}
        <div class="form-check form-switch d-inline-block">
            {!! Form::checkbox(
            'whatsapp_setting_enabled',
            'on',
            isset($setting['whatsapp_setting_enabled']) && $setting['whatsapp_setting_enabled'] === 'on',
            [
            'class' => 'form-check-input',
            'id' => 'whatsapp_setting_enabled',
            ],
            ) !!}
            <label class="custom-control-label form-control-label" for="whatsapp_setting_enabled"></label>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-lg-12 form-group">
                {!! Form::label('whatsapp_contact_number', __('Contact Number'), ['class' => 'form-label']) !!}
                {!! Form::text(
                'whatsapp_contact_number',
                !empty($setting['whatsapp_contact_number']) ? $setting['whatsapp_contact_number'] : '',
                [
                'class' => 'form-control',
                'placeholder' => 'XXXXXXXXXX',
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