@extends('layouts.app')

@section('page-title')
    {{ __('Top Sales Report') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __('Top Sales Report') }}</li>
@endsection

@section('action-button')
    <div class="text-end">
        <a href="#" class="btn btn-sm btn-primary btn-icon" onclick="saveAsPDF()"  data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Download') }}">
            <i class="ti ti-download"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row" id="printableArea">
        <!-- Card 1 with Chart -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 m-0">
                <div class="card-header">
                    <h5 class="card-title">{{ __('Top Selling Products') }}</h5>
                </div>
                <div class="card-body" style="overflow-x: overlay;">
                    <div id="topSellingProductsChart"></div>
                </div>
            </div>
        </div>

        <!-- Empty Card 2 -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 m-0">
                <div class="card-header">
                    <h5 class="card-title">{{ __('Top Selling Category') }}</h5>
                </div>
                <div class="card-body" style="overflow-x: overlay;">
                    <div id="topSellingsecondProductsChart"></div>
                </div>
            </div>
        </div>

        <!-- Empty Card 3 -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 m-0">
                <div class="card-header">
                    <h5 class="card-title">{{ __('Top Selling Brand') }}</h5>
                </div>
                <div class="card-body" style="overflow-x: overlay;">

                    <div id="topSellingBrandChart"></div>
                </div>
            </div>
        </div>

        <!-- Empty Card 4 -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 m-0">
                <div class="card-header">
                    <h5 class="card-title">{{ __('Top Payment Method') }}</h5>
                </div>
                <div class="card-body" style="overflow-x: overlay;">
                    <div id="paymentMethodsChart"></div>
                </div>
            </div>
        </div>
    </div>

    @push('custom-script')
        <script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
        <script>
            // var filename = $('#filename').val();
            function saveAsPDF() {
                var element = document.getElementById('printableArea');
                var opt = {
                    margin: 0.3,
                    filename: "Top Sales Report",
                    image: {type: 'jpeg', quality: 1},
                    html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                    jsPDF: {unit: 'in', format: 'A2'}
                };
                html2pdf().set(opt).from(element).save();
            }
        </script>
        <script>
            // Prepare your dynamic data
            var productCounts = @json($productCounts);
            var productNames = @json($productNames);

            // Check if data is empty
            var hasData = productCounts.length > 0;

            var options = {
                series: hasData ? productCounts : [],
                chart: {
                    type: 'pie',
                },
                labels: hasData ? productNames : [],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 500
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                noData: {
                    text: "@lang('No Data Found')",
                    align: 'center',
                    verticalAlign: 'middle',
                    offsetX: 0,
                    offsetY: 0,
                    style: {
                        color: '#000',
                        fontSize: '14px',
                        fontFamily: undefined
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#topSellingProductsChart"), options);
            chart.render();
        </script>

        <script>
            // Prepare your dynamic data
            var saleCounts = @json($top_sales->pluck('total_sale'));
            var saleNames =  @json($top_sales->pluck('sale_name'));

            // Check if data is empty
            var hasSaleData = saleCounts.length > 0;
            var options = {
                series: hasSaleData ? saleCounts : [],
                chart: {
                    width: 500,
                    type: 'pie',
                },
                labels: hasSaleData ? saleNames : [],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 500
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                noData: {
                    text: "@lang('No Data Found')",
                    align: 'center',
                    verticalAlign: 'middle',
                    offsetX: 0,
                    offsetY: 0,
                    style: {
                        color: '#000',
                        fontSize: '14px',
                        fontFamily: undefined
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#topSellingsecondProductsChart"), options);
            chart.render();
        </script>

        <script>
            // Prepare your dynamic data
            var sellingCounts = @json($top_brand_sales->pluck('total_sale'));
            var sellingNames =  @json($top_brand_sales->pluck('sale_name'));

            // Check if data is empty
            var hasSellingData = sellingCounts.length > 0;
            var options = {
                series: hasSellingData ? sellingCounts : [],
                chart: {
                    width: 500,
                    type: 'pie',
                },
                labels: hasSellingData ? sellingNames : [],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 500
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                noData: {
                    text: "@lang('No Data Found')",
                    align: 'center',
                    verticalAlign: 'middle',
                    offsetX: 0,
                    offsetY: 0,
                    style: {
                        color: '#000',
                        fontSize: '14px',
                        fontFamily: undefined
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#topSellingBrandChart"), options);
            chart.render();
        </script>

        <script>
            // Prepare your dynamic data
            var paymentCounts = @json($paymentMethods->values());
            var paymentNames =  @json($paymentMethods->keys());

            // Check if data is empty
            var hasPaymentData = paymentCounts.length > 0;
            var options = {
                series: hasPaymentData ? paymentCounts : [],
                chart: {
                    width: 500,
                    type: 'pie',
                },
                labels: hasPaymentData ? paymentNames : [],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 500
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                noData: {
                    text: "@lang('No Data Found')",
                    align: 'center',
                    verticalAlign: 'middle',
                    offsetX: 0,
                    offsetY: 0,
                    style: {
                        color: '#000',
                        fontSize: '14px',
                        fontFamily: undefined
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#paymentMethodsChart"), options);
            chart.render();
        </script>
    @endpush
@endsection
