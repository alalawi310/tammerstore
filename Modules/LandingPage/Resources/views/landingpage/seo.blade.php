@extends('layouts.app')
@section('page-title')
    {{ __('Landing Page') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Landing Page') }}</li>
@endsection

@php

    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
    $logo = get_file('storage/uploads/landing_page_image');

@endphp

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Landing Page') }}</li>
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
                    {{ Form::model($settings, ['route' => ['seo.store'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h5 class="mb-2">{{ __('SEO') }}</h5>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('metatitle', __('Meta Title'), ['class' => 'form-label']) }}
                                        {!! Form::text('metatitle', null, [
                                            'class' => 'form-control',
                                            'placeholder' => __('Meta Title'),
                                        ]) !!}
                                        @error('meta_title')
                                            <span class="invalid-about" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('metakeyword', __('Meta Keywords'), ['class' => 'form-label']) }}
                                        {!! Form::textarea('metakeyword', null, [
                                            'class' => 'form-control',
                                            'rows' => 3,
                                            'placeholder' => __('Meta Keyword'),
                                        ]) !!}
                                        @error('meta_keywords')
                                            <span class="invalid-about" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('metadesc', __('Meta Description'), ['class' => 'form-label']) }}
                                        {!! Form::textarea('metadesc', null, [
                                            'class' => 'form-control',
                                            'rows' => 3,
                                            'placeholder' => __('Meta Description'),
                                        ]) !!}

                                        @error('meta_description')
                                            <span class="invalid-about" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group pt-0">
                                        <div class=" setting-card">
                                            <label for="" class="form-label">{{ __('Meta Image') }}</label>
                                            <div class="seo-image">
                                                <a href="{{ asset(!empty($settings['metaimage']) ? $settings['metaimage'] : 'storage/uploads/ecommercego-saas-preview.png') }}"
                                                    target="_blank" class="d-block">
                                                    <img id="meta_image" alt="your image"
                                                        src="{{ asset(!empty($settings['metaimage']) ? $settings['metaimage'] : 'storage/uploads/ecommercego-saas-preview.png') }}"
                                                        width="100%" class="img_setting">
                                                </a>
                                            </div>
                                            <div class="choose-files mt-3">
                                                <label for="metaimage">
                                                    <div class=" bg-primary full_logo"> <i
                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                    </div>
                                                    <input type="file" class="form-control file"
                                                        accept="image/png, image/gif, image/jpeg,image/jpg" id="metaimage"
                                                        name="metaimage"
                                                        onchange="document.getElementById('metaimage').src = window.URL.createObjectURL(this.files[0])"
                                                        data-filename="metaimage">
                                                </label>
                                            </div>
                                            @error('metaimage')
                                                <div class="row">
                                                    <span class="invalid-logo" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <input class="btn btn-print-invoice btn-primary m-r-10" type="submit"
                                value="{{ __('Save Changes') }}">
                        </div>
                    </div>
                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>
@endsection
