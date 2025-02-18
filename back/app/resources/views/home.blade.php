@extends('layouts.app')

@section('content')
    <div class="row">
        <!-- Card for displaying visitors -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Online Store Visitors</h3>
                        <a href="javascript:void(0);">View Report</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">{{ $visitorsCount }}</span>
                            <span>Visitors Over Time</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                            <span class="text-success">
                                <i class="fas fa-arrow-up"></i> {{ $visitorGrowth }}%
                            </span>
                            <span class="text-muted">Since last week</span>
                        </p>
                    </div>

                    <div class="position-relative mb-4">
                        <canvas id="visitors-chart" height="200"></canvas>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                        <span class="mr-2">
                            <i class="fas fa-square text-primary"></i> This Week
                        </span>

                        <span>
                            <i class="fas fa-square text-gray"></i> Last Week
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card for displaying products -->
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Products</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Sales</th>
                            <th>Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-circle img-size-32 mr-2">
                                    {{ $product->name }}
                                </td>
                                <td>${{ $product->price }} USD</td>
                                <td>
                                    <small class="text-success mr-1">
                                        <i class="fas fa-arrow-up"></i>
                                        {{ $product->sales }}%
                                    </small>
                                    {{ $product->sold }} Sold
                                </td>
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="text-muted">
                                        <i class="fas fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Card for displaying sales -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Sales</h3>
                        <a href="javascript:void(0);">View Report</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">${{ $salesTotal }}</span>
                            <span>Sales Over Time</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                            <span class="text-success">
                                <i class="fas fa-arrow-up"></i> {{ $salesGrowth }}%
                            </span>
                            <span class="text-muted">Since last month</span>
                        </p>
                    </div>

                    <div class="position-relative mb-4">
                        <canvas id="sales-chart" height="200"></canvas>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                        <span class="mr-2">
                            <i class="fas fa-square text-primary"></i> This Year
                        </span>

                        <span>
                            <i class="fas fa-square text-gray"></i> Last Year
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data for the visitors chart
        var visitorsChartCtx = document.getElementById('visitors-chart').getContext('2d');
        var visitorsChart = new Chart(visitorsChartCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($visitorsLabels) !!}, // Labels for the chart
                datasets: [{
                    label: 'This Week',
                    borderColor: '#007bff',
                    data: {!! json_encode($visitorsDataThisWeek) !!}, // Data for this week
                    fill: false,
                }, {
                    label: 'Last Week',
                    borderColor: '#ced4da',
                    data: {!! json_encode($visitorsDataLastWeek) !!}, // Data for last week
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Data for the sales chart
        var salesChartCtx = document.getElementById('sales-chart').getContext('2d');
        var salesChart = new Chart(salesChartCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesLabels) !!}, // Labels for the chart
                datasets: [{
                    label: 'This Year',
                    borderColor: '#28a745',
                    data: {!! json_encode($salesDataThisYear) !!}, // Data for this year
                    fill: false,
                }, {
                    label: 'Last Year',
                    borderColor: '#ced4da',
                    data: {!! json_encode($salesDataLastYear) !!}, // Data for last year
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
