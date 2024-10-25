@extends('layouts.app')

@section('page-title', __('Manage Themes'))

@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Manage Themes') }}</li>
@endsection

@section('content')
<!-- [ Main Content ] start -->
<div class="row">
    <div class="d-flex align-items-center justify-content-end">
        <div class="col-md-2">
            <input type="text" id="theme-search" placeholder="{{__('Search Themes')}}" class="form-control mb-3">
        </div>
    </div>
    <!-- [ basic-table ] start -->
   <div class="border border-primary rounded p-3">
        @php
            $user =auth()->user();
            $store = App\Models\Store::where('id', $user->current_store)->first();
        @endphp
        <div class="row uploaded-picss gy-4" id="theme-list">
            @foreach ($themes as $folder)
            @if (!in_array($folder, $addons))
                @continue
            @endif
                    <div class="col-xl-3 col-lg-4 col-md-6 theme-item">
                        <div class="theme-card border-primary theme1  selected h-100">
                            <label for="themes_{{!empty($folder)?$folder:''}}" class="h-100 d-flex flex-column justify-content-between">
                                <img src="{{ asset('themes/'.$folder.'/theme_img/img_1.png') }}" class="front-img">

                                <div class="theme-bottom-content" >
                                   <div class="theme-card-lable"><b>{{ Str::ucfirst($folder) }}</b></div>
                                    <div class="theme-card-button flex-wrap">
                                    <a class="btn btn-sm btn-primary text-end" href="{{ route('theme-preview.create', ['theme_id' => $folder]) }}">
                                    {{ __('Customize') }}
                                    </a>
                                    @if (auth()->user()->theme_id != $folder)
                                        {!! Form::open(['method' => 'POST', 'route' => ['theme-preview.make-active'], 'class' => 'd-inline']) !!}
                                            @csrf
                                            <input type="hidden" name="theme_id" value="{{ $folder }}">
                                            <button type="submit" class="btn btn-sm btn-primary text-end" {{ (auth()->user()->theme_id == $folder ? 'disabled' : '') }}>
                                            {{ __('Make Active') }}
                                            </button>
                                        {!! Form::close() !!}
                                    @endif
                                    </div>

                                </div>
                            </label>
                        </div>
                    </div>
            @endforeach
        </div>
   </div>
    <!-- [ basic-table ] end -->
</div>
<!-- [ Main Content ] end -->
@endsection

@push('custom-script')
<script type="text/javascript">

    $(".email-template-checkbox").click(function(){

        var chbox = $(this);
        $.ajax({
            url: chbox.attr('data-url'),
            data: {_token: $('meta[name="csrf-token"]').attr('content'), status: chbox.val()},
            type: 'post',
            success: function (response) {
                $('#loader').fadeOut();
                if (response.is_success) {
                    show_toastr('Success', response.success, 'success');
                    if (chbox.val() == 1) {
                        $('#' + chbox.attr('id')).val(0);
                    } else {
                        $('#' + chbox.attr('id')).val(1);
                    }
                } else {
                    show_toastr('Error', response.error, 'error');
                }
            },
            error: function (response) {
                $('#loader').fadeOut();
                response = response.responseJSON;
                if (response.is_success) {
                    show_toastr('Error', response.error, 'error');
                } else {
                    show_toastr('Error', response, 'error');
                }
            }
        })
    });

    $(document).on('keyup','#theme-search', function() {
        var value = $(this).val().toLowerCase();
        $('#theme-list .theme-item').filter(function() {
            $(this).toggle($(this).find('.theme-card-lable').text().toLowerCase().indexOf(value) > -1)
        });
    });
</script>
@endpush
