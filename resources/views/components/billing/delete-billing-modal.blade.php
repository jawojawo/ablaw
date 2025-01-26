@props(['refresh' => false])
<div class="modal fade" id="deleteBillingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <span class="BillingIdHeader text-primary  px-3"></span> Billing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="deleteBillingForm" action="" method="DELETE">
                    @csrf
                    <input type="hidden" class="BillingIdInp" name="billing_id">
                    Are you sure you want to delete <span class="BillingIdHeader"></span> Billing?
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" form="deleteBillingForm">Delete Billing</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let refresh = {{ $refresh ? 'true' : 'false' }};
        let id
        $('#deleteBillingModal').on('show.bs.modal', function(e) {
            id = $(e.relatedTarget).data('billing-id')
            $(this).find('.BillingIdInp').val(id)
            $(this).find('.BillingIdHeader').text('#' + id)
            $('#deleteBillingModal .alert').text('')
        });
        $('#deleteBillingForm').on('submit', function(e) {
            e.preventDefault();
            $('#deleteBillingModal .alert').text('')
            var $submitBtn = $('#deleteBillingModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('case.deleteBilling', '') }}/' + id,
                type: 'DELETE',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        if (refresh) {
                            fetchAjaxTable('billings', '#billingsTable', 1, false, 0, true)
                        } else {
                            fetchAjaxTable('billings', '#billingsTable', 1, true)
                        }
                        $('#deleteBillingModal').modal('hide')
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        });
    });
</script>
