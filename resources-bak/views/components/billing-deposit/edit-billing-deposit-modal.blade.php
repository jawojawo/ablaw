@props(['paymentTypes'])
<div class="modal fade" id="editBillingDepositModal" tabindex="-1" aria-labelledby="editDepositModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <span class="billingDepositModal text-primary  px-3"></span> Bill Deposit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="editBillingDepositForm" action="" method="PUT">
                    @csrf
                    <input type="hidden" class="billingDepositIdInp" name="billing_deposit_id">
                    <input type="hidden" class="billingIdInp" name="billing_id">
                    <div class="mb-3">
                        <label class="form-label">Payment Type</label>
                        <select class="form-select paymentType" name="payment_type_id" required>
                            <option selected disabled>Choose...</option>
                            @foreach ($paymentTypes as $paymentType)
                                <option value="{{ $paymentType->id }}">{{ Str::headline($paymentType->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <div class="input-group money-group-con">
                            <span class="input-group-text">₱</span>
                            <input type="text" name="amount" class="form-control input-money amount">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deposit Date</label>
                        <input type="text" class="form-control date-picker depositDate" name="deposit_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="editBillingDepositForm">Save Bill Deposit</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#editBillingDepositModal').on('show.bs.modal', function(e) {
          
            var billingId = $(this).data('billing-id')
            var id = $(this).data('billing-deposit-id')
            var paymentTypeId = $(this).data('billing-deposit-payment-type-id')

            var amount = $(this).data('billing-deposit-amount')
            var depositDate = $(this).data('billing-deposit-deposit-date')
            $(this).find('.billingIdInp').val(billingId)

            $(this).find('.billingDepositIdInp').val(id)
            $(this).find('.billingDepositModal').text('#' + id)
            $(this).find('.paymentType').val(paymentTypeId)
            $(this).find('.amount').val(amount)
            $(this).find('.depositDate')[0]._flatpickr.setDate(depositDate);
        });
        $('#editBillingDepositForm').on('submit', function(e) {
            e.preventDefault();
            var $submitBtn = $('#editBillingDepositModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(window.spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('billing.updateDeposit', '') }}/' + $(this).find(
                    '.billingDepositIdInp').val(),
                type: 'PUT',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        billingId = $('#editBillingDepositForm').find('.billingIdInp')
                            .val()
                        //  console.log(billingId)
                        //window.fetchAjaxTable('billing-deposits', '#billingDepositsTable', 1, false, id)

                        window.fetchAjaxTable('billing-deposits', '#billingDepositsTable',
                            1,
                            true, billingId)
                        window.fetchAjaxTable('billings', '#billingsTable', 1)
                        $('#editBillingDepositModal').modal('hide')

                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                    window.formReset("#addAdminDepositForm");
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        });
    });
</script>
