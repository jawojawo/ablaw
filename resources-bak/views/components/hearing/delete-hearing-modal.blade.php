<div class="modal fade" id="deleteHearingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <span class="hearingIdHeader text-primary  px-3"></span> Hearing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="deleteHearingForm" action="" method="DELETE">
                    @csrf
                    <input type="hidden" class="hearingIdInp" name="admin_fee_id">
                    Are you sure you want to delete <span class="hearingIdHeader"></span> Hearing?
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" form="deleteHearingForm">Delete Hearing</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#deleteHearingModal').on('show.bs.modal', function(e) {


            var id = $(e.relatedTarget).data('hearing-id')
            $(this).find('.hearingIdInp').val(id)
            $(this).find('.hearingIdHeader').text('#' + id)
            $('#deleteHearingModal .alert').text('')
        });
        $('#deleteHearingForm').on('submit', function(e) {
            e.preventDefault();
            $('#deleteHearingModal .alert').text('')

            var $submitBtn = $('#deleteHearingModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('case.deleteHearing', '') }}/' + $(this).find(
                    '.hearingIdInp').val(),
                type: 'DELETE',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        fetchAjaxTable('hearings', '#hearingsTable', 1, true)
                        $('#deleteHearingModal').modal('hide')
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
