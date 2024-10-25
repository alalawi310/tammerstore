{{ Form::open(array('route' => 'ownermenus.store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
    <div class="">
        @csrf
        <div class="row">
            <div class="form-group col-md-12">
                {{Form::label('name',__('Menu Name'),['class'=>'form-label'])}}
                {{Form::text('name',null,array('class'=>'form-control font-style','placeholder'=>__('Enter Menu Title'),'required'=>'required'))}}
            </div>
        </div>
    </div>
    <div class="text-end mt-2">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
    </div>
{{ Form::close() }}
