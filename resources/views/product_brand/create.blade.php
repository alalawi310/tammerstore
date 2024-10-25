{{ Form::open(['route' => 'product-brand.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id'=> 'uploadForm']) }}

<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'true', 'id' => 'name']) !!}
        <span id="nameError" style="color: red; display: none;">{{ __('This field is required.') }}</span>
    </div>
    <div class="form-group col-md-12">
        <div class="row">
            <div class="col-md-6">
            <label for="brand_logo">
            {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                <div class="image-upload bg-primary pointer w-100 logo_update"> <i
                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                </div>
                <input type="file" class="form-control file"
                    name="logo" id="brand_logo"
                    data-filename="logo_update"
                    onchange="document.getElementById('brandLogo').src = window.URL.createObjectURL(this.files[0])" required>
            </label>
            </div>
            <div class="logo-content mt-3 col-md-6">
                    <img src="#"
                        class="big-logo invoice_logo img_setting" id="brandLogo" width="200px">
            </div>
        </div>
    </div>

    <div class="form-group col-md-4">
        {!! Form::label('', __('Status'), ['class' => 'form-label']) !!}
        <div class="form-check form-switch">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" class="form-check-input status" name="status" id="status" value="1">
            <label class="form-check-label" for="status"></label>
        </div>
    </div>
    <div class="form-group col-md-4">
        {!! Form::label('', __('Is Popular ?'), ['class' => 'form-label']) !!}
        <div class="form-check form-switch">
            <input type="hidden" name="is_popular" value="0">
            <input type="checkbox" name="is_popular" class="form-check-input input-primary" id="customCheckdef1trending" value="1">
            <label class="form-check-label" for="customCheckdef1trending"></label>
        </div>
    </div>
    <div class="modal-footer pb-0">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}

