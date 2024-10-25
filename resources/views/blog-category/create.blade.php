{{ Form::open(['route' => 'blog-category.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

@if (isset(auth()->user()->currentPlan) && auth()->user()->currentPlan->enable_chatgpt == 'on')
    <div class="mb-1 d-flex justify-content-end">
        <a href="#" class="btn btn-primary me-2 ai-btn" data-size="lg" data-ajax-popup-over="true"
            data-url="{{ route('generate', ['category']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
        </a>
    </div>
@endif

<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('Title'), ['class' => 'form-label']) !!}
        {!! Form::text('name', old('title'), ['class' => 'form-control', 'required' => 'required']) !!}
    </div>

    <div class="form-group col-md-4">
        {!! Form::label('', __('Status'), ['class' => 'form-label']) !!}
        <div class="form-check form-switch">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" class="form-check-input status" name="status" id="status" value="1">
            <label class="form-check-label" for="status"></label>
        </div>
    </div>

    <div class="pb-0 modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}
