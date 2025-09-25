<div class="modal fade" id="manualTaxPopup" tabindex="-1" role="dialog" aria-labelledby="manualTaxPopupLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manualTaxPopupLabel">Customer Details</h5>
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true">&times;</span>--}}
{{--                </button>--}}
            </div>
            <div class="modal-body">
                <form id="manualTaxForm">
                    @csrf
                    <div id="manualTaxCustomers">
                        <!-- Customer-specific fields will be injected here by JavaScript -->
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-primary" onclick="saveManualTax()">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    function renderManualTaxPopup(customers) {
        let customerHtml = '';

        customers.forEach(customer => {
            const customerId = customer.customer_id ? customer.customer_id : customer.id;

            customerHtml += `
        <div class="customer-section mb-4" data-customer-id="${customerId}">
            <h6 class="mb-3">${customer.first_name} ${customer.last_name}</h6>
            <input type="hidden" name="customer_id" value="${customerId}">

            <!-- First Set: Amount and TCS -->
            <div class="row align-items-center">
                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input
                            type="number"
                            name="manual_amount_1[${customerId}]"
                            class="form-control"
                            placeholder="Enter Amount"
                            value="${customer.amount_1 || ''}">
                        <label for="amount_1_${customerId}">Enter Amount</label>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <select class="form-control" id="tcs_1_${customerId}" name="tcs_1[${customerId}]">
                            <option value="" disabled ${!customer.tcs_1 ? 'selected' : ''}>TCS</option>
                            <option value="5" ${customer.tcs_1 == 5 ? 'selected' : ''}>5%</option>
                            <option value="20" ${customer.tcs_1 == 20 ? 'selected' : ''}>20%</option>
                        </select>
                        <label for="tcs_1_${customerId}">TCS</label>
                    </div>
                </div>
            </div>

            <!-- Checkbox for Multiple TCS -->
            <div class="form-check mb-3">
                <input
                    class="form-check-input multiple-tcs-checkbox"
                    type="checkbox"
                    id="multiple_tcs_${customerId}"
                    data-customer-id="${customerId}"
                    ${customer.amount_2 || customer.tcs_2 ? 'checked' : ''}>
                <label class="form-check-label" for="multiple_tcs_${customerId}">
                    Multiple TCS
                </label>
            </div>

            <!-- Second Set: Amount and TCS -->
            <div class="row align-items-center second-row" id="second_row_${customerId}" style="display: ${customer.amount_2 || customer.tcs_2 ? 'flex' : 'none'};">
                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input
                            type="number"
                            name="manual_amount_2[${customerId}]"
                            class="form-control"
                            placeholder="Enter Amount"
                            value="${customer.amount_2 || ''}">
                        <label for="amount_2_${customerId}">Enter Amount</label>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <select class="form-control" id="tcs_2_${customerId}" name="tcs_2[${customerId}]">
                            <option value="" disabled ${!customer.tcs_2 ? 'selected' : ''}>TCS</option>
                            <option value="5" ${customer.tcs_2 == 5 ? 'selected' : ''}>5%</option>
                            <option value="20" ${customer.tcs_2 == 20 ? 'selected' : ''}>20%</option>
                        </select>
                        <label for="tcs_2_${customerId}">TCS</label>
                    </div>
                </div>
            </div>
            <hr>
        </div>`;
        });

        // Inject the generated HTML into the modal body
        $("#manualTaxCustomers").html(customerHtml);

        // Attach event listeners for checkboxes
        attachCheckboxListeners();

        getSelectedCustomers();

        // Show the modal
        $("#manualTaxPopup").modal("show");
    }

    function attachCheckboxListeners() {
        $(".multiple-tcs-checkbox").off("change").on("change", function () {
            const customerId = $(this).data("customer-id");
            const secondRow = $(`#second_row_${customerId}`);

            if ($(this).is(":checked")) {
                secondRow.show();
            } else {
                secondRow.hide();
            }
        });
    }

    function saveManualTax() {
        const customers = [];
        const token = "{{ request()->token }}";

        // Loop through each customer section in the form
        $("#manualTaxCustomers .customer-section").each(function () {
            const customerId = $(this).data("customer-id");
            const manualAmount1 = $(this).find(`input[name^="manual_amount_1"]`).val();
            const tcs1 = $(this).find(`select[name^="tcs_1"]`).val();
            const manualAmount2 = $(this).find(`input[name^="manual_amount_2"]`).val();
            const tcs2 = $(this).find(`select[name^="tcs_2"]`).val();

            // Only push to customers array if at least one field is filled
            if (manualAmount1 || tcs1 || manualAmount2 || tcs2) {
                customers.push({
                    customer_id: customerId,
                    manual_amount_1: manualAmount1 || null, // Default to null if empty
                    tcs_1: tcs1 || null,
                    manual_amount_2: manualAmount2 || null,
                    tcs_2: tcs2 || null
                });
            }
        });

        // If no data is entered for any customer, prevent submission
        if (customers.length === 0) {
            alert("Please enter data for at least one customer.");
            return; // Stop further execution
        }

        // Prepare the request payload
        const data = {
            "_token": "{{ csrf_token() }}",
            token: token, // Pass the token to fetch booking ID on the backend
            customers: customers
        };

        console.log("Request Payload:", data); // Debugging: Log the JSON payload

        // Make the AJAX POST request
        $.ajax({
            url: "{{ route('booking.saveCustomerManualTax') }}",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function (res) {
                console.log(res);
                if (res.success) {
                    alert("Manual tax details saved successfully.");
                    $("#manualTaxPopup").modal("hide");
                    // saveCosts(true);
                    window.location.reload();

                    // getSelectedCustomers();
                    // getSummary();
                    // saveCreditNoteAmt();
                } else {
                    alert("Error saving manual tax details. Please try again.");
                }
            },
            error: function (err) {
                alert("An error occurred while saving manual tax details.");
            }
        });
    }

</script>
