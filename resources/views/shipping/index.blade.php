@extends('layouts.app')

@section('page-title', __('Shipping Class'))

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Shipping Class') }}</li>
@endsection

@section('action-button')
    {{-- @permission('Create Shipping Class') --}}
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{__('Add Shipping Class')}}"
            data-url="{{ route('shipping.create') }}" data-toggle="tooltip" title="{{ __('Create Shipping Class') }}">
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
                                    <th>{{ __('Descriptions') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($shippings as $index => $shipping)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $shipping->name }}</td>
                                    <td class="fix-content">{{ $shipping->description }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            {{-- @permission('Edit Shipping Class') --}}
                                            <button class="btn btn-sm btn-info" data-url="{{ route('shipping.edit', $shipping->id) }}" data-size="md" data-ajax-popup="true" data-title="{{ __('Edit Shipping Class') }}" data-bs-toggle="tooltip"
                                            title="{{__('Edit') }}">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            {{--@endpermission--}}
                                            {{-- @permission('Delete Shipping Class') --}}
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['shipping.destroy', $shipping->id], 'class' => 'd-inline']) !!}
                                            <button type="button" class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip"
                                            title="{{__('Delete') }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                            {!! Form::close() !!}
                                            {{--@endpermission--}}
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
