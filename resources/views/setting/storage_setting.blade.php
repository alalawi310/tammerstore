 <!--Start Storage Setting-->
 <div class="card" id="Storage_Setting">
     <div class="card-header">
         <h5 class=""> {{ __('Storage Settings') }} </h5>
     </div>
     <div class="card-body p-4">
        {{ Form::model($setting, ['route' => 'storage.settings', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            <div class="d-flex gap-1 flex-wrap">
                <div class="">
                    <input type="radio" class="btn-check" name="storage_setting" id="local-outlined" autocomplete="off"
                        {{ isset($setting['storage_setting']) && $setting['storage_setting'] == 'local' ? 'checked' : '' }}
                        value="local" checked>
                    <label class="btn btn-outline-primary" for="local-outlined">{{ __('Local') }}</label>
                </div>

                <div class="">
                    <input type="radio" class="btn-check" name="storage_setting" id="s3-outlined" autocomplete="off"
                        {{ isset($setting['storage_setting']) && $setting['storage_setting'] == 's3' ? 'checked' : '' }}
                        value="s3">
                    <label class="btn btn-outline-primary" for="s3-outlined">
                        {{ __('AWS S3') }}</label>
                </div>

                <div class="">
                    <input type="radio" class="btn-check" name="storage_setting" id="wasabi-outlined" autocomplete="off"
                        {{ isset($setting['storage_setting']) && $setting['storage_setting'] == 'wasabi' ? 'checked' : '' }}
                        value="wasabi">
                    <label class="btn btn-outline-primary" for="wasabi-outlined">{{ __('Wasabi') }}</label>
                </div>
            </div>

            <div class="mt-2">
                {{-- local setting --}}
                <div
                    class="local-setting row {{ isset($setting['storage_setting']) && $setting['storage_setting'] == 'local' ? ' ' : 'd-none' }}">
                    <div class="form-group col-8 switch-width">
                        {{ Form::label('local_storage_validation', __('Only Upload Files'), ['class' => ' form-label']) }}
                        <select name="local_storage_validation[]" data-role="tagsinput" id="local_storage_validation"
                            multiple>
                            @foreach ($file_type as $f)
                            <option @if (in_array($f, explode(',', isset($setting['local_storage_validation']) ?
                                $setting['local_storage_validation'] : []))) selected @endif>
                                {{ $f }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-label"
                                for="local_storage_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                            <input type="number" name="local_storage_max_upload_size" class="form-control"
                                value="{{ isset($setting['local_storage_max_upload_size']) && !empty($setting['local_storage_max_upload_size']) ? $setting['local_storage_max_upload_size'] : '' }}"
                                placeholder="{{ __('Max upload size') }}">
                        </div>
                    </div>
                </div>

                {{-- S3 setting --}}

                <div
                    class="s3-setting row {{ isset($setting['storage_setting']) && $setting['storage_setting'] == 's3' ? ' ' : 'd-none' }}">
                    <div class=" row ">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="s3_key">{{ __('S3 Key') }}</label>
                                <input type="text" name="s3_key" class="form-control"
                                    value="{{ !isset($setting['s3_key']) || is_null($setting['s3_key']) ? '' : $setting['s3_key'] }}"
                                    placeholder="{{ __('S3 Key') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="s3_secret">{{ __('S3 Secret') }}</label>
                                <input type="text" name="s3_secret" class="form-control"
                                    value="{{ !isset($setting['s3_secret']) || is_null($setting['s3_secret']) ? '' : $setting['s3_secret'] }}"
                                    placeholder="{{ __('S3 Secret') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="s3_region">{{ __('S3 Region') }}</label>
                                <input type="text" name="s3_region" class="form-control"
                                    value="{{ !isset($setting['s3_region']) || is_null($setting['s3_region']) ? '' : $setting['s3_region'] }}"
                                    placeholder="{{ __('S3 Region') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="s3_bucket">{{ __('S3 Bucket') }}</label>
                                <input type="text" name="s3_bucket" class="form-control"
                                    value="{{ !isset($setting['s3_bucket']) || is_null($setting['s3_bucket']) ? '' : $setting['s3_bucket'] }}"
                                    placeholder="{{ __('S3 Bucket') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="s3_url">{{ __('S3 URL') }}</label>
                                <input type="text" name="s3_url" class="form-control"
                                    value="{{ !isset($setting['s3_url']) || is_null($setting['s3_url']) ? '' : $setting['s3_url'] }}"
                                    placeholder="{{ __('S3 URL') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="s3_endpoint">{{ __('S3 Endpoint') }}</label>
                                <input type="text" name="s3_endpoint" class="form-control"
                                    value="{{ !isset($setting['s3_endpoint']) || is_null($setting['s3_endpoint']) ? '' : $setting['s3_endpoint'] }}"
                                    placeholder="{{ __('S3 Bucket') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group switch-width">
                                {{ Form::label('s3_storage_validation', __('Only Upload Files'), ['class' => ' form-label']) }}
                                <select name="s3_storage_validation[]" data-role="tagsinput" id="s3_storage_validation"
                                    multiple>
                                    @foreach ($file_type as $f)
                                    <option @if (in_array($f, explode(',', isset($setting['s3_storage_validation']) ?
                                        $setting['s3_storage_validation'] : null))) selected @endif>
                                        {{ $f }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label"
                                    for="s3_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                                <input type="number" name="s3_max_upload_size" class="form-control"
                                    value="{{ !isset($setting['s3_max_upload_size']) || is_null($setting['s3_max_upload_size']) ? '' : $setting['s3_max_upload_size'] }}"
                                    placeholder="{{ __('Max upload size') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- wasabi setting --}}

                <div
                    class="wasabi-setting row {{ isset($setting['storage_setting']) && $setting['storage_setting'] == 'wasabi' ? ' ' : 'd-none' }}">
                    <div class=" row ">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="s3_key">{{ __('Wasabi Key') }}</label>
                                <input type="text" name="wasabi_key" class="form-control"
                                    value="{{ !isset($setting['wasabi_key']) || is_null($setting['wasabi_key']) ? '' : $setting['wasabi_key'] }}"
                                    placeholder="{{ __('Wasabi Key') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="s3_secret">{{ __('Wasabi Secret') }}</label>
                                <input type="text" name="wasabi_secret" class="form-control"
                                    value="{{ !isset($setting['wasabi_secret']) || is_null($setting['wasabi_secret']) ? '' : $setting['wasabi_secret'] }}"
                                    placeholder="{{ __('Wasabi Secret') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="s3_region">{{ __('Wasabi Region') }}</label>
                                <input type="text" name="wasabi_region" class="form-control"
                                    value="{{ !isset($setting['wasabi_region']) || is_null($setting['wasabi_region']) ? '' : $setting['wasabi_region'] }}"
                                    placeholder="{{ __('Wasabi Region') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="wasabi_bucket">{{ __('Wasabi Bucket') }}</label>
                                <input type="text" name="wasabi_bucket" class="form-control"
                                    value="{{ !isset($setting['wasabi_bucket']) || is_null($setting['wasabi_bucket']) ? '' : $setting['wasabi_bucket'] }}"
                                    placeholder="{{ __('Wasabi Bucket') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="wasabi_url">{{ __('Wasabi URL') }}</label>
                                <input type="text" name="wasabi_url" class="form-control"
                                    value="{{ !isset($setting['wasabi_url']) || is_null($setting['wasabi_url']) ? '' : $setting['wasabi_url'] }}"
                                    placeholder="{{ __('Wasabi URL') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="wasabi_root">{{ __('Wasabi Root') }}</label>
                                <input type="text" name="wasabi_root" class="form-control"
                                    value="{{ !isset($setting['wasabi_root']) || is_null($setting['wasabi_root']) ? '' : $setting['wasabi_root'] }}"
                                    placeholder="{{ __('Wasabi Bucket') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group switch-width">
                                {{ Form::label('wasabi_storage_validation', __('Only Upload Files'), ['class' => 'form-label']) }}

                                <select name="wasabi_storage_validation[]" data-role="tagsinput" id="wasabi_storage_validation"
                                    multiple>
                                    @foreach ($file_type as $f)
                                    <option @if (in_array( $f, explode(',', isset($setting['wasabi_storage_validation']) ?
                                        $setting['wasabi_storage_validation'] : null))) selected @endif>
                                        {{ $f }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="wasabi_root">{{ __('Max upload size ( In KB)') }}</label>
                                <input type="number" name="wasabi_max_upload_size" class="form-control"
                                    value="{{ !isset($setting['wasabi_max_upload_size']) || is_null($setting['wasabi_max_upload_size']) ? '' : $setting['wasabi_max_upload_size'] }}"
                                    placeholder="{{ __('Max upload size') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 text-end">
                <input type="submit" value="{{ __('Save Changes') }}" class="btn-submit btn btn-primary">
            </div>
        {!! Form::close() !!}
     </div>
 </div>
 <!--End Storage Setting-->