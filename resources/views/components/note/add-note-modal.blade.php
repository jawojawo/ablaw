<div class="modal fade modal-lg" id="addNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " style="width:800px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Add Note</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" class="pb-5">
                <form id="addNoteModalForm">
                    @csrf

                    <div>
                        <label class="form-label">Note <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="note"></textarea>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addNoteModalForm">Save Note</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let route;
        let callback;
        $('#addNoteModal').on('show.bs.modal', function(e) {
            route = $(e.relatedTarget).data('route')
            callback = $(e.relatedTarget).data('callback')


        });
        $('#addNoteModalForm').on('submit', function(e) {
            e.preventDefault();
            var $submitBtn = $('#addNoteModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(window.spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: route,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        // addNoteCount = Number($('#case1NotesCount').text()) + 1
                        // $('#case1NotesCount').text(addNoteCount)
                        if (callback) {
                            try {

                                eval(callback);
                            } catch (error) {
                                console.error('Error executing callback:', error);
                            }
                        }
                        $('#addNoteModal').modal('hide')
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                    window.formReset("#addNoteModalForm");
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        });
    });
</script>
