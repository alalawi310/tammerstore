{{ Form::model($city, ['route' => ['city.update', $city->id], 'method' => 'put']) }}
<div class="row">
<input type="hidden" name="country_active_tab" value="pills-city-tab">
    <div class="form-group col-md-12">
        {!! Form::label('city', __('City'), ['class' => 'form-label', 'id' => 'adv_label']) !!}
        {{ Form::text('city', !empty($city->name) ? $city->name : '', ['class' => 'form-control ', 'required' => 'required', 'city' => 'city']) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('country', __('Country'), ['class' => 'col-form-label']) }}
        <select class="form-control" id="country_id" name="country">
            <option value="" disabled selected>{{ __('Select Country') }}</option>
            @foreach ($countries as $key => $count)
                <option value="{{ $key }}" {{ $country->name == $count ? 'selected' : '' }}>{{ $count }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-12">
        {{ Form::label('state', __('State'), ['class' => 'col-form-label']) }}
        <select class="form-control" id="state_id" name="state">

            <option value="" disabled selected>{{ __('Select State') }}</option>

            @foreach ($states as $key => $count)
                <option value="{{ $key }}" {{ $state->name == $count ? 'selected' : '' }}>{{ $count }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}
