@extends('layouts.app')

@section('page-title', __('Shipping Method'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('shipping-zone.index') }}">{{ __('Shipping Zone') }} @if(isset($shippingZones->zone_name)) ({{ $shippingZones->zone_name }}) @endif</a></li>
    <li class="breadcrumb-item">{{ __('Shipping Method') }}</li>
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
                                <th>{{ __('Shipping Method') }}</th>
                                <th>{{ __('Shipping Cost') }}</th>
                                <th class="text-start">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($shippingMethods as $value => $shippingMethod)
                            <tr>
                                <td>{{ ++$value }}</td>
                                <td>{{ $shippingMethod['method_name'] }}</td>
                                <td>{{ $shippingMethod['cost'] }}</td>
                                <td class="text-start">
                                    <div class="d-flex gap-1 justify-content-end">
                                    {{-- @permission('Edit Shipping Method') --}}
                                            @if($shippingMethod['method_name'] == 'Flat Rate')
                                                <button class="btn btn-sm btn-info" data-url="{{ route('shipping-method.edit', $shippingMethod['id']) }}" data-size="lg" data-ajax-popup="true" data-title="{{ __('Edit Shipping Method') }}" data-bs-toggle="tooltip"
                                                title="{{__('Edit') }}">  <i class="ti ti-pencil"></i>
                                                </button>
                                            @elseif($shippingMethod['method_name'] == 'Free shipping')
                                                <button class="btn btn-sm btn-info" data-url="{{ route('free-shipping.edit', $shippingMethod['id']) }}" data-size="md" data-ajax-popup="true" data-title="{{ __('Edit Shipping Method') }}" data-bs-toggle="tooltip"
                                                title="{{__('Edit') }}">  <i class="ti ti-pencil"></i>
                                                </button>
                                            @elseif($shippingMethod['method_name'] == 'Local pickup')
                                                <button class="btn btn-sm btn-info" data-url="{{ route('local-shipping.edit', $shippingMethod['id']) }}" data-size="md" data-ajax-popup="true" data-title="{{ __('Edit Shipping Method') }}" data-bs-toggle="tooltip"
                                                title="{{__('Edit') }}">  <i class="ti ti-pencil"></i>
                                                </button>
                                            @endif
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
