{{ Form::open(['route' => 'faqs.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('Topic'), ['class' => 'form-label']) !!}
        {!! Form::text('topic', old('topic'), ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
</div>

<div class="pb-0 modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
</div>
{!! Form::close() !!}
