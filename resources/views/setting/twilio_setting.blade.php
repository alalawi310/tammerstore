<div class="card" id="twilio_setting">
    {{ Form::open(['route' => 'twilio.settings', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="card-header d-flex justify-content-between ">
        <h5> {{ __('Twilio Settings') }} </h5>
        {!! Form::hidden('twilio_setting_enabled', 'off') !!}
        <div class="form-check form-switch d-inline-block">
            {!! Form::checkbox(
            'twilio_setting_enabled',
            'on',
            isset($setting['twilio_setting_enabled']) && $setting['twilio_setting_enabled'] === 'on',
            [
            'class' => 'form-check-input',
            'id' => 'twilio_setting_enabled',
            ],
            ) !!}
            <label class="custom-control-label form-control-label" for="twilio_setting_enabled"></label>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-lg-6 form-group">
                {!! Form::label('twilio_sid', __('Twilio SID'), ['class' => 'form-label']) !!}
                {!! Form::text('twilio_sid', !empty($setting['twilio_sid']) ? $setting['twilio_sid'] : '', [
                'class' => 'form-control',
                'placeholder' => 'Twilio SID',
                ]) !!}
            </div>
            <div class="col-lg-6 form-group">
                {!! Form::label('twilio_token', __('Twilio Token'), ['class' => 'form-label']) !!}
                {!! Form::text('twilio_token', !empty($setting['twilio_token']) ? $setting['twilio_token'] : '', [
                'class' => 'form-control',
                'placeholder' => 'Twilio Token',
                ]) !!}
            </div>
            <div class="col-lg-6 form-group">
                {!! Form::label('twilio_from', __('Twilio From'), ['class' => 'form-label']) !!}
                {!! Form::text('twilio_from', !empty($setting['twilio_from']) ? $setting['twilio_from'] : '', [
                'class' => 'form-control',
                'placeholder' => 'twilio consumer secret',
                ]) !!}
            </div>

            <div class="col-lg-6 form-group">
                {!! Form::label('twilio_notification_number', __('Notification Number'), ['class' => 'form-label'])
                !!}
                {!! Form::text(
                'twilio_notification_number',
                !empty($setting['twilio_notification_number']) ? $setting['twilio_notification_number'] : '',
                ['class' => 'form-control', 'placeholder' => 'twilio consumer secret'],
                ) !!}
                <small>* {{ __('Use country code with your number') }} *</small>
            </div>

            <div class="col-lg-12 text-end">
                <input type="submit" value="{{ __('Save Changes') }}" class="btn-submit btn btn-primary">
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>