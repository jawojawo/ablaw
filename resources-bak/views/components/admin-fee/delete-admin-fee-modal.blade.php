<div class="modal fade" id="deleteAdminFeeModal" tabindex="-1" aria-labelledby="editFeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAdminFeeLabel"> <span
                        class="adminFeeIdHeader text-primary  px-3"></span> Admin Fee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="deleteAdminFeeForm" action="" method="DELETE">
                    @csrf
                    <input type="hidden" class="adminFeeIdInp" name="admin_fee_id">
                    Are you sure you want to delete <span class="adminFeeIdHeader"></span> Admin Fee?
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" form="deleteAdminFeeForm">Delete Admin
                    Fee</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#deleteAdminFeeModal').on('show.bs.modal', function(e) {
            var id = $(e.relatedTarget).data('admin-fee-id')
            $(this).find('.adminFeeIdInp').val(id)
            $(this).find('.adminFeeIdHeader').text('#' + id)
            $('#deleteAdminFeeModal .alert').text('')
        });
        $('#deleteAdminFeeForm').on('submit', function(e) {
            $('#deleteAdminFeeModal .alert').text('')
            e.preventDefault();
            var $submitBtn = $('#deleteAdminFeeModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('case.deleteAdminFee', '') }}/' + $(this).find(
                    '.adminFeeIdInp').val(),
                type: 'DELETE',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        fetchAjaxTable('admin-fees', '#adminFeesTable', 1, true)
                        $('#deleteAdminFeeModal').modal('hide')
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
