@extends('layouts.app')
@section('page-title')
    {{__('Sub Domain')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{__('Domain')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('stores.index') }}">{{ __('Users') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Sub Domain') }}</li>
@endsection
@section('action-button')
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="{{ route('store.customdomain') }}" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip"
            data-bs-placement="top" title="{{ __('Custom Domain') }}">{{ __('Custom Domain') }}</a>

        <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New User') }}"
            data-url="{{ route('store.user.create') }}" data-toggle="tooltip" title="{{ __('Create New User') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection
@section('filter')
@endsection
@push('css-page')
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 dataTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sub Domain Name')}}</th>
                                    <th>{{ __('Store Name')}}</th>
                                    <th>{{ __('Email')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stores as $store)
                                <tr>
                                    <td>
                                        {{$store->subdomain}}
                                    </td>
                                    <td>
                                        {{$store->name}}
                                    </td>
                                    <td>
                                        {{($store->email)}}
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
