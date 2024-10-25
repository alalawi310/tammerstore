@extends('layouts.app')
@section('page-title')
    {{ __('Menus') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Menus') }}</li>
@endsection
@php
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
    $logo = get_file('storage/uploads/landing_page_image');
@endphp
@push('custom-script')
    <script src="{{ asset('Modules/LandingPage/Resources/assets/js/plugins/tinymce.min.js') }}" referrerpolicy="origin">
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            <h5>{{ __('Menus') }}</h5>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                            <a data-size="md" data-url="{{ route('ownermenus.create') }}" data-ajax-popup="true"
                                data-bs-toggle="tooltip" data-title="{{ __('Create Menu') }}"
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
                                    <th>{{ __('Created At') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (is_array($menus) || is_object($menus))
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($menus as $key => $value)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($value->created_at)->format('M d, Y h:i A') }}</td>
                                            <td class="">
                                                <div class="d-flex gap-1">
                                                <a href="{{ route('ownermenus.edit', $value->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                title="{{ __('Edit') }}">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['ownermenus.destroy', $value->id],
                                                    'class' => 'd-inline',
                                                ]) !!}
                                                <button type="button" class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip"
                                                title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                                {!! Form::close() !!}
                                                </div>
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
@endsection
