<!-- Cookie Setting -->
<div class="card" id="Cookie_Setting">
    {{ Form::model($setting, ['route' => 'cookie.setting', 'method' => 'post']) }}
    <div class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
        <h5>{{ __('Cookie Settings') }}</h5>

        <div class="d-flex align-items-center gap-5">
            <div class="d-flex align-items-center">
                {{ Form::label('enable_cookie', __('Enable cookie'), ['class' => 'col-form-label p-0 fw-bold me-3']) }}
                <div class="custom-control custom-switch" onclick="enablecookie()">
                    <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" name="enable_cookie"
                        class="form-check-input input-primary " id="enable_cookie"
                        {{ isset($setting['enable_cookie']) && $setting['enable_cookie'] == 'on' ? ' checked ' : '' }}>
                    <label class="custom-control-label mb-1" for="enable_cookie"></label>
                </div>
            </div>
        </div>
    </div>

    <div
        class="card-body cookieDiv {{ isset($setting['enable_cookie']) && $setting['enable_cookie'] == 'off' ? 'disabledCookie ' : '' }}">
        <div class="row ">
            <div class="col-md-6">
                <div class=" form-switch custom-switch-v1" id="cookie_log">
                    <input type="checkbox" name="cookie_logging" class="form-check-input input-primary cookie_setting"
                        id="cookie_logging"
                        {{ isset($setting['cookie_logging']) && $setting['cookie_logging'] == 'on' ? ' checked ' : '' }}>
                    <label class="form-check-label" for="cookie_logging">{{ __('Enable logging') }}</label>
                </div>
                <div class="form-group">
                    {{ Form::label('cookie_title', __('Cookie Title'), ['class' => 'col-form-label']) }}
                    {{ Form::text('cookie_title', null, ['class' => 'form-control cookie_setting']) }}
                </div>
                <div class="form-group ">
                    {{ Form::label('cookie_description', __('Cookie Description'), ['class' => ' form-label']) }}
                    {!! Form::textarea('cookie_description', null, ['class' => 'form-control cookie_setting', 'rows' => '3']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class=" form-switch custom-switch-v1 ">
                    <input type="checkbox" name="necessary_cookies" class="form-check-input input-primary"
                        id="necessary_cookies" checked onclick="return false">
                    <label class="form-check-label"
                        for="necessary_cookies">{{ __('Strictly necessary cookies') }}</label>
                </div>
                <div class="form-group ">
                    {{ Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' => 'col-form-label']) }}
                    {{ Form::text('strictly_cookie_title', null, ['class' => 'form-control cookie_setting']) }}
                </div>
                <div class="form-group ">
                    {{ Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => ' form-label']) }}
                    {!! Form::textarea('strictly_cookie_description', null, [
                        'class' => 'form-control cookie_setting ',
                        'rows' => '3',
                    ]) !!}
                </div>
            </div>
            <div class="col-12">
                <h5>{{ __('More Information') }}</h5>
            </div>
            <div class="col-md-6">
                <div class="form-group ">
                    {{ Form::label('more_information_description', __('Contact Us Description'), ['class' => 'col-form-label']) }}
                    {{ Form::text('more_information_description', null, ['class' => 'form-control cookie_setting']) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group ">
                    {{ Form::label('contactus_url', __('Contact Us URL'), ['class' => 'col-form-label']) }}
                    {{ Form::text('contactus_url', null, ['class' => 'form-control cookie_setting']) }}
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex align-items-center gap-2 flex-sm-column flex-lg-row justify-content-between">
        <div>
            @if (isset($setting['cookie_logging']) && $setting['cookie_logging'] == 'on')
                @if (Storage::exists('uploads/sample/cookie_data.csv'))
                    <label for="file" class="form-label">{{ __('Download cookie accepted data') }}</label>
                    <a href="{{ asset(Storage::url('uploads/sample/cookie_data.csv')) }}"
                        class="btn btn-primary mr-2 ">
                        <i class="ti ti-download"></i>
                    </a>
                @endif
            @endif
        </div>
        <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
    </div>
    {{ Form::close() }}
</div>
<!-- end Cookie Setting -->
