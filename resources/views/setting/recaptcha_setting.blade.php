<!-- Recaptcha_Settings -->
<div class="card" id="Recaptcha_Settings">
    <form method="POST" action="{{ route('recaptcha.settings') }}" accept-charset="UTF-8">
        @csrf
        <div class="card-header">
            <div class="row gy-2">
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <h5 class="">{{ __('ReCaptcha Settings') }}</h5><small class="text-secondary font-weight-bold"><a
                            href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                            target="_blank" class="text-blue">
                            <small>({{ __('How to Get Google reCaptcha Site and Secret key') }})</small>
                        </a></small>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 text-sm-end">
                    <div class="col switch-width">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" class=""
                                value="yes" name="recaptcha_module" id="recaptcha_module"
                                {{ !empty($setting['RECAPTCHA_MODULE']) && $setting['RECAPTCHA_MODULE'] == 'yes' ? 'checked="checked"' : '' }}>
                            <label class="custom-control-label form-control-label px-2"
                                for="recaptcha_module"></label><br>
                            <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                target="_blank" class="text-blue">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row ">
                <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                    <label for="google_recaptcha_version" class="form-label">{{ __('Google Recaptcha Version') }}</label>

                    <select id="google_recaptcha_version" class="form-control choices" searchenabled="true" name="google_recaptcha_version">
                        <option value="v2" {{ (isset($setting['NOCAPTCHA_VERSON']) && ($setting['NOCAPTCHA_VERSON'] == 'v2')) ? 'selected' : '' }} >{{ __('v2') }}</option>
                        <option value="v3" {{ (isset($setting['NOCAPTCHA_VERSON']) && ($setting['NOCAPTCHA_VERSON'] == 'v3')) ? 'selected' : '' }}>{{ __('v3') }}</option>
                    </select>
                </div>
                
                <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                    <label for="google_recaptcha_key" class="form-label">{{ __('Google Recaptcha Key') }}</label>
                    <input class="form-control" placeholder="{{ __('Enter Google Recaptcha Key') }}"
                        name="google_recaptcha_key" type="text" value="{{ $setting['NOCAPTCHA_SITEKEY'] ?? '' }}"
                        id="google_recaptcha_key">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                    <label for="google_recaptcha_secret" class="form-label">{{ __('Google Recaptcha Secret') }}</label>
                    <input class="form-control " placeholder="{{ __('Enter Google Recaptcha Secret') }}"
                        name="google_recaptcha_secret" type="text" value="{{ $setting['NOCAPTCHA_SECRET'] ?? '' }}"
                        id="google_recaptcha_secret">
                </div>
            </div>
            <div class="card-footer p-0">
                <div class="col-sm-12 mt-3 px-2">
                    <div class="text-end">
                        {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- End Recaptcha_Settings -->