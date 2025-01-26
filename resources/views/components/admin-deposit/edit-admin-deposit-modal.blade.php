@php
    $paymentTypes = config('enums.payment_types');
@endphp
<div class="modal fade" id="editAdminDepositModal" tabindex="-1" aria-labelledby="editDepositModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAdminDepositModalLabel"> <span
                        class="adminDepositIdHeader text-primary  px-3"></span> Deposit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="editAdminDepositForm" action="" method="PUT">
                    @csrf
                    <input type="hidden" class="adminDepositIdInp" name="admin_deposit_id">
                    <div class="mb-3">
                        <label class="form-label">Payment Type <span class="text-danger">*</span></label>
                        <select class="form-select paymentType" name="payment_type" required>
                            <option selected disabled>Choose...</option>
                            @foreach ($paymentTypes as $paymentType)
                                <option value="{{ $paymentType }}">{{ Str::headline($paymentType) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <div class="input-group money-group-con">
                            <span class="input-group-text">₱</span>
                            <input type="text" name="amount" class="form-control input-money amount">
                        </div>
                        {{-- <x-input-money name="amount" /> --}}
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deposit Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control date-picker depositDate" name="deposit_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="editAdminDepositForm">Save Deposit</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let id
        let paymentType
        let amount
        let depositDate
        $('#editAdminDepositModal').on('show.bs.modal', function(e) {
            id = $(e.relatedTarget).data('admin-deposit-id')
            paymentType = $(e.relatedTarget).data('admin-deposit-payment-type')
            amount = $(e.relatedTarget).data('admin-deposit-amount')
            depositDate = $(e.relatedTarget).data('admin-deposit-deposit-date')
            $(this).find('.adminDepositIdInp').val(id)
            $(this).find('.adminDepositIdHeader').text('#' + id)
            $(this).find('.paymentType').val(paymentType)
            $(this).find('.amount').val(amount)
            $(this).find('.depositDate')[0]._flatpickr.setDate(depositDate);
        });
        $('#editAdminDepositForm').on('submit', function(e) {
            e.preventDefault();
            var $submitBtn = $('#editAdminDepositModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(window.spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('case.updateAdminDeposit', '') }}/' + id,
                type: 'PUT',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        fetchAjaxTable('admin-deposits', '#adminDepositsTable', 1, true)
                        $('#editAdminDepositModal').modal('hide')
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                    window.formReset("#editAdminDepositForm");
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        });
    });
</script>
