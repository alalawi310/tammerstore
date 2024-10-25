{{ Form::model($country, ['route' => ['countries.update', $country->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
<div class="row">
<input type="hidden" name="country_active_tab" value="pills-country-tab">
    <div class="form-group col-md-12">
        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
        {{ Form::text('name', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
    </div>

    <div class="modal-footer pb-0">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}
