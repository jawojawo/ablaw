@php
    $adminFeeCategories = App\Models\AdministrativeFee::select('type')->distinct()->limit(100)->pluck('type')->toJson();
@endphp

<div class="modal fade" id="addAdminFeeModal" tabindex="-1" aria-labelledby="addAdminFeeModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAdminFeeModalLabel">Add Deductable Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="addAdminFeeForm" action="" method="POST">
                    @csrf
                    <input type="hidden" name="law_case_id">
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <div class="custom-datalist-container">
                            <input type="text" name="type" class="form-control  custom-datalist-input "
                                data-datalist="{{ $adminFeeCategories }}" autocomplete="off">
                        </div>
                        {{-- <select class="form-select adminFeeCategorySelect" name="administrative_fee_category_id"
                            required>
                            <option disabled selected>Choose...</option>

                            @foreach ($adminFeeCategories as $adminFeeCategory)
                                <option value="{{ $adminFeeCategory }}" data-amount="{{ $adminFeeCategory }}">
                                    {{ Str::headline($adminFeeCategory) }}
                                </option>
                            @endforeach
                        </select> --}}
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
                        <label for="feeDate" class="form-label">Expense Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control date-picker" id="feeDate" name="fee_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addAdminFeeForm">Save Expense</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let lawCaseId
        $('#addAdminFeeModal').on('show.bs.modal', function(e) {
            lawCaseId = $(e.relatedTarget).data('law-case-id')
        })
        $('#addAdminFeeForm').on('submit', function(e) {
            e.preventDefault();
            var $submitBtn = $('#addAdminFeeModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(window.spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: `{{ route('case.addAdminFee', ':lawCase') }}`.replace(':lawCase',
                    lawCaseId),
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        fetchAjaxTable('admin-fees', '#adminFeesTable', 1, true)
                        $('#addAdminFeeModal').modal('hide')
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                    window.formReset("#addAdminFeeForm");
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        });
    });
</script>
