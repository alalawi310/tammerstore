@extends('layouts.app')

@section('page-title', __('Tax Class'))

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Tax Class') }}</li>
@endsection

@section('action-button')
{{-- @permission('Create Tax') --}}
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{__('Add Tax Class')}}"
            data-url="{{ route('taxes.create') }}" data-toggle="tooltip" title="{{ __('Create Tax Class') }}">
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
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            @foreach ($taxes as $index => $tax)
                                <tbody>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $tax->name }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            {{-- @permission('Show Tax') --}}
                                            <a href="{{ route('taxes.show',$tax->id) }}" class="btn btn-sm btn-warning" data-title="{{ __('Show Tax Class') }}" data-bs-toggle="tooltip"
                                            title="{{__('Show') }}">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            {{-- @endpermission --}}
                                            {{-- @permission('Edit Tax') --}}
                                            <button class="btn btn-sm btn-info" data-url="{{ route('taxes.edit', $tax->id) }}" data-size="md" data-ajax-popup="true" data-title="{{ __('Edit Tax Class') }}" data-bs-toggle="tooltip"
                                            title="{{__('Edit') }}">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            {{--@endpermission--}}
                                            {{-- @permission('Delete Tax') --}}
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['taxes.destroy', $tax->id], 'class' => 'd-inline']) !!}
                                            <button type="button" class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip"
                                            title="{{__('Delete') }}">
                                                <i class="ti ti-trash" ></i>
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
