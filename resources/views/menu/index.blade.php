@extends('layouts.app')

@section('page-title', __('Menus'))

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Menus') }}</li>
@endsection

@section('action-button')
{{-- @permission('Create Menu') --}}
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{__('Add Menus')}}"
            data-url="{{ route('menus.create') }}" data-toggle="tooltip" title="{{ __('Create Menus') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
    {{--@endpermission--}}
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
                                    <th>{{ __('#') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            @foreach ($menus as $index => $menu)
                                <tbody>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $menu->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($menu->created_at)->format('M d, Y h:i A') }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1">
                                        {{-- @permission('Edit Menu') --}}
                                        <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                        title="{{__('Edit') }}">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                        {{--@endpermission--}}
                                        {{-- @permission('Delete Menu') --}}
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['menus.destroy', $menu->id], 'class' => 'd-inline']) !!}
                                        <button type="button" class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip"
                                        title="{{__('Delete') }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                        {!! Form::close() !!}
                                        {{--@endpermission--}}
                                        </div>
                                    </td>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
