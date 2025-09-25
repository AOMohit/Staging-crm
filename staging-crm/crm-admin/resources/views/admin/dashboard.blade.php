@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row mb-2 d-flex justify-content-end">
            <div class="col-2">
                <label for="">Filter</label>
                <select id="filter" class="form-control">
                    <option value="">Select</option>
                    <option @if (request()->filter == 'Daily') selected @endif value="Daily">Daily</option>
                    <option @if (request()->filter == 'Weekly') selected @endif value="Weekly">Weekly</option>
                    <option @if (request()->filter == 'Monthly') selected @endif value="Monthly">Monthly</option>
                    <option @if (request()->filter == 'Yearly') selected @endif value="Yearly">Yearly</option>
                </select>
            </div>
            <div class="col-2">
                <label for="">From</label>
                <input value="{{ request()->input('from_date') }}" id="from_date" type="date" class="form-control">
            </div>
            <div class="col-2">
                <label for="">To</label>
                <input value="{{ request()->input('to_date') }}" id="to_date" type="date" class="form-control">
            </div>
            <div class="col-2">
                <div class="row" style="margin-right: 4px;">
                    <button class="btn btn-warning mt-4" onclick="filterDashboard()">Filter</button>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            <!-- Cards with few info -->
            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('trip.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <span class="mdi mdi-plane-car"></span>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h4 class="mb-0">{{ number_format($tripCount) }}</h4>
                                    </div>
                                    <small class="text-muted"> Trips</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('booking.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <span class="mdi mdi-book-account"></span>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h4 class="mb-0">{{ number_format($bookingCount) }}</h4>
                                    </div>
                                    <small class="text-muted"> Bookings</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('customer.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <span class="mdi mdi-account-group"></span>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h4 class="mb-0">{{ number_format($customerCount) }}</h4>
                                    </div>
                                    <small class="text-muted"> Customers</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('vendors.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <span class="mdi mdi-store"></span>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h4 class="mb-0">{{ number_format($vendorCount) }}</h4>
                                    </div>
                                    <small class="text-muted"> Vendors</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('agent.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <span class="mdi mdi-face-agent"></span>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h4 class="mb-0">{{ number_format($agentCount) }}</h4>
                                    </div>
                                    <small class="text-muted"> Agents</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('inventory.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <span class="mdi mdi-shopping"></span>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h4 class="mb-0">{{ number_format($inventoryCount) }}</h4>
                                    </div>
                                    <small class="text-muted"> Inventory</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('staff.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-account-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h4 class="mb-0">{{ number_format($userCount) }}</h4>
                                    </div>
                                    <small class="text-muted"> Teams</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('loyalty.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <span class="mdi mdi-star-four-points-circle-outline"></span>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h4 class="mb-0">{{ number_format($lpTotal) }}</h4>
                                    </div>
                                    <small class="text-muted">Loyalty Points</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Sales Overview-->
            <div class="col-lg-6">
                {{-- <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h4 class="mb-2">Sales Overview</h4>
                        </div>
                        <div class="d-flex align-items-center">
                            <small class="me-2">Total {{ number_format($bookingCount) }} Trip Bookings</small>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between flex-wrap gap-3">
                        <div class="d-flex gap-3">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-account-outline mdi-24px"></i>
                                </div>
                            </div>
                            <div class="card-info">
                                <h4 class="mb-0">{{ number_format($customerCount) }}</h4>
                                <small class="text-muted">Customers</small>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-info rounded">
                                    <i class="mdi mdi-trending-up mdi-24px"></i>
                                </div>
                            </div>
                            <div class="card-info">
                                <h4 class="mb-0">₹{{ number_format(totalTripCost()) }}</h4>
                                <small class="text-muted">Total Revenue</small>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-warning rounded">
                                    <i class="mdi mdi-poll mdi-24px"></i>
                                </div>
                            </div>
                            <div class="card-info">
                                <h4 class="mb-0">₹{{ number_format(totalTripProfit()) }}</h4>
                                <small class="text-muted">Profit</small>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Tiers</h5>
                    </div>
                    <div class="card-body row widget-separator">
                        <div class="col-sm-12 g-2 text-nowrap d-flex flex-column justify-content-between px-4 gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <small>Discovery</small>
                                <div class="progress w-100 rounded" style="height:10px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ getTierPerByCustomer($discovery) }}%"
                                        aria-valuenow="{{ getTierPerByCustomer($discovery) }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="w-px-20 text-end">{{ number_format($discovery) }}</small>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <small>Adventurer</small>
                                <div class="progress w-100 rounded" style="height:10px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ getTierPerByCustomer($adventurer) }}%"
                                        aria-valuenow="{{ getTierPerByCustomer($adventurer) }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                                <small class="w-px-20 text-end">{{ number_format($adventurer) }}</small>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <small>Explorer</small>
                                <div class="progress w-100 rounded" style="height:10px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ getTierPerByCustomer($explorer) }}%"
                                        aria-valuenow="{{ getTierPerByCustomer($explorer) }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                                <small class="w-px-20 text-end">{{ number_format($explorer) }}</small>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <small>Legends</small>
                                <div class="progress w-100 rounded" style="height:10px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ getTierPerByCustomer($legends) }}%"
                                        aria-valuenow="{{ getTierPerByCustomer($legends) }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                                <small class="w-px-20 text-end">{{ number_format($legends) }}</small>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!--/ Sales Overview-->
            <div class="col-lg-6 ">
                <div class="card h-100">

                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Popular Trips</h5>
                    </div>
                    <div class="card-body">
                        <ul class="p-0 m-0">
                            @foreach ($popularTrips as $pt)
                                <li class="d-flex mb-3">
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">{{ $pt->trip_name }}</h6>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="fw-semibold text-heading">{{ number_format($pt->total_bookings) }}</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 ">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Booking Overview</h5>
                    </div>
                    <div class="card-body">
                        <ul class="p-0 m-0">
                            @foreach ($tripOverview as $to)
                                <li class="d-flex mb-3">
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">{{ $to->trip_status }}</h6>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="fw-semibold text-heading">{{ number_format($to->trip_status_count) }}</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 ">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Customers Data by Destination</h5>
                    </div>
                    <div class="card-body">
                        <ul class="p-0 m-0">
                            @foreach ($customerCounts as $cc)
                                <li class="d-flex mb-3">
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">{{ $cc->country }}</h6>
                                            <span>{{ $cc->state }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="fw-semibold text-heading">{{ number_format($cc->customer_count) }}</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card h-100" id="chart">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            getTripStat();
        });

        function filterDashboard() {
            var filter = $("#filter").val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var fullUrl = "{{ url()->current() }}";

            window.location.href = fullUrl + "?filter=" + filter + "&from_date=" + from_date + "&to_date=" + to_date;
        }
    </script>

    <script>
        function getTripStat() {
            var filter = $("#filter").val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();

            $.ajax({
                url: "{{ route('tripstat') }}",
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    token: "{{ request()->token }}",
                    filter: filter,
                    from_date: from_date,
                    to_date: to_date
                },
                success: function(res) {
                    var data = JSON.parse(res);
                    var trips = [];
                    var tripCounts = [];
                    $.each(data, function(key, val) {
                        trips.push(key);
                        tripCounts.push(val);
                    });

                    var options = {
                        series: [{
                            name: 'Bookings Count',
                            data: tripCounts
                        }],
                        chart: {
                            height: 350,
                            type: 'bar',
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: '50%',
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            width: 2
                        },

                        grid: {
                            row: {
                                colors: ['#fff', '#f2f2f2']
                            }
                        },
                        xaxis: {
                            labels: {
                                rotate: -45
                            },
                            categories: trips,
                            tickPlacement: 'on'
                        },
                        yaxis: {
                            title: {
                                text: 'Bookings Count',
                            },
                        },
                    };

                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                }
            });
            return tripData;
        }
    </script>
@endsection
