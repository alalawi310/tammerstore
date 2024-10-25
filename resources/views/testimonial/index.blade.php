@extends('layouts.app')

@section('page-title', __('Testimonial'))

@section('action-button')
{{-- @permission('Create Testimonial') --}}
    <div class=" text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{__('Add Testimonial')}}"
            data-url="{{ route('testimonial.create') }}" data-toggle="tooltip" title="{{ __('Create Testimonial') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
{{-- @endpermission --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Testimonial') }}</li>
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ basic-table ] start -->
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <th>{{ __('MainCategory') }}</th>
                                    <th>{{ __('SubCategory') }}</th>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Rating') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($testimonials as $testimonial)
                                    <tr>

                                        <td>{{ !empty($testimonial->MainCategoryData) ? $testimonial->MainCategoryData->name : '' }}</td>
                                        <td>{{ !empty($testimonial->SubCategoryData) ? $testimonial->SubCategoryData->name : '' }}</td>
                                        <td>{{ !empty($testimonial->ProductData) ? $testimonial->ProductData->name : '' }}</td>
                                        <td>
                                            @for ($i = 0; $i < 5; $i++)
                                            <i class="ti ti-star {{ $i < $testimonial->rating_no ? 'text-warning' : '' }} "></i>
                                            @endfor
                                        </td>
                                        <td class="fix-content">{{ $testimonial->description }}</td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                            {{-- @permission('Edit Testimonial') --}}
                                            <button class="btn btn-sm btn-info"
                                                data-url="{{ route('testimonial.edit', $testimonial->id) }}" data-size="md"
                                                data-ajax-popup="true" data-title="{{ __('Edit Testimonial') }}" data-bs-toggle="tooltip"
                                                title="{{__('Edit') }}">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            {{-- @endpermission --}}
                                            {{-- @permission('Delete Testimonial') --}}
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['testimonial.destroy', $testimonial->id], 'class' => 'd-inline']) !!}
                                            <button type="button" class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip"
                                            title="{{__('Delete') }}">
                                                <i class="ti ti-trash"></i>
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
            <!-- [ basic-table ] end -->
        </div>
    </div>
        <!-- [ Main Content ] end -->
    @endsection

    @push('custom-script')
        <script>
            $(document).on('change', '#maincategory_id', function(e) {
                var id = $(this).val();
                var val = $('.subcategory_id_div').attr('data_val');
                var data = {
                    id: id,
                    val: val

                }
                $.ajax({
                    url: '{{ route('get.subcategory') }}',
                    method: 'POST',
                    data: data,
                    context: this,
                    success: function(response) {
                        $('#loader').fadeOut();
                        $.each(response, function (key, value) {
                            $("#subcategory-dropdown").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                        var val = $('.subcategory_id_div').attr('data_val', 0);
                        $('.subcategory_id_div span').html(response.html);
                        comman_function();
                    }
                });

            });
            $(document).on('change', '#subcategory-dropdown', function(e) {
                var id = $(this).val();
                var val = $('.product_id_div').attr('data_val');

                var data = {
                    id: id,
                    val: val

                }
                $.ajax({
                    url: '{{ route('get.product') }}',
                    method: 'POST',
                    data: data,
                    context: this,
                    success: function(response) {
                        $('#loader').fadeOut();
                        $.each(response, function (key, value) {
                            $("#product-dropdown").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                        var val = $('.product_id_div').attr('data_val', 0);
                        $('.product_id_div span').html(response.html);
                        comman_function();
                    }
                });

            });
        </script>
    @endpush
