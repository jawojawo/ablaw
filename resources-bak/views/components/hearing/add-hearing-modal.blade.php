@props(['lawCase'])
<div class="modal fade" id="addHearingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Hearing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="addHearingForm" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-block">Court Branch</label>
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
                                data-id="addHearingForm"><i class="bi bi-search"></i></button>
                            <button type="button" class="btn btn-danger infoBtn clearBtn courtBranchClear"
                                data-id="addHearingForm"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>
                    <div class="mb-3 ">
                        <label class="form-label">Hearing Date</label>
                        <input type="text" class="form-control date-time-picker" name="hearing_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addHearingForm">Save Hearing</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.courtBranchClear', function(e) {
        let hearingCardId = $(this).data('id')
        $(`#${hearingCardId} .courtBranchIdInp`).val('')
        $(`#${hearingCardId} .id`).text('')
        $(`#${hearingCardId} span`).text('')
    });
    $('#addHearingForm').on('submit', function(e) {
        $('#addHearingModal .alert').text('')
        e.preventDefault();
        var $submitBtn = $('#addHearingModal').find('button[type="submit"]');
        $submitBtn.prop('disabled', true).prepend(window.spinner);
        var formData = $(this).serialize();
        $.ajax({
            url: '{{ route('case.addHearing', ['lawCase' => $lawCase]) }}',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    window.showToast('Success', response.message, 'success');
                    fetchAjaxTable('hearings', '#hearingsTable', 1, true)
                    $('#addHearingModal').modal('hide')
                }
                $submitBtn.prop('disabled', false).find('.spinner-border').remove();
            },
            error: function(xhr) {
                window.toastError(xhr);
                $submitBtn.prop('disabled', false).find('.spinner-border').remove();
            }
        });
    });
</script>
