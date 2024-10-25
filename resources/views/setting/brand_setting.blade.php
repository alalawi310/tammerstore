<!--Start Brand Setting-->
<div class="card" id="Brand_Setting">
    <div class="card-header">
        <h5 class=""> {{ __('Brand Settings') }} </h5>
    </div>
    <div class="card-body p-4">
        {{ Form::open(['route' => 'business.settings', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
        {{ Form::model($setting, ['route' => 'business.settings', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            <div class="row">
                <div class="col-lg-4 col-sm-6 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Logo dark') }}</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="setting-card">
                                <div class="logo-content mt-4">
                                    <a href="#" target="_blank">
                                        <img src="{{ !empty($setting['logo_dark']) ? get_file($setting['logo_dark'], APP_THEME()) . '?timestamp=' . time() : asset(Storage::url('uploads/logo/')) . '/logo-dark.png' . '?timestamp=' . time() }}"
                                            class="img_setting" id="before">
                                    </a>
                                </div>
                                <div class="choose-files mt-5">
                                    <label for="company_logo">
                                        <div class=" bg-primary company_logo_update">
                                            <i class="ti ti-upload "></i>{{ __('Choose file here') }}
                                        </div>
                                        <input type="file" id="company_logo" data-filename="company_logo_update"
                                            name="logo_dark" class="form-control file"
                                            onchange=" document.getElementById('before').src = window.URL.createObjectURL(this.files[0])">
                                    </label>
                                </div>
                                @error('company_logo')
                                <div class="row">
                                    <span class="invalid-logo" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Logo Light') }}</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class=" setting-card">
                                <div class="logo-content mt-4">
                                    <a href="#" target="_blank">
                                        <img src="{{ !empty($setting['logo_light']) ? get_file($setting['logo_light'], APP_THEME()) . '?timestamp=' . time() : asset(Storage::url('uploads/logo/')) . '/logo-light.png' . '?timestamp=' . time() }}"
                                            class=" img_setting" id="logo-light">
                                    </a>
                                </div>
                                <div class="choose-files mt-5">
                                    <label for="company_logo_light">
                                        <div class=" bg-primary dark_logo_update">
                                            <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                        </div>
                                        <input type="file" class="form-control file" name="logo_light"
                                            id="company_logo_light" data-filename="dark_logo_update"
                                            onchange=" document.getElementById('logo-light').src = window.URL.createObjectURL(this.files[0])">
                                    </label>
                                </div>
                                @error('company_logo_light')
                                <div class="row">
                                    <span class="invalid-logo" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Favicon') }}</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class=" setting-card">
                                <div class="logo-content mt-4">
                                    <a href="#" target="_blank">
                                        <img src="{{ !empty($setting['favicon']) ? get_file($setting['favicon'], APP_THEME())  . '?timestamp=' . time() : asset(Storage::url('uploads/logo/')) . '/logo-sm.svg' . '?timestamp=' . time() }}"
                                            width="60px" height="40px" class=" img_setting favicon" id="faviCon">
                                    </a>
                                </div>
                                <div class="choose-files mt-5">
                                    <label for="company_favicon">
                                        <div class=" bg-primary company_favicon_update">
                                            <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                        </div>
                                        <input type="file" class="form-control file" id="company_favicon" name="favicon"
                                            data-filename="company_favicon_update"
                                            onchange=" document.getElementById('faviCon').src = window.URL.createObjectURL(this.files[0])">
                                    </label>
                                </div>
                                @error('logo')
                                <div class="row">
                                    <span class="invalid-logo" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    {{ Form::label('title_text', __('Title Text'), ['class' => 'form-label']) }}
                    {{ Form::text('title_text', null, ['class' => 'form-control', 'placeholder' => __('Title Text')]) }}
                    @error('title_text')
                    <span class="invalid-title_text" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    {{ Form::label('footer_text', __('Footer Text'), ['class' => 'form-label']) }}
                    {{ Form::text('footer_text', null, ['class' => 'form-control', 'placeholder' => __('Footer Text')]) }}
                    @error('footer_text')
                    <span class="invalid-footer_text" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group col-6 col-md-3">
                    <div class="custom-control form-switch p-0">
                        <label class="form-check-label form-label" for="SITE_RTL">{{ __('Enable RTL') }}</label><br>
                        <input type="checkbox" class="form-check-input" data-toggle="switchbutton" data-onstyle="primary"
                            name="SITE_RTL" id="SITE_RTL"
                            {{ isset($setting['SITE_RTL']) && $setting['SITE_RTL'] == 'on' ? 'checked="checked"' : '' }}>
                    </div>
                </div>
                @if (auth()->user() && auth()->user()->type == 'admin' && $plan && $plan->enable_tax == 'on')
                    <div class="form-group col-6 col-md-3">
                        <div class="custom-control form-switch p-0">
                            <label class="form-check-label form-label" for="Taxes">{{ __('Enable Taxes') }}</label><br>
                            <input type="checkbox" class="form-check-input" data-toggle="switchbutton" data-onstyle="primary"
                                name="taxes" id="Taxes"
                                {{ isset($setting['taxes']) && $setting['taxes'] == 'on' ? 'checked="checked"' : '' }}>
                        </div>
                    </div>
                @endif
                @if (auth()->user()->type == 'super admin')
                    <div class="form-group col-6 col-md-3">
                        <div class="custom-control form-switch p-0">
                            <label class="form-check-label form-label"
                                for="display_landing">{{ __('Enable Landing Page') }}</label><br>
                            <input type="checkbox" class="form-check-input" data-toggle="switchbutton" data-onstyle="primary"
                                name="display_landing" id="display_landing"
                                {{ isset($setting['display_landing']) && $setting['display_landing'] == 'on' ? 'checked="checked"' : '' }}>
                        </div>
                    </div>
                    <div class="form-group col-6 col-md-3">
                        <div class="custom-control form-switch p-0">
                            <label class="form-check-label form-label" for="SIGNUP">{{ __('Enable Sign-Up Page') }}</label><br>
                            <input type="checkbox" class="form-check-input" data-toggle="switchbutton" data-onstyle="primary"
                                name="SIGNUP" id="SIGNUP"
                                {{ isset($setting['SIGNUP']) && $setting['SIGNUP'] == 'on' ? 'checked="checked"' : '' }}>
                        </div>
                    </div>
                @endif
                @if (auth()->user()->type == 'super admin')
                    <div class="form-group col-6 col-md-3">
                        <div class="custom-control form-switch p-0">
                            <label class="form-check-label form-label"
                                for="email_verification">{{ __('Enable Email Verification') }}</label><br>
                            <input type="checkbox" name="email_verification" class="form-check-input" id="email_verification"
                                data-toggle="switchbutton"
                                {{ isset($setting['email_verification']) && $setting['email_verification'] == 'on' ? 'checked="checked"' : '' }}
                                data-onstyle="primary">
                        </div>
                    </div>
                @endif

                <div class="setting-card setting-logo-box p-3">
                    <div class="row">
                        <h5>{{ __('Theme Customizer') }}</h5>
                        <div class="col-md-4 col-12 my-auto">
                            <div class="inner-div">
                                <h6 class="mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"
                                        fill="none">
                                        <path
                                            d="M8.5 17C3.79231 17 0 13.2077 0 8.5C0 3.79231 3.79231 0 8.5 0C13.2077 0 17 3.79231 17 8.5C17 10.3308 15.7577 13.0769 12.4231 13.0769C11.8346 13.0769 10.7231 13.3385 10.3962 13.9923C10.2 14.3192 10.2654 14.5808 10.3308 14.7115C10.3962 14.9077 10.5923 15.0385 10.7231 15.1038L10.7885 15.1692C11.2462 15.4962 11.3115 15.8885 11.1808 16.15C11.05 16.7385 10.3308 17 8.5 17ZM8.5 1.30769C4.51154 1.30769 1.30769 4.51154 1.30769 8.5C1.30769 12.4885 4.51154 15.6923 8.5 15.6923H9.41538C9.35 15.5615 9.28462 15.4962 9.21923 15.3654C9.02308 14.9731 8.82692 14.3192 9.21923 13.4692C9.87308 12.1615 11.5731 11.7692 12.4231 11.7692C15.6269 11.7692 15.6923 8.63077 15.6923 8.5C15.6923 4.51154 12.4885 1.30769 8.5 1.30769Z"
                                            fill="#6FD943" />
                                        <path
                                            d="M3.26904 6.86358C3.26904 7.38666 3.72674 7.84435 4.24981 7.84435C4.77289 7.84435 5.23058 7.38666 5.23058 6.86358C5.23058 6.3405 4.77289 5.88281 4.24981 5.88281C3.72674 5.88281 3.26904 6.3405 3.26904 6.86358Z"
                                            fill="#013D29" />
                                        <path
                                            d="M13.7306 6.86358C13.7306 7.38666 13.2729 7.84435 12.7498 7.84435C12.2267 7.84435 11.769 7.38666 11.769 6.86358C11.769 6.3405 12.2267 5.88281 12.7498 5.88281C13.2729 5.88281 13.7306 6.3405 13.7306 6.86358Z"
                                            fill="#013D29" />
                                        <path
                                            d="M5.88452 4.2503C5.88452 4.77338 6.34221 5.23107 6.86529 5.23107C7.38837 5.23107 7.84606 4.77338 7.84606 4.2503C7.84606 3.72722 7.38837 3.26953 6.86529 3.26953C6.34221 3.26953 5.88452 3.72722 5.88452 4.2503Z"
                                            fill="#013D29" />
                                        <path
                                            d="M11.1153 4.2503C11.1153 4.77338 10.6577 5.23107 10.1346 5.23107C9.6115 5.23107 9.15381 4.77338 9.15381 4.2503C9.15381 3.72722 9.6115 3.26953 10.1346 3.26953C10.6577 3.26953 11.1153 3.72722 11.1153 4.2503Z"
                                            fill="#013D29" />
                                    </svg>
                                    {{ __('Primary Color Settings') }}
                                </h6>
                                <hr class="my-2" />
                                <div class="color-wrp">
                                    <div class="theme-color themes-color">
                                        <a href="#!"
                                            class="themes-color-change {{ isset($setting['color']) && $setting['color'] == 'theme-1' ? 'active_color' : '' }}"
                                            data-value="theme-1" onclick="check_theme('theme-1')"></a>
                                        <input type="radio" class="theme_color" name="color" value="theme-1"
                                            style="display: none;">

                                        <a href="#!"
                                            class="themes-color-change {{ isset($setting['color']) && $setting['color'] == 'theme-2' ? 'active_color' : '' }}"
                                            data-value="theme-2" onclick="check_theme('theme-2')"></a>
                                        <input type="radio" class="theme_color" name="color" value="theme-2"
                                            style="display: none;">

                                        <a href="#!"
                                            class="themes-color-change {{ isset($setting['color']) && $setting['color'] == 'theme-3' ? 'active_color' : '' }}"
                                            data-value="theme-3" onclick="check_theme('theme-3')"></a>
                                        <input type="radio" class="theme_color" name="color" value="theme-3"
                                            style="display: none;">

                                        <a href="#!"
                                            class="themes-color-change {{ isset($setting['color']) && $setting['color'] == 'theme-4' ? 'active_color' : '' }}"
                                            data-value="theme-4" onclick="check_theme('theme-4')"></a>
                                        <input type="radio" class="theme_color" name="color" value="theme-4"
                                            style="display: none;">

                                        <a href="#!"
                                            class="themes-color-change {{ isset($setting['color']) && $setting['color'] == 'theme-5' ? 'active_color' : '' }}"
                                            data-value="theme-5" onclick="check_theme('theme-5')"></a>
                                        <input type="radio" class="theme_color" name="color" value="theme-5"
                                            style="display: none;">

                                        <a href="#!"
                                            class="themes-color-change {{ isset($setting['color']) && $setting['color'] == 'theme-6' ? 'active_color' : '' }}"
                                            data-value="theme-6" onclick="check_theme('theme-6')"></a>
                                        <input type="radio" class="theme_color" name="color" value="theme-6"
                                            style="display: none;">

                                        <a href="#!"
                                            class="themes-color-change {{ isset($setting['color']) && $setting['color'] == 'theme-7' ? 'active_color' : '' }}"
                                            data-value="theme-7" onclick="check_theme('theme-7')"></a>
                                        <input type="radio" class="theme_color" name="color" value="theme-7"
                                            style="display: none;">

                                        <a href="#!"
                                            class="themes-color-change {{ isset($setting['color']) && $setting['color'] == 'theme-8' ? 'active_color' : '' }}"
                                            data-value="theme-8" onclick="check_theme('theme-8')"></a>
                                        <input type="radio" class="theme_color" name="color" value="theme-8"
                                            style="display: none;">

                                        <a href="#!"
                                            class="themes-color-change {{ isset($setting['color']) && $setting['color'] == 'theme-9' ? 'active_color' : '' }}"
                                            data-value="theme-9" onclick="check_theme('theme-9')"></a>
                                        <input type="radio" class="theme_color" name="color" value="theme-9"
                                            style="display: none;">

                                        <a href="#!"
                                            class="themes-color-change {{ isset($setting['color']) && $setting['color'] == 'theme-10' ? 'active_color' : '' }}"
                                            data-value="theme-10" onclick="check_theme('theme-10')"></a>
                                        <input type="radio" class="theme_color" name="color" value="theme-10"
                                            style="display: none;">

                                    </div>
                                    <div class="color-picker-wrp ">
                                        <input type="color" value="{{ $setting['color'] ?? '' }}"
                                            class="colorPicker {{ isset($flag) && $flag == 'true' ? 'active_color' : '' }}"
                                            name="custom_color" id="color-picker">
                                        <input type='hidden' name="color_flag"
                                            value={{ isset($flag) && $flag == 'true' ? 'true' : 'false' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-12 my-auto mt-2">
                            <div class="inner-div">
                                <h6 class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"
                                        fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M1.96154 1.30769C1.60043 1.30769 1.30769 1.60043 1.30769 1.96154V15.0385C1.30769 15.3996 1.60043 15.6923 1.96154 15.6923H15.0385C15.3996 15.6923 15.6923 15.3996 15.6923 15.0385V1.96154C15.6923 1.60043 15.3996 1.30769 15.0385 1.30769H1.96154ZM0 1.96154C0 0.878211 0.878211 0 1.96154 0H15.0385C16.1218 0 17 0.878211 17 1.96154V15.0385C17 16.1218 16.1218 17 15.0385 17H1.96154C0.878211 17 0 16.1218 0 15.0385V1.96154Z"
                                            fill="#6FD943" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M5.01273 0C5.37384 0 5.66658 0.29274 5.66658 0.653846V16.3462C5.66658 16.7073 5.37384 17 5.01273 17C4.65163 17 4.35889 16.7073 4.35889 16.3462V0.653846C4.35889 0.29274 4.65163 0 5.01273 0Z"
                                            fill="#013D29" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.05139 16.3453C3.05139 15.9842 3.34413 15.6914 3.70524 15.6914H6.32062C6.68173 15.6914 6.97447 15.9842 6.97447 16.3453C6.97447 16.7063 6.68173 16.9991 6.32062 16.9991H3.70524C3.34413 16.9991 3.05139 16.7063 3.05139 16.3453Z"
                                            fill="#013D29" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.05139 0.653846C3.05139 0.29274 3.34413 0 3.70524 0H6.32062C6.68173 0 6.97447 0.29274 6.97447 0.653846C6.97447 1.01495 6.68173 1.30769 6.32062 1.30769H3.70524C3.34413 1.30769 3.05139 1.01495 3.05139 0.653846Z"
                                            fill="#013D29" />
                                    </svg>
                                    {{ __('Sidebar Settings') }}
                                </h6>
                                <hr class="my-2" />
                                <div class="form-check form-switch mt-2">
                                    <input type="checkbox" class="form-check-input" id="cust_theme_bg" name="cust_theme_bg"
                                        {{ isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on' ? 'checked="checked"' : '' }}>
                                    <label class="form-check-label f-w-600 pl-1"
                                        for="cust_theme_bg">{{ __('Transparent layout') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-12 my-auto mt-2">
                            <div class="inner-div">
                                <h6 class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15"
                                        fill="none">
                                        <path
                                            d="M4.95833 12.0417C3.6433 12.0417 2.38213 11.5193 1.45226 10.5895C0.522394 9.65959 0 8.39842 0 7.08338C0 5.76835 0.522394 4.50718 1.45226 3.57731C2.38213 2.64744 3.6433 2.12505 4.95833 2.12505C5.67582 2.12179 6.38495 2.27911 7.03375 2.58547L8.40792 3.23713L7.02667 3.86755C6.41232 4.15102 5.89202 4.60454 5.52734 5.17444C5.16266 5.74435 4.96885 6.40679 4.96885 7.08338C4.96885 7.75998 5.16266 8.42242 5.52734 8.99232C5.89202 9.56222 6.41232 10.0157 7.02667 10.2992L8.40792 10.9296L7.03375 11.5813C6.38495 11.8877 5.67582 12.045 4.95833 12.0417ZM4.95833 3.54172C4.49324 3.54637 4.03361 3.64258 3.60569 3.82486C3.17778 4.00715 2.78996 4.27193 2.46437 4.60409C1.80682 5.27492 1.44269 6.17949 1.45208 7.1188C1.46148 8.05811 1.84362 8.95521 2.51446 9.61276C3.18529 10.2703 4.08986 10.6344 5.02917 10.625C4.55799 10.1634 4.18366 9.61235 3.92812 9.0042C3.67257 8.39606 3.54094 7.74304 3.54094 7.08338C3.54094 6.42373 3.67257 5.77071 3.92812 5.16256C4.18366 4.55442 4.55799 4.00338 5.02917 3.54172H4.95833Z"
                                            fill="#013D29" />
                                        <path
                                            d="M8.49997 12.0426C7.78248 12.0459 7.07335 11.8885 6.42455 11.5822C5.3972 11.1097 4.56222 10.3002 4.0581 9.28797C3.55397 8.27576 3.41097 7.12164 3.65283 6.017C3.89468 4.91236 4.50685 3.92357 5.38782 3.21462C6.26879 2.50567 7.36562 2.11914 8.49643 2.11914C9.62723 2.11914 10.7241 2.50567 11.605 3.21462C12.486 3.92357 13.0982 4.91236 13.34 6.017C13.5819 7.12164 13.4389 8.27576 12.9348 9.28797C12.4306 10.3002 11.5957 11.1097 10.5683 11.5822C9.9212 11.8862 9.21491 12.0434 8.49997 12.0426ZM8.49997 3.5426C7.99353 3.53785 7.49248 3.64677 7.03372 3.86135C6.29797 4.19695 5.69923 4.77423 5.337 5.49724C4.97478 6.22025 4.87089 7.04545 5.04261 7.83568C5.21432 8.62591 5.6513 9.33358 6.28091 9.84107C6.91052 10.3486 7.69484 10.6253 8.50351 10.6253C9.31218 10.6253 10.0965 10.3486 10.7261 9.84107C11.3557 9.33358 11.7927 8.62591 11.9644 7.83568C12.1361 7.04545 12.0322 6.22025 11.67 5.49724C11.3078 4.77423 10.709 4.19695 9.9733 3.86135C9.51286 3.64433 9.00894 3.53531 8.49997 3.5426Z"
                                            fill="#6FD943" />
                                        <path
                                            d="M14.875 6.375H13.4583C13.0671 6.375 12.75 6.69213 12.75 7.08333C12.75 7.47453 13.0671 7.79167 13.4583 7.79167H14.875C15.2662 7.79167 15.5833 7.47453 15.5833 7.08333C15.5833 6.69213 15.2662 6.375 14.875 6.375Z"
                                            fill="#6FD943" />
                                        <path
                                            d="M12.0404 1.83529L11.0387 2.83702C10.7621 3.11364 10.7621 3.56214 11.0387 3.83876C11.3153 4.11538 11.7638 4.11538 12.0404 3.83876L13.0422 2.83702C13.3188 2.5604 13.3188 2.11191 13.0422 1.83529C12.7656 1.55867 12.3171 1.55867 12.0404 1.83529Z"
                                            fill="#6FD943" />
                                        <path
                                            d="M9.20829 0.708333C9.20829 0.317132 8.89116 0 8.49996 0C8.10876 0 7.79163 0.317132 7.79163 0.708333V2.125C7.79163 2.5162 8.10876 2.83333 8.49996 2.83333C8.89116 2.83333 9.20829 2.5162 9.20829 2.125V0.708333Z"
                                            fill="#6FD943" />
                                        <path
                                            d="M9.20829 12.0423C9.20829 11.6511 8.89116 11.334 8.49996 11.334C8.10876 11.334 7.79163 11.6511 7.79163 12.0423V13.459C7.79163 13.8502 8.10876 14.1673 8.49996 14.1673C8.89116 14.1673 9.20829 13.8502 9.20829 13.459V12.0423Z"
                                            fill="#6FD943" />
                                        <path
                                            d="M12.0362 10.3312C11.7595 10.0545 11.3111 10.0545 11.0344 10.3312C10.7578 10.6078 10.7578 11.0563 11.0344 11.3329L12.0362 12.3346C12.3128 12.6113 12.7613 12.6113 13.0379 12.3346C13.3145 12.058 13.3145 11.6095 13.0379 11.3329L12.0362 10.3312Z"
                                            fill="#6FD943" />
                                    </svg>
                                    {{ __('Layout Settings') }}
                                </h6>
                                <hr class="my-2" />
                                <div class="form-check form-switch mt-2">
                                    <input type="checkbox" class="form-check-input" id="cust-darklayout" name="cust_darklayout"
                                        {{ isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on' ? 'checked="checked"' : '' }} />
                                    <label class="form-check-label f-w-600 pl-1"
                                        for="cust-darklayout">{{ __('Dark Layout') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 text-end mt-3">
                        <input type="submit" value="{{ __('Save Changes') }}" class="btn-submit btn btn-primary">
                    </div>
                </div>
            
            </div>
        {!! Form::close() !!}
    </div>
</div>
<!--End Brand Setting-->