
<div id="whatsapp-notification-settings" class="card">
    {{ Form::model($setting, ['route' => 'whatsapp-notification', 'method' => 'post']) }}
    @csrf
    <div class="col-md-12">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <h5>{{ __('WhatsApp Business API') }}</h5>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 form-group">
                    {!! Form::label('whatsapp_phone_number_id', __('WhatsApp Phone number ID'), ['class' =>
                    'form-label']) !!}
                    {!! Form::text('whatsapp_phone_number_id', $setting['whatsapp_phone_number_id'] ?? '', [
                    'class' => 'form-control',
                    'placeholder' => 'WhatsApp Phone number ID',
                    'id' => 'whatsapp_phone_number_id',
                    ]) !!}
                </div>
                <div class="col-lg-6 form-group">
                    {!! Form::label('whatsapp_access_token', __('WhatsApp Access Token'), ['class' => 'form-label']) !!}
                    {!! Form::text('whatsapp_access_token', $setting['whatsapp_access_token'] ?? '', [
                    'class' => 'form-control',
                    'placeholder' => 'WhatsApp Access Token',
                    'id' => 'whatsapp_access_token',
                    ]) !!}
                </div>

                @foreach ($WhatsappNotification as $notification)
                <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                    <div class="list-group">
                        <div class="list-group-item form-switch form-switch-right">
                            <label class="form-label" style="margin-left:5%;">{{ $notification->name }}</label>

                            <input class="form-check-input whatsapp-notification" name='{{ $notification->id }}'
                                id="{{ $notification->id }}" type="checkbox" @if ($notification->is_active == 1)
                            checked="checked" @endif
                            type="checkbox" value="1" />
                            <label class="form-check-label" for="{{ $notification->id }}"></label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-lg-12">
                <div class="row">
                    <div class="text-start col-6">
                        <a href="#" data-ajax-popup1="true" data-size="md"
                            data-title="{{ __('Send Test whatsapp massage') }}"
                            data-url="{{ route('whatsappmassage.test') }}" data-toggle="tooltip"
                            title="{{ __('Test WhatsApp Massage') }}" class="btn  btn-primary test-whatsapp-massage">
                            {{ __('Send Test Message') }}
                        </a>
                    </div>
                    <div class="text-end col-6">
                        <input type="submit" value="{{ __('Save Changes') }}" class="btn-submit btn btn-primary">
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>