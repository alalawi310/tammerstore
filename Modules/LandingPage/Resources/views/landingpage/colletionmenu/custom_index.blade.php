@extends('layouts.app')
@section('page-title')
    {{ __('Landing Page') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Landing Page') }}</li>
    <li class="breadcrumb-item">{{ __('Menus') }}</li>
@endsection
@php
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
    $logo = get_file('storage/uploads/landing_page_image');
@endphp

@push('custom-script')
    <script>
        document.getElementById('site_logo').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image').src = src
        }
    </script>
    <script src="{{ asset('Modules/LandingPage/Resources/assets/js/plugins/tinymce.min.js') }}" referrerpolicy="origin">
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Landing Page') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <h5>{{ __('Menu Bar') }}</h5>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                    <a data-size="lg" data-url="{{ route('custom_page.create') }}" data-ajax-popup="true"
                                        data-bs-toggle="tooltip" data-title="{{ __('Create Custom Page') }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (is_array($pages) || is_object($pages))
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($pages as $key => $value)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $value['menubar_page_name'] }}</td>
                                                    <td>
                                                        <span>
                                                            <div class="d-flex gap-1">
                                                                <button class="btn btn-sm btn-info"
                                                                    data-url="{{ route('custom_page.edit', $key) }}"
                                                                    data-size="lg" data-ajax-popup="true"
                                                                    data-title="{{ __('Edit Custom Page') }}" data-bs-toggle="tooltip"
                                                                    title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil" ></i>
                                                                </button>
                                                                @if (
                                                                    $value['page_slug'] != 'terms_and_conditions' &&
                                                                        $value['page_slug'] != 'about_us' &&
                                                                        $value['page_slug'] != 'privacy_policy')
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['custom_page.destroy', $key], 'class' => 'd-inline']) !!}
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip"
                                                                        title="{{ __('Delete') }}">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                    {!! Form::close() !!}
                                                                @endif
                                                            </div>
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
