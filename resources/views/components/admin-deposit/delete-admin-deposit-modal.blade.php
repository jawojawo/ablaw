<div class="modal fade" id="deleteAdminDepositModal" tabindex="-1" aria-labelledby="editDepositModalLabel"
    aria-hidden="true">
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
                <form id="deleteAdminDepositForm" action="" method="DELETE">
                    @csrf
                    <input type="hidden" class="adminDepositIdInp" name="admin_deposit_id">
                    Are you sure you want to delete <span class="adminDepositIdHeader"></span> Deposit?
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" form="deleteAdminDepositForm">Delete Deposit</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        
        $('#deleteAdminDepositModal').on('show.bs.modal', function(e) {
            var id = $(e.relatedTarget).data('admin-deposit-id')
            $(this).find('.adminDepositIdInp').val(id)
            $(this).find('.adminDepositIdHeader').text('#' + id)
            $('#deleteAdminDepositModal .alert').text('')
        });
        $('#deleteAdminDepositForm').on('submit', function(e) {
            $('#deleteAdminDepositModal .alert').text('')
            e.preventDefault();
            var $submitBtn = $('#deleteAdminDepositModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('case.deleteAdminDeposit', '') }}/' + $(this).find(
                    '.adminDepositIdInp').val(),
                type: 'DELETE',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        fetchAjaxTable('admin-deposits', '#adminDepositsTable', 1, true)
                        $('#deleteAdminDepositModal').modal('hide')
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
