@extends('layouts.app')

@section('page-title', __('Blogs'))

@section('action-button')
{{-- @permission('Create Blog') --}}
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{__('Add Blog')}}"
            data-url="{{ route('blog.create') }}" data-toggle="tooltip" title="{{ __('Create Blog') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
{{-- @endpermission --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Blogs') }}</li>
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
                                    <th>{{ __('Cover Image') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Short Description') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($blogs as $blog)
                                    <tr>
                                        <td>
                                            <img src="{{ asset($blog->cover_image_path) }}" alt="" width="100" class="cover_img{{ $blog->id }}">
                                        </td>
                                        <td>{{$blog->category->name}}</td>
                                        <td class="fix-content">{{$blog->title}}</td>
                                        <td class="fix-content">{{$blog->short_description}}</td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1">
                                                {{-- @permission('Edit Blog') --}}
                                                <button class="btn btn-sm btn-info"
                                                    data-url="{{ route('blog.edit', $blog->id) }}" data-size="lg"
                                                    data-ajax-popup="true" data-title="{{ __('Edit Blog') }}" data-bs-toggle="tooltip" title="{{ __('Edit') }}">
                                                    <i class="ti ti-pencil" data-bs-toggle="tooltip" title="edit"></i>
                                                </button>
                                                {{-- @endpermission --}}
                                                {{-- @permission('Delete Blog') --}}
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['blog.destroy', $blog->id], 'class' => 'd-inline']) !!}
                                                <button type="button" class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash" ></i>
                                                </button>
                                                {!! Form::close() !!}
                                                {{-- @endpermission --}}
                                            </div>
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

