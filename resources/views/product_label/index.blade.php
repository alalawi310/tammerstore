@extends('layouts.app')

@section('page-title', __('Product Label'))

@section('action-button')
{{-- @permission('Create Product Label') --}}
<div class=" text-end  gap-2 d-flex all-button-box justify-content-md-end justify-content-center">
    @if (module_is_active('ImportExport'))
    @permission('label import')
        @include('importexport::import.label_import',['module'=>'label'])
    @endpermission
    @permission('label export')
        @include('importexport::export.label_export',['module'=>'label'])
    @endpermission
    @endif
    <a href="#" class="btn btn-sm btn-primary add_attribute" data-ajax-popup="true" data-size="md"
        data-title="{{ __("Add Label") }}"
        data-url="{{ route('product-label.create') }}"
        data-toggle="tooltip" title="{{ __('Create Label') }}">
        <i class="ti ti-plus"></i>
    </a>
</div>
{{-- @endpermission --}}

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Label') }}</li>
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
                                    <th>{{ __('Slug') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productLabels as $productLabel)
                                    <tr>
                                        <td>{{ $productLabel->name }}</td>
                                        <td>{{ $productLabel->slug }}</td>
                                        <td>
                                            {{-- @permission('Manage Product Label Status') --}}
                                            <div class="form-check form-switch">
                                                <input type="checkbox" data-id="{{$productLabel->id}}"  class="form-check-input status-index" name="status"
                                                id="status_{{$productLabel->id}}" value="{{$productLabel->status}}" {{ $productLabel->status ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_{{$productLabel->id}}"></label>
                                            </div>
                                            {{-- @endpermission --}}
                                        </td>

                                        <td class="text-end">
                                            {{-- @permission('Edit Product Label') --}}
                                            <button class="btn btn-sm btn-info"
                                                data-url="{{ route('product-label.edit', $productLabel->id) }}"
                                                data-size="md" data-ajax-popup="true"
                                                data-title="{{ __('Edit Label') }}" data-bs-toggle="tooltip"
                                                title="{{__('Edit') }}">
                                                <i class="ti ti-pencil" data-bs-toggle="tooltip" title="Edit Label"></i>
                                            </button>
                                            {{-- @endpermission --}}
                                            {{-- @permission('Delete Product Label') --}}
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['product-label.destroy', $productLabel->id],
                                                'class' => 'd-inline',
                                            ]) !!}
                                            <button type="button" class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip"
                                            title="{{__('Delete') }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
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
@push('scripts')
<script>
    $(document).on('change', '.status-index',function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var id = $(this).data('id'); 
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('product-label.status') }}",
            data: {'status': status, 'id': id},
            success: function(data){
                $('#loader').fadeOut();
                if (data.status != 'success') {
                    show_toastr('Error', data.message, 'error');
                } else {
                    show_toastr('Success', data.message, 'success');
                }
            }
        });
    });
</script>
@endpush