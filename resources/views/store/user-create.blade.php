{!! Form::open(['route' => 'stores.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

    @if((auth()->user()->type == 'super admin') && (!empty($setting['chatgpt_key'])))
        <div class="d-flex justify-content-end">
            <a href="#" class="btn btn-primary me-2 ai-btn mb-3" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['store']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
            </a>
        </div>
    @endif

    @if((auth()->user()->type == 'admin') && $plan && ($plan->enable_chatgpt == 'on'))
        <div class="d-flex justify-content-end">
            <a href="#" class="btn btn-primary me-2 ai-btn mb-3" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['store']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
            </a>
        </div>
    @endif

    <div class="row">
        <div class="form-group col-md-12">
            {!! Form::label('', __('Store Name'), ['class' => 'form-label']) !!}
            {!! Form::text('storename', null, ['class' => 'form-control','placeholder' =>__('Enter Store Name'), 'required' => 'true']) !!}
        </div>

        @if (auth()->user()->type == 'super admin')
            <div class="form-group col-md-12">
                {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control','placeholder'=>__('Enter Name'), 'required' => 'true']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('', __('Email'), ['class' => 'form-label']) !!}
                {!! Form::email('email', null, ['class' => 'form-control','placeholder' =>__('Enter Email'), 'required' => 'true']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('', __('Password'), ['class' => 'form-label']) !!}
                {{Form::password('password',array('class'=>'form-control','placeholder' =>__('Enter Password'), 'required' => 'true'))}}
            </div>
        @endif

        <div class="modal-footer pb-0">
            <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
        </div>
    </div>
{!! Form::close() !!}
