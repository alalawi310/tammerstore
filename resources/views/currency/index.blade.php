@extends('layouts.app')
@section('page-title', __('Currency'))
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Currency') }}</li>
@endsection
@section('action-button')
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{__('Add Currency')}}"
            data-url="{{ route('currency.create') }}" data-toggle="tooltip" title="{{ __('Create Currency') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
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
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Symbol') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($currency as $index => $currencies)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $currencies->name }}</td>
                                        <td>{{ $currencies->code }}</td>
                                        <td>{{ $currencies->symbol }}</td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-info"
                                                data-url="{{ route('currency.edit', $currencies->id) }}" data-size="md"
                                                data-ajax-popup="true" data-title="{{ __('Edit Currency') }}" data-bs-toggle="tooltip" title="{{ __('Edit') }}">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            </div>
                                        </td>
                                @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
