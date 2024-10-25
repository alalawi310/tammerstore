<!-- HTML -->

@extends('layouts.app')

@section('page-title')
    {{ __('Order Country Report') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __('Order Country Report') }}</li>
@endsection

@section('action-button')
    <div class="text-end">
        <a href="#" class="btn btn-sm btn-primary btn-icon" onclick="saveAsPDF()"  data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Download') }}">
            <i class="ti ti-download"></i>
        </a>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/svgMap.min.css') }}">
@endpush

@section('content')
<div class="row" id="printableArea">
    <!-- Card 1 with Chart -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 m-0">
            <div class="card-header">
                <h5 class="card-title">{{ __('Top Selling By Country Report') }}</h5>
            </div>
            <div class="card-body" style="overflow-x: overlay;">
                <div id="topSellingProductsChart"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card h-100 m-0">
            <div class="card-header">
                <h5 class="card-title">{{ __('Top Selling By Country Report') }}</h5>
            </div>
            <div class="card-body">
                <div id="BarchartOfStatus"></div>

            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card h-100 m-0">
            <div class="card-header">
                <h5 class="card-title">{{ __('Top Selling By Country Report') }}</h5>
            </div>
            <div class="card-body">
                <div id="orderLineStatusChart"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card h-100 m-0">
            <div class="card-header" style="display: flex; justify-content: space-between;">
                <h5 class="card-title">{{ __('Countries') }}</h5>
                <h4 class="text-dark" style="text-align: end; margin: 0px; font-size: initial;">{{__('Total Order Is')}} : {{$total}}</h4>
            </div>
            <div class="card-body">
                <div id="map"></div>
            </div>
        </div>
    </div>
@endsection

@push('custom-script')
    <script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
        <script>
            function saveAsPDF() {
                var element = document.getElementById('printableArea');
                var opt = {
                    margin: 0.3,
                    filename: "Country Report",
                    image: {type: 'jpeg', quality: 1},
                    html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                    jsPDF: {unit: 'in', format: 'A2'}
                };
                html2pdf().set(opt).from(element).save();
            }
        </script>
    <script>
        var options = {
            series: @json($billingData->pluck('total_orders')),
            chart: {
                width: 500,
                type: 'pie',
            },
            labels: @json($billingData->pluck('country_name'))
        };

        var chart = new ApexCharts(document.querySelector("#topSellingProductsChart"), options);
        chart.render();
    </script>

    <script>
        var colors = ['#33FF57']; // Example color array
        var options = {
            series: [{
                data: @json($billingData->pluck('total_orders'))
            }],
            chart: {
                height: 350,
                type: 'bar',
                events: {
                    click: function(chart, w, e) {
                    }
                }
            },
            colors: colors,
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            xaxis: {
                categories:@json($billingData->pluck('country_name')),
                labels: {
                    style: {
                        colors: ["#000000"],
                        fontSize: '12px'
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#BarchartOfStatus"), options);
        chart.render();
    </script>

    <script>
        var options = {
            series: [{
                name: 'Orders',
                data: @json($billingData->pluck('total_orders'))
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                        show: false,
                    },
            },
            xaxis: {
                categories: @json($billingData->pluck('country_name')),
                title: {
                    text: 'Country Name'
                }
            },
            yaxis: {
                title: {
                    text: 'Total Orders'
                }
            },
            stroke: {
                curve: 'smooth'
            },
            dataLabels: {
                enabled: false
            },
            markers: {
                size: 5
            }
        };

        var chart = new ApexCharts(document.querySelector("#orderLineStatusChart"), options);
        chart.render();
    </script>

    <script src="{{ asset('assets/js/svgMap.min.js') }}"></script>

    <script>
        new svgMap({
            targetElementID: 'map',
            data: {
                data: {
                    pageviews: {
                        name: '',
                        format: '{0} Orders',
                        thousandSeparator: ',',
                    },
                },
                applyData: 'pageviews',
                values: {!! json_encode($formattedData) !!},
            },
            colorMin: '#a2eeed',
            colorMax: '#19c8c7',
            flagType: 'emoji',
        });
    </script>
@endpush
