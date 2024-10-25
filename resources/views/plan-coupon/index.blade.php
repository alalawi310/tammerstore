@extends('layouts.app')

@section('page-title', __('Plan Coupon'))

@section('action-button')
    {{-- @permission('Create Coupon') --}}
    <div class="text-end d-flex all-button-box justify-content-end">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{__('Add Coupon')}}"
            data-url="{{ route('plan-coupon.create') }}" data-toggle="tooltip" title="{{ __('Create Coupon') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
    {{-- @endpermission --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Plan Coupon') }}</li>
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
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Discount (%)') }}</th>
                                    <th>{{ __('Limit') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->name }}</td>
                                        <td>{{ $coupon->code }}</td>
                                        <td>{{ $coupon->discount }}</td>
                                        <td>{{ $coupon->limit }}</td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1">
                                                {{-- View --}}
                                                {{-- @permission('Manage Coupon') --}}
                                                <a class="btn btn-sm btn-warning"
                                                        href="{{ route('plan-coupon.show', $coupon->id) }}" data-bs-toggle="tooltip" title="{{ __('Show') }}">
                                                        <i class="ti ti-eye"></i>
                                                </a>
                                                {{-- @endpermission --}}
    
                                                {{-- Edit --}}
                                                {{-- @permission('Edit Coupon') --}}
                                                    <button class="btn btn-sm btn-info"
                                                        data-url="{{ route('plan-coupon.edit', $coupon->id) }}" data-size="md"
                                                        data-ajax-popup="true" data-title="{{ __('Edit Coupon') }}" data-bs-toggle="tooltip" title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil"></i>
                                                    </button>
                                                {{-- @endpermission --}}
    
                                                {{-- Delete --}}
                                                {{-- @permission('Delete Coupon') --}}
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['plan-coupon.destroy', $coupon->id], 'class' => 'd-inline']) !!}
                                                    <button type="button" class="btn btn-sm btn-danger show_confirm" data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash text-white py-1" ></i>
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
        </div>
    </div>
@endsection

@push('custom-script')
    <script>
        $(document).on('click', '#code-generate', function() {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>
@endpush
