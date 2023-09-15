{{ Form::open(['route' => 'blog.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

@if (isset(auth()->user()->currentPlan) && auth()->user()->currentPlan->enable_chatgpt == 'on')
    <div class="mb-1 d-flex justify-content-end">
        <a href="#" class="btn btn-primary me-2 ai-btn" data-size="lg" data-ajax-popup-over="true"
            data-url="{{ route('generate', ['blog']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
        </a>
    </div>
@endif

<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('Title'), ['class' => 'form-label']) !!}
        {!! Form::text('title', old('title'), ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('Short Description'), ['class' => 'form-label']) !!}
        {!! Form::text('short_description', old('short_description'), [
            'class' => 'form-control',
            'required' => 'required',
        ]) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('Content'), ['class' => 'form-label']) !!}
        <div class="mt-3 form-group">
            <textarea class="pc-tinymce-2 required" name="content" id="content" rows="8"></textarea>
        </div>
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('', __('Category'), ['class' => 'form-label']) !!}
        {!! Form::select('category_id', $blogCategoryList, old('category_id'), [
            'class' => 'form-control select category',
            'data-role' => 'tagsinput',
            'id' => 'category_id',
            'name' => 'category_id',
            'placeholder' => 'Select Option',
        ]) !!}
    </div>

    <div class="form-group col-md-5">
        {!! Form::label('', __('Cover Image'), ['class' => 'form-label']) !!}
        <label for="upload_cover_image" class="image-upload bg-primary pointer w-100">
            <i class="px-1 ti ti-upload"></i> {{ __('Choose file here') }}
        </label>
        <input type="file" name="cover_image" id="upload_cover_image" class="d-none">
    </div>
</div>

<div class="pb-0 modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
</div>
{!! Form::close() !!}

<script src="{{ asset('assets/css/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>
<script>
    if ($(".pc-tinymce-2").length) {
        tinymce.init({
            selector: '.pc-tinymce-2',
            toolbar: 'link image',
            plugins: 'image code',
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function() {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.onload = function() {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), {
                            title: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            },
            height: "400",
            content_style: 'body { font-family: "Inter", sans-serif; }'
        });
    }
    document.addEventListener('focusin', function(e) {
        if (e.target.closest('.tox-tinymce-aux, .moxman-window, .tam-assetmanager-root') !== null) {
            e.stopImmediatePropagation();
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200,
        });
    });
</script>

<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
<script>
    if ($(".multi-select").length > 0) {
        $($(".multi-select")).each(function(index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                    removeItemButton: true,
                }
            );
        });
    }
</script>

@push('custom-css')
    <link rel="stylesheet" href="{{ asset('assets/css/summernote/summernote-bs4.css') }}">
    <style>
        .nav-tabs .nav-link-tabs.active {
            background: none;
        }
    </style>
@endpush