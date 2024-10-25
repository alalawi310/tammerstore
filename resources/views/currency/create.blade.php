{{ Form::open(['route' => 'currency.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

 {{-- @if (isset(auth()->user()->currentPlan) && auth()->user()->currentPlan->enable_chatgpt == 'on')
<div class="d-flex justify-content-end mb-1">
    <a href="#" class="btn btn-primary me-2 ai-btn" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['currency']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
        <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
    </a>
</div>
@endif --}}
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'true']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('Code'), ['class' => 'form-label']) !!}
        {!! Form::text('code', null, ['class' => 'form-control', 'required' => 'true']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('Symbol'), ['class' => 'form-label']) !!}
        {!! Form::text('symbol', null, ['class' => 'form-control', 'required' => 'true']) !!}
    </div>
    <div class="modal-footer pb-0">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}
