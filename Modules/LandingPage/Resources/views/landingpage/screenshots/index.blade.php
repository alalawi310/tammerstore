@extends('layouts.app')
@section('page-title')
    {{ __('Landing Page') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">{{__('Landing Page')}}</li>
@endsection

@php
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
    $logo=get_file('storage/uploads/landing_page_image');
@endphp

@push('custom-script')

@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">{{__('Landing Page')}}</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @include('landingpage::layouts.tab')
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    {{--  Start for all settings tab --}}
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5>{{ __('Screenshots') }}</h5>
                                    </div>
                                </div>
                            </div>

                            {{ Form::open(array('route' => 'screenshots.store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}
                                                {{ Form::text('screenshots_heading',$settings['screenshots_heading'], ['class' => 'form-control ', 'placeholder' => __('Enter Heading')]) }}
                                                @error('mail_host')
                                                <span class="invalid-mail_driver" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                                                {{ Form::text('screenshots_description', $settings['screenshots_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
                                                @error('mail_port')
                                                <span class="invalid-mail_port" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn btn-print-invoice btn-primary m-r-10" type="submit" >{{ __('Save Changes') }}</button>
                                </div>
                            {{ Form::close() }}

                        </div>


                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-9">
                                        <h5>{{ __('Menu Bar') }}</h5>
                                    </div>
                                    <div class="col-3 justify-content-end d-flex">
                                        <a href="javascript::void(0);" data-size="lg" data-url="{{ route('screenshots_create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" data-title="{{__('Create Screenshots')}}"  class="btn btn-sm btn-primary">
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
                                                <th>{{__('No')}}</th>
                                                <th>{{__('Name')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           @if (is_array($screenshots) || is_object($screenshots))
                                               @php
                                                   $no = 1
                                               @endphp
                                                @foreach ($screenshots as $key => $value)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $value['screenshots_heading'] }}</td>
                                                        <td>
                                                            <span>
                                                                <div class="d-flex gap-1">
                                                                    <button class="btn btn-sm btn-info"
                                                                    data-url="{{ route('screenshots_edit',$key) }}"  data-size="lg"
                                                                        data-ajax-popup="true" data-title="{{ __('Edit Screenshots') }}" data-bs-toggle="tooltip"
                                                                        title="{{ __('Edit') }}">
                                                                        <i class="ti ti-pencil"></i>
                                                                    </button>
                                                                    {!! Form::open(['method' => 'GET', 'route' => ['screenshots_delete', $key], 'class' => 'd-inline']) !!}
                                                                    <button type="button" class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip"
                                                                    title="{{ __('Delete') }}">
                                                                    <i class="ti ti-trash"></i>
                                                                    </button>
                                                                    {!! Form::close() !!}
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
                    {{--  End for all settings tab --}}
                </div>
            </div>
        </div>
    </div>
@endsection



