<div class="tab-pane fade show active" id="eco-1" role="tabpanel" aria-labelledby="pills-home-tab">
    <!--Start Email Setting-->
    <div class="card" id="email-settings">
        <div class="email-setting-wrap ">
            {{ Form::open(['route' => 'email.settings', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
            @csrf
            <input type="hidden" class="email">
            <div class="card-header">
                <h3 class="h5">{{ __('Email Settings') }}</h3>
            </div>
            <div class="card-body pb-0">
                <div class="d-flex">
                    <div class="col-sm-6 col-12">

                        <div class="form-group col switch-width">
                            {{ Form::label('email_setting', __('Email Setting'), ['class' => ' col-form-label']) }}

                            {{ Form::select('email_setting', $email_setting ?? [], isset($setting['email_setting']) ? $setting['email_setting'] : $get_setting, ['id' => 'email_setting', 'class' => 'form-control choices', 'searchEnabled' => 'true']) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12" id="getfields">
                    </div>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-between flex-wrap " style="gap:10px">

                <input type="hidden" name="custom_email" id="custom_email"
                    value="{{ isset($settings['email_setting']) ? $settings['email_setting'] : $get_setting }}">

                <div class="text-start ">
                    <a href="#" data-ajax-popup1="true" data-size="md" data-title="{{ __('Send Test Mail') }}"
                        data-url="{{ route('email.test') }}" data-toggle="tooltip" title="{{ __('Send Test Mail') }}"
                        class="btn btn-print-invoice  btn-primary m-r-10 send_email">
                        {{ __('Send Test Mail') }}
                    </a>
                </div>

                <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <!--End Email Setting-->
</div>

@push('custom-script')
<script>
    $(document).ready(function() {
        var emailSetting = $('#email_setting').val();
        getEmailSettingFields(emailSetting);

    });
    
</script>
@endpush