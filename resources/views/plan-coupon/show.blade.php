@extends('layouts.app')

@section('page-title', __('Plan Coupon Detail'))

@section('action-button')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('plan-coupon.index') }}">{{ __('Plan Coupon') }}</a></li>
    <li class="breadcrumb-item">{{ __('Plan Coupon Detail') }}</li>
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
                                <th>{{ __('Order ID') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userCoupons as $userCoupon)
                            @php
                                if (isset($userCoupon->order_detail->price)) {
                                    $data = $userCoupon->order_detail->price;
                                } else {
                                    if (module_is_active('Campaigns')) {
                                        $campaignsData = \Modules\Campaigns\app\Models\Campaigns::find($userCoupon->order);
                                        if ($campaignsData) {
                                            $data = $campaignsData->total_cost;
                                        }
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ $userCoupon->coupon_detail->name }}</td>
                                <td>{{ '#'.$userCoupon->order }}</td>
                                <td>{{ $data ?? ($userCoupon->order_detail->price ?? 0) }}</td>
                                <td>{{ $userCoupon->created_at }}</td>
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
    var doc = new jsPDF();
    var elementHandler = {
    '#ignorePDF': function (element, renderer) {
        return true;
    }
    };
    var source = window.document.getElementsByTagName("body")[0];
    doc.fromHTML(
        source,
        15,
        15,
        {
        'width': 180,'elementHandlers': elementHandler
        });

    doc.output("dataurlnewwindow");
</script>
@endpush

