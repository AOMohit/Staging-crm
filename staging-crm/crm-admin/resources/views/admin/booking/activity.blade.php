@extends('admin.inc.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a
                    href="{{ route('booking.index') }}">Bookings</a>/</span>
            Activity > {{ $trip_name }}</h4>
        <!-- DataTable with Buttons -->
        <div class="col-12">
            <!-- Activity Timeline -->
            <div class="card card-action mb-4">
                <div class="card-header align-items-center">
                    <h5 class="card-action-title mb-0">
                        <i class="mdi mdi-format-list-bulleted mdi-24px me-2"></i>Activity Timeline
                    </h5>
                </div>
                <div class="card-body pt-3 pb-0">
                    <ul class="timeline mb-0">

                        @foreach ($data as $key => $item)
                            <li
                                class="timeline-item timeline-item-transparent @if ($key + 1 == count($data)) border-0 @endif">
                                <span class="timeline-point timeline-point-warning"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 style="cursor: pointer;" onclick='getFullActivity({{ $item->id }})' class="mb-0">
                                            {!! $item->action !!}</h6>
                                        <span class="text-muted">{{ $item->created }}</span>
                                    </div>
                                    <div class="d-flex flex-wrap">
                                        <div>
                                            <h6 class="mb-0">By: {{ $item->admin->name }}</h6>
                                            <span class="text-muted">Action: {{ $item->page }}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>

        </div>
    </div>
    <!--/ DataTable with Buttons -->

    <!-- Modal for Full Activity -->
    <div class="modal fade" id="full_activityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="full_activity" style="overflow-wrap: anywhere; overflow: auto;">

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function getFullActivity(id) {
            $.ajax({
                url: "{{ route('booking.activity-get') }}",
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id
                },
                success: function(res) {
                    if (res) {
                        $("#full_activity").html(res);
                        $("#full_activityModal").modal('show');
                    } else {
                        $("#full_activity").html('');
                        $("#full_activityModal").modal('hide');
                    }
                }
            });
        }
    </script>
@endsection
