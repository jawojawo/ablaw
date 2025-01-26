@props(['refresh' => false])
<div class="modal fade" id="deleteBillingDepositModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <span class="billingDepositModal text-primary  px-3"></span> Bill Deposit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="deleteBillingDepositForm" action="" method="DELETE">
                    @csrf
                    <input type="hidden" class="billingIdInp" name="billing_id">
                    <input type="hidden" class="billingDepositIdInp" name="billing_deposit_id">
                    Are you sure you want to delete <span class="billingDepositModal"></span> Bill Deposit?
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" form="deleteBillingDepositForm">Delete Bill
                    Deposit</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let billingId
        let id
        let refresh = {{ $refresh ? 'true' : 'false' }};
        $('#deleteBillingDepositModal').on('show.bs.modal', function(e) {

            billingId = $(this).data('billing-id')
            id = $(this).data('billing-deposit-id')

            $(this).find('.billingIdInp').val(billingId)
            $(this).find('.billingDepositIdInp').val(id)
            $(this).find('.billingDepositModal').text('#' + id)
        });
        $('#deleteBillingDepositForm').on('submit', function(e) {
            e.preventDefault();
            var $submitBtn = $('#deleteBillingDepositModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('billing.deleteDeposit', '') }}/' + id,
                type: 'DELETE',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    window.showToast('Success', response.message, 'success');
                    if (response.success) {
                        if (refresh) {
                            window.fetchAjaxTable('billings', '#billingsTable', 1, false, 0,
                                true)
                        } else {
                            window.fetchAjaxTable('billings', '#billingsTable', 1)
                        }

                        window.fetchAjaxTable('billing-deposits',
                            '#billingDepositsTable',
                            1,
                            true, billingId)

                        $('#deleteBillingDepositModal').modal('hide')
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border')
                        .remove();
                }
            });
        });
    });
</script>
