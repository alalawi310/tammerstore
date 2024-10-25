<!--Start System Setting-->
<div class="card" id="System_Setting">
    <div class="card-header">
        <h5 class=""> {{ __('System Settings') }} </h5>
    </div>
    <div class="card-body p-4">
        {{ Form::open(['route' => 'system.settings', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
        {{ Form::model($setting, ['route' => 'system.settings', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            <div class="row">
                <div class="col-6">
                    <div class="form-group col switch-width">
                        {{ Form::label('default_language', __('Default Language'), ['class' => ' col-form-label']) }}
                        <div class="changeLanguage">
                            <select name="default_language" id="default_language" class="form-control" data-toggle="select">
                                @foreach (\App\Models\Utility::languages() as $code => $language)
                                <option @if (\Auth::user()['default_language']==$code) selected @endif value="{{ $code }}">
                                    {{ ucFirst($language) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-6">
                    <div class="form-group col switch-width">
                        {{ Form::label('defult_timezone', __('Default Timezone'), ['class' => 'col-form-label']) }}
                        {{ Form::select('defult_timezone', ['' => 'Select Timezone'] + $timezones, (isset($setting['defult_timezone']) ? $setting['defult_timezone'] : 'Asia/Kolkata'), ['id' => 'timezone', 'class' => 'form-control choices', 'searchEnabled' => 'true']) }}
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="site_date_format" class="form-label">{{ __('Date Format') }}</label>
                    <select type="text" name="site_date_format" class="form-control" data-toggle="select" id="site_date_format">
                        <option value="M j, Y" @if (isset($setting['site_date_format']) && $setting['site_date_format']=='M j, Y' ) selected="selected" @endif>
                            Jan 1,2015</option>
                        <option value="d-m-Y" @if (isset($setting['site_date_format']) && $setting['site_date_format']=='d-m-Y' ) selected="selected" @endif>
                            DD-MM-YYYY</option>
                        <option value="m-d-Y" @if (isset($setting['site_date_format']) && $setting['site_date_format']=='m-d-Y' ) selected="selected" @endif>
                            MM-DD-YYYY</option>
                        <option value="Y-m-d" @if (isset($setting['site_date_format']) && $setting['site_date_format']=='Y-m-d' ) selected="selected" @endif>
                            YYYY-MM-DD</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="site_time_format" class="form-label">{{ __('Time Format') }}</label>
                    <select type="text" name="site_time_format" class="form-control" data-toggle="select" id="site_time_format">
                        <option value="g:i A" @if (isset($setting['site_time_format']) && $setting['site_time_format']=='g:i A' ) selected="selected" @endif>
                            10:30 PM</option>
                        <option value="g:i a" @if (isset($setting['site_time_format']) && $setting['site_time_format']=='g:i a' ) selected="selected" @endif>
                            10:30 pm</option>
                        <option value="H:i" @if (isset($setting['site_time_format']) && $setting['site_time_format']=='H:i' ) selected="selected" @endif>
                            22:30</option>
                    </select>
                </div>

                <div class="text-end">
                    <input type="submit" value="{{ __('Save Changes') }}" class="btn-submit btn btn-primary">
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
<!--End System Setting-->