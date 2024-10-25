@extends('layouts.app')

@section('page-title')
    {{ __('Order Status Report') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __('Order Status Report') }}</li>
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
        <div class="col-md-5 mb-4">
            <div class="card h-100 m-0">
                <div class="card-header">
                    <h5 class="card-title">{{ __('Order Status Report') }}</h5>
                </div>
                <div class="card-body" style="overflow-x: overlay;">
                    <div id="orderStatusChart"></div>

                </div>
            </div>
        </div>
        <div class="col-md-7 mb-4">
            <div class="card h-100 m-0">
                <div class="card-header">
                    <h5 class="card-title">{{ __('Order Status Report') }}</h5>
                </div>
                <div class="card-body">
                    <div id="BarchartOfStatus"></div>

                </div>
            </div>
        </div>
        <div class="col-md-12 mb-4">
            <div class="card h-100 m-0">
                <div class="card-header">
                    <h5 class="card-title">{{ __('Order Status Report') }}</h5>
                </div>
                <div class="card-body">
                    <div id="orderLineStatusChart"></div>
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
                filename: "Order Status Report",
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A2'}
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
    <script>
        var options = {
            series: @json(array_values($orderStatusCounts)),
            chart: {
                width: 500,
                type: 'pie',
            },
            labels: @json(array_keys($orderStatusCounts)),
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
            }]
        };

        var chart = new ApexCharts(document.querySelector("#orderStatusChart"), options);
        chart.render();
    </script>

    <script>
        var colors = ['#33FF57']; // Example color array
        var options = {
            series: [{
                data: @json(array_values($orderStatusCounts))
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
                categories: @json(array_keys($orderStatusCounts)),
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
                data: @json(array_values($orderStatusCounts))
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                        show: false,
                    },
            },
            xaxis: {
                categories: @json(array_keys($orderStatusCounts)),
                title: {
                    text: 'Order Status'
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
@endpush




