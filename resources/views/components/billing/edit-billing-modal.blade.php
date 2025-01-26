@props(['refresh' => false])
<div class="modal fade" id="editBillingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="billingIdHeader text-primary  px-3"></span> Edit Billing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="editBillingForm" action="" method="POST">
                    @csrf
                    <input type="hidden" class="billingIdInp" name="billing_id" value="">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control billingTitle" name="title" required>
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
                            <input type="text" class="form-control date-picker billingDate" name="billing_date"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control date-picker dueDate" name="due_date" required>
                        </div>

                    </div>
                    <div class="mb-3">

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="editBillingForm">Save Billing</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let id
        let title
        let amount
        let billingDate
        let dueDate
        let refresh = {{ $refresh ? 'true' : 'false' }};
        $('#editBillingModal').on('show.bs.modal', function(e) {
            id = $(e.relatedTarget).data('billing-id')
            title = $(e.relatedTarget).data('billing-title')
            amount = $(e.relatedTarget).data('billing-amount')
            billingDate = $(e.relatedTarget).data('billing-billing-date')
            dueDate = $(e.relatedTarget).data('billing-due-date')
            $(this).find('.billingIdInp').val(id)
            $(this).find('.billingIdHeader').text('#' + id)
            $(this).find('.billingTitle').val(title)
            $(this).find('.amount').val(amount)
            $(this).find('.billingDate')[0]._flatpickr.setDate(billingDate);
            $(this).find('.dueDate')[0]._flatpickr.setDate(dueDate);
            $('#editBillingModal .alert').text('')
        });
        $('#editBillingForm').on('submit', function(e) {
            $('#editBillingModal .alert').text('')
            e.preventDefault();
            var $submitBtn = $('#editBillingModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(window.spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('case.updateBilling', '') }}/' + id,
                type: 'PUT',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        if (refresh)
                            fetchAjaxTable('billings', '#billingsTable', 1, false, 0, true)
                        else {
                            fetchAjaxTable('billings', '#billingsTable', 1, true)
                        }
                        $('#editBillingModal').modal('hide')
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                    window.formReset("#editBillingForm");
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        });
    });
</script>
