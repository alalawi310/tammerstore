@extends('layouts.app')

@section('page-title')
    {{ __('Add New Modules') }}
@endsection

@section('page-breadcrumb')
    {{ __('Modules') }},{{ __('Add New Addon') }}
@endsection

@section('page-action')
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.css') }}" type="text/css" />
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('module.index') }}">{{ __('Add-on Manager') }}</a></li>    
    <li class="breadcrumb-item">{{ __('Add New Addon') }}</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-10 col-xxl-8">
            <div class="card">
                <div class="card-body">
                    <section>
                        <!-- Add the dropdown for selecting the upload type -->
                        <div class="form-group">
                            <label class="form-label" for="uploadType">{{ __('Select Upload Type') }}</label>
                            <select id="uploadType" class="form-control">
                                <option value="{{ route('module.install') }}">{{ __('Add Addon') }}</option>
                                <option value="{{ route('addon.theme') }}">{{ __('Add New Theme') }}</option>
                            </select>
                        </div>

                        <div id="dropzone">
                            <form class="dropzone needsclick" id="demo-upload">
                                <div class="dz-message needsclick">
                                    {{ __('Drop files here or click to upload and install.') }}<br>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/dropzone.js') }}"></script>

    <script>
        Dropzone.autoDiscover = false;
        
        // Initialize Dropzone
        var dropzone = new Dropzone('#demo-upload', {
            thumbnailHeight: 120,
            thumbnailWidth: 120,
            maxFilesize: 500,
            acceptedFiles: '.zip',
            url: $('#uploadType').val(), // Initial URL based on default selection
            success: function(file, response) {
                $('#loader').fadeOut();
                if (response.status == 'success') {
                    show_toastr('Success', response.message, 'success');
                    setTimeout(() => {
                        window.location.href = "{{ route('module.index') }}";
                    }, 1000);
                } else {
                    show_toastr('Error', response.message, 'error');
                }
            }
        });

        // Update URL when the selected option changes
        $('#uploadType').change(function() {
            dropzone.options.url = $(this).val();
        });

        dropzone.on('sending', function(file, xhr, formData) {
            formData.append('_token', "{{ csrf_token() }}");
            $('#loader').fadeIn();
        });
    </script>
@endpush
