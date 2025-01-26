@props(['paymentTypes'])
<div class="modal fade" id="addBillingDepositModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="billingIdHeader  text-primary  px-3"></span> Bill
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <table class="table table-bordered title">
                    <tr>
                        <th colspan="2" class="table-primary text-center">
                            <span class="title"></span>
                        </th>
                    </tr>
                    <tr>
                        <td>Bill Amount:</td>
                        <td>₱<span class="amount"></span></td>
                    </tr>
                    <tr>
                        <td>Paid:</td>
                        <td>₱<span class="paid"></span></td>
                    </tr>
                    <tr>
                        <td>Defecit:</td>
                        <td>₱<span class="defecit"></span></td>
                    </tr>
                    <tr>
                        <td>Due Date:</td>
                        <td><span class="dueDate"></span></td>
                    </tr>
                </table>
                <form id="addBillingDepositForm" action="" method="POST">
                    @csrf
                    <input type="hidden" name="billing_id" class="billingIdInp" value="">
                    <div class="mb-3">
                        <label for="paymentType" class="form-label">Payment Type</label>
                        <select class="form-select" id="paymentType" name="payment_type_id" required>
                            <option selected disabled>Choose payment
                                type...</option>
                            @foreach ($paymentTypes as $paymentType)
                                <option value="{{ $paymentType->id }}">
                                    {{ Str::headline($paymentType->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <div class="input-group money-group-con">
                            <span class="input-group-text">₱</span>
                            <input type="text" name="amount" class="form-control input-money amount">
                        </div>
                        {{-- <x-input-money name="amount" /> --}}

                    </div>

                    <!-- Deposit Date -->
                    <div class="mb-3">
                        <label for="depositDate" class="form-label">Deposit Date</label>
                        <input class="date-picker" type="date" class="form-control" id="depositDate"
                            name="deposit_date" required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addBillingDepositForm">Save Deposit</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#addBillingDepositModal').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).data('billing-id')
        var title = $(e.relatedTarget).data('billing-title')
        var amount = $(e.relatedTarget).data('billing-amount')
        var paid = $(e.relatedTarget).data('billing-paid')
        var defecit = $(e.relatedTarget).data('billing-defecit')
        var dueDate = $(e.relatedTarget).data('billing-due-date')
        $(this).find('.billingIdInp').val(id)
        $(this).find('.billingIdHeader').text('#' + id)
        $(this).find('.title .title').text(title)
        $(this).find('.title .amount').text(amount)
        $(this).find('.title .paid').text(paid)
        var defecitEl = $(this).find('.title .defecit').text(defecit)
        if (parseFloat(defecit) > 0) {
            defecitEl.closest('tr').removeClass('table-success')
            defecitEl.closest('tr').addClass('table-danger')
        } else {

            defecitEl.closest('tr').removeClass('table-danger')
            defecitEl.closest('tr').addClass('table-success')
        }

        $(this).find('.title .dueDate').text(dueDate)
    });
    $(document).ready(function() {
        $('#addBillingDepositForm').on('submit', function(e) {
            e.preventDefault();
            var $submitBtn = $('#addBillingDepositModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(window.spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('billing.addDeposit', '') }}/' + $(this).find(
                        '.billingIdInp')
                    .val(),
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        fetchAjaxTable('billings', '#billingsTable', 1, true)
                        $('#addBillingDepositModal').modal('hide')
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                    window.formReset("#addBillingDepositForm");
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        });
    });
</script>
