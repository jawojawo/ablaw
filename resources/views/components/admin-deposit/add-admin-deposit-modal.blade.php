@php
    $paymentTypes = config('enums.payment_types');
@endphp

<div class="modal fade" id="addAdminDepositModal" tabindex="-1" aria-labelledby="addDepositModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAdminDepositModalLabel">Add Deposit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="addAdminDepositForm" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="paymentType" class="form-label">Payment Type <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="paymentType" name="payment_type" required>
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
                            <input type="text" name="amount" class="form-control input-money">
                        </div>
                        {{-- <x-input-money name="amount" /> --}}
                    </div>
                    <div class="mb-3">
                        <label for="depositDate" class="form-label">Deposit Date <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control date-picker" id="depositDate" name="deposit_date"
                            required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addAdminDepositForm">Save Deposit</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let lawCaseId
        $('#addAdminDepositModal').on('show.bs.modal', function(e) {
            lawCaseId = $(e.relatedTarget).data('law-case-id')

        })
        $('#addAdminDepositForm').on('submit', function(e) {
            e.preventDefault();
            var $submitBtn = $('#addAdminDepositModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(window.spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: `{{ route('case.addAdminDeposit', ':lawCase') }}`.replace(':lawCase',
                    lawCaseId),
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        fetchAjaxTable('admin-deposits', '#adminDepositsTable', 1, true)
                        $('#addAdminDepositModal').modal('hide')
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
