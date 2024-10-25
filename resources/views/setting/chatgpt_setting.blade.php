<div class="card" id="Chatgpt_Setting">
    {{ Form::model($setting, ['route' => 'chatgpt.setting', 'method' => 'post']) }}
    @csrf
    <div class="card-header">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <h5>{{ __('Chat GPT Key Settings') }}</h5>
                <small class="text-muted">{{ __('Edit your key details') }}</small>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mt-2">
            <div class="form-group">
                <div class="field_wrapper">
                    @if (isset($ai_key_settings) && count($ai_key_settings) > 0)
                        <?php $i = 1; ?>
                        @foreach ($ai_key_settings as $key_data)
                            <div class="d-flex gap-1 mb-4">
                                <input type="text" class="form-control" name="api_key[]"
                                    value="{{ $key_data->key }}" />
                                @if ($i == 1)
                                    <a href="javascript:void(0);" class="add_button btn btn-primary"
                                        title="Add field"><i class="ti ti-plus"></i></a>
                                @else
                                    <a href="javascript:void(0);" class="remove_button btn btn-danger bg-danger"><i
                                            class="ti ti-trash"></i></a>
                                @endif
                            </div>
                            <?php $i++; ?>
                        @endforeach
                    @else
                        <div class="d-flex gap-1 mb-4">
                            <input type="text" class="form-control " name="api_key[]" value="" />

                            <a href="javascript:void(0);" class="add_button btn btn-primary" title="Add field"><i
                                    class="ti ti-plus"></i></a>

                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                {{ Form::text('chatgpt_key', null, ['class' => 'form-control', 'placeholder' => __('Enter Chatgpt Key Here')]) }}
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
    </div>
    {{ Form::close() }}
</div>
