<!--Email Notification Setting-->
<div id="email-notification-settings" class="card">
    {{ Form::model($setting, ['route' => ['update.email.statue'], 'method' => 'post']) }}
    @csrf
    <div class="col-md-12">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <h5>{{ __('Email Notification Settings') }}</h5>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                @foreach ($emailTemplates as $EmailTemplate)
                <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                    <div class="list-group">
                        <div class="list-group-item form-switch form-switch-right">
                            <label class="form-label" style="margin-left:5%;">{{ $EmailTemplate->name }}</label>

                            <input class="form-check-input" name='{{ $EmailTemplate->id }}' id="email_tempalte_{{ $EmailTemplate->template->id }}" type="checkbox" @if($EmailTemplate->template->is_active == 1) checked="checked" @endif type="checkbox" value="1" />
                            <label class="form-check-label"
                                for="email_tempalte_{{ $EmailTemplate->template->id }}"></label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="card-footer p-0">
                <div class="col-sm-12 mt-3 px-2">
                    <div class="text-end">
                        <input class="btn btn-print-invoice  btn-primary " type="submit"
                            value="{{ __('Save Changes') }}">
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>
<!--End Email Notification Setting-->