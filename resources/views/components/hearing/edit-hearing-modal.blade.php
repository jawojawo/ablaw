<div class="modal fade" id="editHearingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <span class="hearingIdHeader text-primary  px-3"></span> Hearing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="editHearingForm" action="" method="PUT">
                    @csrf
                    <input type="hidden" class="hearingIdInp" name="hearing_id">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control hearingTitle" name="title" required>

                    </div>
                    <div class="mb-3">
                        <label class="form-label d-block">Court Branch <span class="text-danger">*</span></label>
                        <input type="hidden" name="court_branch_id" class="courtBranchIdInp">
                        <div class="info courtBranchInfo">
                            <div class="id"></div>
                            <div class="name">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item cBRegion"></li>
                                        <li class="breadcrumb-item cBCity"></li>
                                        <li class="breadcrumb-item cBType"></li>
                                        <li class="breadcrumb-item cBBranch"></li>
                                    </ol>
                                </nav>
                            </div>
                            <button type="button" class="btn btn-primary infoBtn searchBtn courtBranchSearch"
                                data-id="editHearingForm"><i class="bi bi-search"></i></button>
                            <button type="button" class="btn btn-danger infoBtn clearBtn courtBranchClear"
                                data-id="editHearingForm"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hearing Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control date-time-picker hearingDate" name="hearing_date"
                            required>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            <span>
                                <input type="radio" class="btn-check hearingStatus" name="status" value="completed"
                                    id="hearingStatusCompleted" autocomplete="off">
                                <label class="btn btn-outline-secondary text-nowrap"
                                    for="hearingStatusCompleted">Completed</label>
                            </span>
                            <span>
                                <input type="radio" class="btn-check hearingStatus" name="status" value="canceled"
                                    id="hearingStatusCanceled" autocomplete="off">
                                <label class="btn btn-outline-danger text-nowrap"
                                    for="hearingStatusCanceled">Canceled</label>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="editHearingForm">Save Hearing</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let id
        let title
        let courtBranch
        let hearingDate
        let status


        $('#editHearingModal').on('show.bs.modal', function(e) {
            $(this).find('.hearingStatus').prop('checked', false)
            id = $(e.relatedTarget).data('hearing-id')
            title = $(e.relatedTarget).data('hearing-title')
            courtBranch = $(e.relatedTarget).data('hearing-court-branch')
            hearingDate = $(e.relatedTarget).data('hearing-date')
            status = $(e.relatedTarget).data('hearing-status')

            $(this).find('.hearingIdInp').val(id)
            $(this).find('.hearingIdHeader').text('#' + id)
            $(this).find('.hearingTitle').val(title)

            $(this).find('.hearingStatus[value="' + status + '"]').prop('checked', true)
            //console.log($(this).find('.hearingStatus[value="' + status + '"]').checked)
            selectCourtBranch(courtBranch, "editHearingModal")

            $(this).find('.hearingDate')[0]._flatpickr.setDate(hearingDate);


        });
        $('#editHearingForm').on('submit', function(e) {

            e.preventDefault();
            var $submitBtn = $('#editHearingModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(window.spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('case.updateHearing', '') }}/' + id,
                type: 'PUT',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        fetchAjaxTable('hearings', '#hearingsTable', 1, true)
                        $('#editHearingModal').modal('hide')
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
