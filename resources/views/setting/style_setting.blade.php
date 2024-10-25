<!-- style customization -->
<div class="card mb-3" id="style_customize">
    <form method="POST" action="{{ route('customize.settings') }}" accept-charset="UTF-8">
        @csrf
        <div class="card-header">
            <div class="row">
                <div class="col-12">
                    <h5 class="h6 md-0">{{ __('Style Customize') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-12">
                    {{ Form::label('storecss', __('Style Customize'), ['class' => 'form-label']) }}
                    {{ Form::textarea('storecss', isset($setting['storecss']) ? $setting['storecss'] : '', ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Custom CSS')]) }}
                    @error('storecss')
                    <span class="invalid-about" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group col-md-12">
                    {{ Form::label('storejs', __('JS Customize'), ['class' => 'form-label']) }}
                    {{ Form::textarea('storejs', isset($setting['storejs']) ? $setting['storejs'] : '', ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Custom JS')]) }}
                    @error('storejs')
                    <span class="invalid-about" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
        </div>
    </form>
</div>
<!-- end style customization -->