<div class="modal fade" id="changeStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable" style="max-width:600px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></span>Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="changeStatusForm" action="" method="POST" class="mb-0">
                    @csrf
                    <input type="hidden" class="caseIdInp">
                    <div class="d-flex" style="gap:10px">
                        <input type="radio" class="btn-check case-status" name="status" value="open"
                            id="openRadioButton" autocomplete="off">
                        <label class="btn btn-outline-primary text-nowrap" for="openRadioButton">Open</label>

                        <input type="radio" class="btn-check  case-status" name="status" value="in_progress"
                            id="inProgressRadioButton" autocomplete="off">
                        <label class="btn btn-outline-info  text-nowrap" for="inProgressRadioButton">In Progress</label>

                        <input type="radio" class="btn-check  case-status" name="status" value="settled"
                            id="settledRadioButton" autocomplete="off">
                        <label class="btn btn-outline-warning" for="settledRadioButton">Settled</label>

                        <input type="radio" class="btn-check  case-status" name="status" value="won"
                            id="wonRadioButton" autocomplete="off">
                        <label class="btn btn-outline-success  case-status" for="wonRadioButton">Won</label>

                        <input type="radio" class="btn-check  case-status" name="status" value="lost"
                            id="lostRadioButton" autocomplete="off">
                        <label class="btn btn-outline-danger" for="lostRadioButton">Lost</label>


                        <input type="radio" class="btn-check  case-status" name="status" value="closed"
                            id="closedRadioButton" autocomplete="off">
                        <label class="btn btn-outline-secondary  text-nowrap" for="closedRadioButton">Closed</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="changeStatusForm">Update Status</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#changeStatusModal').on('show.bs.modal', function(e) {
            var id = $(e.relatedTarget).data('case-id')
            var status = $(e.relatedTarget).data('case-status')
            $(this).find('.caseIdInp').val(id)
            $('.case-status').prop('checked', false);
            $('.case-status').filter('[value="' + status + '"]').prop('checked', true);
        })
        $('#changeStatusForm').on('submit', function(e) {
            e.preventDefault();
            var $submitBtn = $('#changeStatusModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(window.spinner);
            var formData = $(this).serialize();
            caseId = $(this).find('.caseIdInp').val()
            var newStatus = $(this).find('.case-status:checked').val()
            $.ajax({
                url: '{{ route('case.updateStatus', '') }}/' + $(this).find(
                    '.caseIdInp').val(),
                type: 'PUT',
                data: formData,
                dataType: 'json',
                success: function(response) {

                    if (response.success) {
                        $('.update-status-btn[data-case-id="' + caseId + '"]').data(
                            'case-status', newStatus);
                        $('#statusCon' + caseId).html(window.statusBadge(newStatus));
                        window.showToast('Success', response.message, 'success');
                        $('#changeStatusModal').modal('hide')
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        })

    })
</script>
