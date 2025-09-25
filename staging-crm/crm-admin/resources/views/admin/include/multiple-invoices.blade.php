@if ($bookingInvoices->count() > 0)
<div class="modal fade" id="invoicesModel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Invoices</h5>
                </div>
                <div class="card-body">
                    @php $index = 1; @endphp
                    <ul id="invoiceList">
                        @foreach ($bookingInvoices as $invoice)
                            <li style="margin-bottom: 10px;">
                                <span>
                                    <a target="_blank" href="{{ url('storage/app/' . $invoice->invoice_path) }}">
                                        {{ $invoice->invoice_name }}
                                    </a>
                                    @if ($invoice->invoice_status === null )
                                        @if(auth()->user()->id != $invoice->invoice_sent_by)
                                            <a id="acceptLink-{{ $invoice->id }}"
                                               class="styled-button accept-link"
                                               onclick="toggleAccept({{ $invoice->id }})">Accept</a>
                                            <a id="rejectLink-{{ $invoice->id }}"
                                               class="styled-button reject-link"
                                               onclick="toggleReject({{ $invoice->id }})">Reject</a>
                                        @else
                                            <p>You sent this invoice, waiting for the recipient's action.</p>
                                        @endif
                                    @elseif($invoice->invoice_status == 1)
                                        <a class="tick-mark" style="color: green;">
                                            <i class="mdi mdi-checkbox-marked-circle-outline"></i>
                                        </a>
                                    @elseif ($invoice->invoice_status == 0)
                                        <a class="cross-mark" style="color: red; margin-right: 8px;">
                                            <i class="mdi mdi-close-circle-outline"></i>
                                        </a>
                                        @if(auth()->user()->id == $invoice->invoice_sent_by)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="22" viewBox="0 0 20 22" fill="none">
                                                <path d="M6.45166 6.45142H11.613V7.74174H6.45166V6.45142Z" fill="#FFB224" />
                                                <path d="M6.45166 9.03223H11.613V10.3225H6.45166V9.03223Z" fill="#FFB224" />
                                                <path d="M6.45166 3.87085H11.613V5.16117H6.45166V3.87085Z" fill="#FFB224" />
                                                <path d="M6.45166 15.4838H10.0096C10.3381 14.5575 10.8952 13.7386 11.613 13.0954V11.6128H6.45166V15.4838Z" fill="#FFB224" />
                                                <path d="M9.71346 16.7742C7.49265 16.7742 4.74865 16.7742 2.58065 16.7742C2.58065 16.4409 2.58065 4.39389 2.58065 2.58064H12.9032V12.219C13.6811 11.8314 14.5574 11.6129 15.4839 11.6129V0H0V21.9355H11.8388C10.3001 20.6912 9.49265 18.7622 9.71346 16.7742ZM6.45161 19.3548H2.58065V18.0645H6.45161V19.3548Z" fill="#FFB224" />
                                                <path d="M3.87091 6.45142H5.16123V7.74174H3.87091V6.45142Z" fill="#FFB224" />
                                                <path d="M3.87091 3.87085H5.16123V5.16117H3.87091V3.87085Z" fill="#FFB224" />
                                                <path d="M3.87091 11.6128H5.16123V12.9031H3.87091V11.6128Z" fill="#FFB224" />
                                                <path d="M3.87091 9.03223H5.16123V10.3225H3.87091V9.03223Z" fill="#FFB224" />
                                                <path d="M3.87091 14.1934H5.16123V15.4837H3.87091V14.1934Z" fill="#FFB224" />
                                                <path d="M15.4838 12.9031C12.9936 12.9031 10.9677 14.929 10.9677 17.4192C10.9677 19.9094 12.9936 21.9353 15.4838 21.9353C17.974 21.9353 20 19.9094 20 17.4192C20 14.929 17.974 12.9031 15.4838 12.9031ZM15.4838 20.13L13.1905 18.6011L13.9063 17.5275L14.8387 18.1492V14.8386H16.129V18.1492L17.0615 17.5276L17.7773 18.6012L15.4838 20.13Z" fill="#FFB224" />
                                            </svg>
                                            <a href="javascript:void(0)" onclick="openFileManager({{ $invoice->id }})">
                                                <span class="font-size-14">Upload Invoice</span>
                                            </a>
                                            <!-- Hidden File Input -->
                                            <input accept=".pdf" type="file" id="fileUploader-{{ $invoice->id }}" style="display: none;" onchange="handleFileSelect({{ $invoice->id }})">

                                            <!-- Display Selected File Name -->
                                            <span id="fileNameDisplay-{{ $invoice->id }}" style="margin-left: 10px; font-size: 14px;"></span>

                                            <!-- Upload Button -->
                                            <button id="uploadButton-{{ $invoice->id }}"
                                                    class="btn btn-primary btn-sm hidden"
                                                    style="margin-left: 10px;"
                                                    onclick="handleFileUpload(document.getElementById('fileUploader-{{ $invoice->id }}'), {{ $invoice->id }})">
                                                Upload
                                            </button>
                                        @endif
                                        @if($invoice->comment != '')
                                            <p>{{$invoice->comment}}</p>
                                        @endif
                                    @endif
                                </span>
                                <div id="commentBox-{{ $invoice->id }}"
                                     class="comment-box"
                                     style="display: none; margin-top: 10px;">
                                    <textarea class="form-control"
                                              placeholder="Enter your comment" rows="3"> {{$invoice->comment}} </textarea>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @if ($bookingInvoices->isNotEmpty() && auth()->user()->id != $bookingInvoices->first()->invoice_sent_by)
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-success" onclick="submitInvoices()">Submit</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    let approvedInvoices = [];
    let rejectedInvoices = [];

    function toggleAccept(invoiceId) {
        const acceptLink = document.getElementById(`acceptLink-${invoiceId}`);
        const rejectLink = document.getElementById(`rejectLink-${invoiceId}`);
        const commentBox = document.getElementById(`commentBox-${invoiceId}`);

        if (approvedInvoices.includes(invoiceId)) {
            // Undo acceptance
            approvedInvoices = approvedInvoices.filter(id => id !== invoiceId);
            acceptLink.textContent = "Accept";
            acceptLink.classList.remove("active");
        } else {
            // Accept the invoice
            approvedInvoices.push(invoiceId);
            acceptLink.textContent = "Accepted";
            acceptLink.classList.add("active");

            // Reset reject button
            rejectedInvoices = rejectedInvoices.filter(id => id !== invoiceId);
            rejectLink.textContent = "Reject";
            rejectLink.classList.remove("active");
            commentBox.style.display = "none";
        }
    }

    function toggleReject(invoiceId) {
        const acceptLink = document.getElementById(`acceptLink-${invoiceId}`);
        const rejectLink = document.getElementById(`rejectLink-${invoiceId}`);
        const commentBox = document.getElementById(`commentBox-${invoiceId}`);

        if (rejectedInvoices.includes(invoiceId)) {
            // Undo rejection
            rejectedInvoices = rejectedInvoices.filter(id => id !== invoiceId);
            rejectLink.textContent = "Reject";
            rejectLink.classList.remove("active");
            commentBox.style.display = "none";
        } else {
            // Reject the invoice
            rejectedInvoices.push(invoiceId);
            rejectLink.textContent = "Rejected";
            rejectLink.classList.add("active");

            // Reset accept button
            approvedInvoices = approvedInvoices.filter(id => id !== invoiceId);
            acceptLink.textContent = "Accept";
            acceptLink.classList.remove("active");
            commentBox.style.display = "block";
        }
    }

    function showCommentBox(invoiceId) {
        const commentBox = document.getElementById(`commentBox-${invoiceId}`);
        if( commentBox.style.display === "block" ){
            commentBox.style.display = "none";
        }else{
            commentBox.style.display = "block";
        }

    }

    function submitInvoices() {
        if (approvedInvoices.length === 0 && rejectedInvoices.length === 0) {
            alert("No invoices selected for submission.");
            return;
        }

        const approvedInvoicesArray = approvedInvoices.map(invoiceId => {
            return {
                invoiceId,
            };
        });

        const rejectedWithComments = rejectedInvoices.map(invoiceId => {
            const commentBox = document.querySelector(`#commentBox-${invoiceId} textarea`);
            return {
                invoiceId,
                comment: commentBox.value.trim(),
            };
        });

        const data = {
            approvedInvoices: approvedInvoicesArray,
            rejectedInvoices: rejectedWithComments,
            "_token": "{{ csrf_token() }}",
            'id': {{ $bookingId }}
        };

        console.log("Submitting Data:", data);

        $.ajax({
            url: "{{ route('booking.trip.upload-multi-invoice-action') }}",
            type: "POST",
            data: data,
            success: function (result) {
                $("#invoicesModel").modal("hide");
                Toast.fire({
                    icon: "success",
                    title: "Invoice submitted successfully."
                });
                setTimeout(function () {
                        location.reload();
                    }, 2000);

            },
            error: function (xhr, status, error) {
                console.error("Error submitting invoices:", xhr.responseText);
                alert("An error occurred while submitting invoices.");
            }
        });
    }

    function openFileManager(invoiceId) {
        document.getElementById('fileUploader-' + invoiceId).click();
    }

    // Function to handle the file selection and associate it with the invoice ID
    function handleFileUpload(input, invoiceId) {

        if (input.files && input.files[0]) {
            const file = input.files[0];
            console.log('Selected file for Invoice ID:', invoiceId);
            console.log('File name:', file.name);

            const formData = new FormData();
            formData.append('id', {{ $bookingId }});
            formData.append('invoice_id', invoiceId);
            formData.append('invoice_files', file);
            formData.append('_token', "{{ csrf_token() }}");

            $.ajax({
                url: "{{ route('booking.trip.upload-invoice') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (result) {
                    $("#invoicesModel").modal("hide");
                    Toast.fire({
                        icon: "success",
                        title: "Updated invoice uploaded."
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                },
                error: function (xhr, status, error) {
                    console.error("Error uploading invoice:", xhr.responseText);
                    alert("An error occurred while uploading the invoice.");
                }
            });
        } else {
            alert('Please select a file to upload.');
        }
    }

    function handleFileSelect(invoiceId) {
        const fileInput = document.getElementById('fileUploader-' + invoiceId);
        const fileNameDisplay = document.getElementById('fileNameDisplay-' + invoiceId);
        const uploadButton = document.getElementById('uploadButton-' + invoiceId);

        if (fileInput.files.length > 0) {
            // Display the selected file name
            fileNameDisplay.textContent = fileInput.files[0].name;

            // Show the upload button
            uploadButton.style.display = 'inline-block';

            uploadButton.classList.remove('hidden');
        }
    }

</script>

<style>
    .styled-button {
        display: inline-block;
        background-color: #f8f9fa; /* Light gray background */
        color: #6c757d; /* Gray text */
        padding: 5px 10px; /* Padding for button size */
        border-radius: 5px; /* Rounded corners */
        text-decoration: none; /* Remove underline */
        font-size: 12px; /* Adjust font size */
        cursor: pointer; /* Pointer cursor */
        margin-left: 10px; /* Space between buttons */
        border: 1px solid #ddd; /* Optional border for button look */
    }

    .styled-button:hover {
        background-color: #e2e6ea; /* Slightly darker gray on hover */
        color: #5a6268; /* Slightly darker text */
    }

    .accept-link.active {
        background-color: #28a745; /* Green background for accepted */
        color: white; /* White text */
    }

    .reject-link.active {
        background-color: #dc3545; /* Red background for rejected */
        color: white; /* White text */
    }
    .hidden {
        display: none !important;
    }
</style>
