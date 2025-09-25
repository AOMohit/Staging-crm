@extends('admin.inc.layout')

@section('content')
    <style>
        h6 {
            font-size: 0.8em;
        }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            <!-- Cards with few info -->
            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.booking-by-trip') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Bookings By Trip</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.booking-by-traveler') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Bookings By Traveler</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.booking-by-traveler') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Bookings By Location</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.booking-by-agent') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Bookings By Agents</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.total-trips') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Total Trips</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.total-bookings') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Total Bookings</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.total-customers') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Total Customers</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.total-agents') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Total Agents</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.total-vendors') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Total Vendors</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.total-inventory') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Total Inventory</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.total-loyalty') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Total Gifted Loyalty Points</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.customer-by-location') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Customers By Location</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.payment-by-type') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Payment By Type</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div> --}}

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.expense-by-trip') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Expense By Trip</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- need to work --}}
            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.net-receivables') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Net Receivables</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.loyalty-points-redeemed') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0"> Loyalty Points Redeemed</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.loyalty-points-available') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Loyalty Points Available </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.booking-by-lead') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Booking by Lead Source </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.booking-by-gender') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Booking by Gender </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.booking-by-shirt') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Booking by T-shirt </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.sustainability') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Sustainability</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.ongoing-trip') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">UpComing Trip Revenue Report </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{ route('report.export.sent-invoice') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Sent Invoices Data</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-sm-6">
                <a href="{{route('report.export.pending-invoice')}}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="avatar me-3">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-download-outline mdi-24px"> </i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Pending Invoices Data</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


        </div>
    </div>
    <!--/ DataTable with Buttons -->
@endsection
