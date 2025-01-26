@php
    $adminFeeCategories = App\Models\AdministrativeFee::select('type')->distinct()->limit(100)->pluck('type')->toJson();
@endphp

<div class="modal fade" id="editAdminFeeModal" tabindex="-1" aria-labelledby="editDepositModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <span class="adminFeeIdHeader text-primary  px-3"></span> Deductable Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="editAdminFeeForm" action="" method="PUT">
                    @csrf
                    <input type="hidden" class="adminFeeIdInp" name="admin_fee_id">
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <div class="custom-datalist-container">
                            <input type="text" name="type" class="form-control  custom-datalist-input type"
                                data-datalist="{{ $adminFeeCategories }}" autocomplete="off">
                        </div>
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
                        <label class="form-label">Expense Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control date-picker adminFeeDate" name="fee_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="editAdminFeeForm">Save Expense</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let id
        let type
        let amount
        let date
        $('#editAdminFeeModal').on('show.bs.modal', function(e) {
            id = $(e.relatedTarget).data('admin-fee-id')
            type = $(e.relatedTarget).data('admin-fee-type')
            amount = $(e.relatedTarget).data('admin-fee-amount')
            date = $(e.relatedTarget).data('admin-fee-date')

            $(this).find('.adminFeeIdInp').val(id)
            $(this).find('.adminFeeIdHeader').text('#' + id)

            $(this).find('.type').val(type)
            $(this).find('.amount').val(amount)
            $(this).find('.adminFeeDate')[0]._flatpickr.setDate(date);

        });
        $('#editAdminFeeForm').on('submit', function(e) {
            e.preventDefault();
            var $submitBtn = $('#editAdminFeeModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(window.spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('case.updateAdminFee', '') }}/' + id,
                type: 'PUT',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        fetchAjaxTable('admin-fees', '#adminFeesTable', 1, true)
                        $('#editAdminFeeModal').modal('hide')
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                    window.formReset("#editAdminFeeForm");
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        });
        // $(document).on('change', '#editAdminFeeModal .adminFeeCategorySelect', function(e) {
        //     var amount = e.target.options[e.target.selectedIndex].dataset.amount;
        //     $(this).closest('#editAdminFeeModal').find('.amount').val(amount);
        // });
    });
</script>
