
@php
    $hasAtLeastOneValidStatus = $bookingInvoices->contains(function ($invoice) {
        return in_array($invoice->invoice_status, [0, 1]);
    });
    $hasSameSender = collect($bookingInvoices)->contains(function ($invoice) {
        return auth()->user()->id == $invoice->invoice_sent_by;
    });
@endphp

<span>
    @if ($hasAtLeastOneValidStatus)
        @if($data->invoice_status != 'Sent')
            <span><a href="javaScript:void(0)" onclick="showInvoices()">Click here </a> to see
            current status</span>
        @else
            @if($data->invoice_file != null)
                <a target="_blank" href="{{ url('storage/app/' . $data->invoice_file) }}">Click
                here</a> to see invoice
            @else
                <span><a href="javaScript:void(0)" onclick="showInvoices()">Click here </a> to see
                invoices </span>
            @endif
        @endif
    @else
        <span><a href="javaScript:void(0)" onclick="showInvoices()">Click here </a> to see
        uploaded invoices</span>
    @endif

<br>

    @if($data->invoice_status != 'Sent')
        @php
            // Check if all invoices have status 1
            $allInvoicesApproved = $bookingInvoices->every(function ($invoice) {
                return $invoice->invoice_status == 1;
            });

            // Check if the current user sent any invoices
            $currentUserSentAnyInvoice = $bookingInvoices->contains(function ($invoice) {
                return auth()->user()->id == $invoice->invoice_sent_by;
            });
        @endphp

        @if ($allInvoicesApproved && !$currentUserSentAnyInvoice)

{{--                <span><a href="javaScript:void(0)" onclick="takeActionClicked()">Click here </a> to send--}}
{{--                    email to customer</span>--}}
                <a id="takeActionButton" class="btn btn-primary btn-sm mt-1 mb-1 waves-effect waves-light" onclick="takeActionClicked()" href="javascript:void(0)">
                Send invoice to customer 
                <img src="{{ url('public/admin') }}/assets/img/loader.gif" alt="Ratings" class="loader " width="50">
                </a>
{{--                <div id="loadingSpinner" class="spinner-border text-primary" role="status" style="display: none; margin-left: 10px;">--}}
{{--                    <span class="sr-only">Loading...</span>--}}
{{--                </div>--}}
        @endif

    @else
        <p>All the updated invoices are sent to Customer.</p>
    @endif
</span>
<style>
     .loader{
        display:none;
        width: 30;
        height: 30;
    }
</style>
<script>
   function takeActionClicked() {
        // const loadingSpinner = document.getElementById('loadingSpinner');
        // const takeActionButton = document.getElementById('takeActionButton');
        // takeActionButton.style.display = 'none';
        // loadingSpinner.style.display = 'inline-block';
        // console.log('takeActionButton:', takeActionButton);
        // console.log('loadingSpinner:', loadingSpinner);
        const data = {
            "_token": "{{ csrf_token() }}",
            'id': {{ $bookingId }}
        };
        $.ajax({
            url: "{{ route('booking.trip.upload-invoice-action') }}",
            type: "POST",
            data: data,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function (result) {

                $("#invoicesModel").modal("hide");
                if (result.success) {
                    $('.loader').hide();
                    if (result.errors && result.errors.length > 0) {
                        let errorMessage = '';
                        if (result.errors.length == 1) {
                            errorMessage = 'Invoice sent successfully but:\n' + result.errors.join('\n');
                            Toast.fire({
                                icon: 'success',
                                title: errorMessage,
                                width: '600px',        
                                position: 'top-center',
                                maxWidth: '100%',
                                padding: '20px',
                            });
                        } else if (result.errors.length == 2) {
                            errorMessage = 'Invoice sent successfully but\n' + result.errors.join('\n');
                            Toast.fire({
                                icon: 'success',
                                title: errorMessage,
                                width: '600px',
                                position: 'top-center',
                                maxWidth: '100%',
                                padding: '20px', 
                            });
                        } else if (result.errors.length == 3) {
                            errorMessage = 'Invoice sent successfully but\n' + result.errors.join('\n');
                            Toast.fire({
                                icon: 'success',
                                title: errorMessage,
                                width: '600px',
                                position: 'top-center',  
                                maxWidth: '100%',
                                padding: '20px',
                            });
                        } else {
                            errorMessage = 'Multiple emails could not be sent. Please try again later.\n' + result.errors.join('\n');
                            Toast.fire({
                                icon: 'error',
                                title: errorMessage,
                                width: '600px',
                                position: 'top-center',
                                maxWidth: '100%',      
                                padding: '20px', 
                            });
                        }
                    } else {
                        Toast.fire({
                            icon: 'success',
                            title: 'All invoices sent successfully.',
                            width: '600px',
                            position: 'top-center', 
                            maxWidth: '100%',      
                            padding: '20px', 
                        });
                    }
                }
            },
            error: function (xhr, status, error) {
                $('.loader').hide();
                let responseError=  JSON.parse(xhr.responseText);
                console.log(responseError.error);
                Toast.fire({
                    icon: 'error',
                    title: responseError.error,
                    width: '600px',
                    position: 'top-center',
                    maxWidth: '100%',
                    padding: '20px', 
                });
            }
        });
    }

</script>
{{--@if ($tripInvoices->isNotEmpty() > 0 && $bookingInvoices)--}}
{{--    @if ($data->invoice_sent_date == null)--}}
{{--        @if (auth()->user()->id == $bookingInvoice->invoice_sent_by)--}}
{{--            <small>--}}
{{--                @if ($bookingInvoice->invoice_status == '0')--}}
{{--                    <span class="text-danger">Kindly Edit and Re-upload the--}}
{{--                        invoice</span><strong>: Verified By--}}
{{--                        {{ $bookingInvoice->staffVerifiedBy->name }}</strong> <br>--}}
{{--                    @if ($bookingInvoice->comment != null)--}}
{{--                        <span><strong>Comment:</strong>--}}
{{--                            <span class="text-danger">{{ $bookingInvoice->comment }}</span></span>--}}
{{--                    @endif--}}
{{--                @elseif ($bookingInvoice->invoice_status == null)--}}
{{--                    <span>Invoice Sent and Waiting for Verification.</span>--}}
{{--                @endif--}}
{{--                <br>--}}
{{--                    <div>--}}
{{--                        <span><a href="javaScript:void(0)" onclick="showInvoices()">Click here </a> to see invoices</span>--}}
{{--                    </div>--}}
{{--            </small>--}}
{{--        @else--}}
{{--            <small>--}}
{{--                <span class="text-danger">New Invoice @if ($bookingInvoice->invoice_reuploaded)--}}
{{--                        Re-Uploaded--}}
{{--                    @else--}}
{{--                        Uploaded--}}
{{--                    @endif--}}
{{--                </span><strong>: By--}}
{{--                    {{ $bookingInvoice->staffSentBy->name }}</strong> <br>--}}
{{--                <span>--}}
{{--                    <span><a href="javaScript:void(0)" onclick="showInvoices()">Click here </a> to see invoices</span>--}}
{{--                <br>--}}
{{--                @if ($bookingInvoice->comment != null)--}}
{{--                    <span><strong>Comment:</strong>--}}
{{--                        <span class="text-danger">{{ $bookingInvoice->comment }}</span></span>--}}
{{--                @endif--}}
{{--            </small>--}}
{{--        @endif--}}
{{--    @else--}}
{{--        <span>--}}
{{--            @foreach ($tripInvoices as $invoice)--}}
{{--                <a target="_blank" href="{{ url('storage/app/' . $invoice->invoice_path) }}">Click--}}
{{--                here</a> to see--}}
{{--                Uploaded Invoice--}}
{{--            @endforeach--}}
{{--        </span>--}}

{{--    @endif--}}
{{--@endif--}}

