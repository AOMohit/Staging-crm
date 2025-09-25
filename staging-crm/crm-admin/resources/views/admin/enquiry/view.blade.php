@extends('admin.inc.layout')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-header">
                <h3>Enquiry Details</h3>
                <hr>

                <h4>
                    Enquired By : {{ $data->customer->first_name . ' ' . $data->customer->last_name }}
                </h4>
                <h4>
                    Phone : {{ $data->customer->phone ?? null }}
                </h4>
                <h4>
                    Email : {{ $data->customer->email }}
                </h4>
                <h4>
                    Expedition: {{ $data->trip->name ?? $data->expedition}}
                </h4>
                @if($data->tailor_made_comment)
                    <h4>
                        Comment :{{$data->tailor_made_comment}}
                    </h4>
                @endif
                @if ($data->redeem_points && $data->redeem_points > 0)
                    <h4>
                        Points To Redeem : {{ $data->redeem_points }}
                    </h4>
                @endif
                <hr>

                <h4>Travelers List:</h4>
                <div class="row">
                    @if ($data->traveler)
                        @foreach (json_decode($data->traveler) as $traveler)
                            <div class="col-md-4">
                                <div class="card shadow">
                                    <div class="p-3">
                                        <p><b>Type :</b> {{ $traveler->checkedValue ?? null }}</p>
                                        <p><b>Name :</b> {{ $traveler->name ?? null }}</p>
                                        <p><b>Age :</b> {{ $traveler->dob ?? null }}</p>
                                        <p><b>Gender :</b> {{ $traveler->gender ?? null }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--/ DataTable with Buttons -->
@endsection
