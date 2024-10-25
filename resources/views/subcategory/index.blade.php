@extends('layouts.app')

@section('page-title', __('Sub Category'))

@section('action-button')
    {{-- @permission('Create Product Sub Category') --}}
    <div class=" text-end  gap-2 d-flex all-button-box justify-content-md-end justify-content-center">
        @if (module_is_active('ImportExport'))
            @permission('sub-category import')
                @include('importexport::import.subcategory_import', ['module' => 'subcategory'])
            @endpermission
            @permission('sub-category export')
                @include('importexport::export.subcategory_export', ['module' => 'subcategory'])
            @endpermission
        @endif
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
            data-title="{{ __('Add Sub category') }}"
            data-url="{{ route('sub-category.create') }}" data-toggle="tooltip"
            title="{{ __('Create Sub Category') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
    {{-- @endpermission --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Sub Category') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Icon') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Theme id') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($SubCategory as $Category)
                                    <tr>
                                        <td>{{ $Category->name }}</td>
                                        <td>
                                            <img src="{{ $Category->icon_path ? get_file($Category->image_path, APP_THEME()) : '' }}"
                                                alt="" class="category_Image">
                                        </td>
                                        <td>

                                            <img src="{{ $Category->icon_path ? get_file($Category->icon_path, APP_THEME()) : '' }}"
                                                alt="" class="category_Image">
                                        </td>
                                        <td>{{ $Category->MainCategory->name ?? '' }}</td>
                                        <td>{{ $Category->status == 1 ? __('Active') : __('InActive') }}</td>
                                        <td>{{ $Category->theme_id }}</td>
                                        <td class="text-end">
                                            {{-- @permission('Edit Product Sub Category') --}}
                                            <button class="btn btn-sm btn-info"
                                                data-url="{{ route('sub-category.edit', $Category->id) }}" data-size="md"
                                                data-ajax-popup="true" data-title="{{ __('Edit Sub Category') }}" data-bs-toggle="tooltip"
                                                title="{{__('Edit') }}">
                                                <i class="ti ti-pencil py-1"></i>
                                            </button>
                                            {{-- @endpermission --}}
                                            {{-- @permission('Delete Product Sub Category') --}}
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['sub-category.destroy', $Category->id],
                                                'class' => 'd-inline',
                                            ]) !!}
                                            <button type="button" class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip"
                                            title="{{__('Delete') }}"><i
                                                    class="ti ti-trash"></i></button>
                                            {!! Form::close() !!}
                                            {{-- @endpermission --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
