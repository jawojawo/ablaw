<div class="modal fade" id="addBillingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Billing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="addBillingForm" action="" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <div class="input-group money-group-con">
                            <span class="input-group-text">₱</span>
                            <input type="text" name="amount" class="form-control input-money amount">
                        </div>
                        {{-- <x-input-money name="amount" /> --}}
                    </div>
                    <div class="mb-3 row">
                        <div class="col-lg-6">
                            <label class="form-label">Billing Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control date-picker" name="billing_date" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control date-picker" name="due_date" required>
                        </div>

                    </div>
                    <div class="mb-3">

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addBillingForm">Save Billing</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let lawCaseId
        $('#addBillingModal').on('show.bs.modal', function(e) {
            lawCaseId = $(e.relatedTarget).data('law-case-id')
        })
        $('#addBillingForm').on('submit', function(e) {
            $('#addBillingModal .alert').text('')
            e.preventDefault();
            var $submitBtn = $('#addBillingModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(window.spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: `{{ route('case.addBilling', ':lawCase') }}`.replace(':lawCase',
                    lawCaseId),
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        fetchAjaxTable('billings', '#billingsTable', 1, true)
                        $('#addBillingModal').modal('hide')
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                    window.formReset("#addBillingForm");
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        });
    });
</script>
