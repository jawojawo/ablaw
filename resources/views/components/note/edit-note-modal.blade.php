<div class="modal fade modal-lg" id="editNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " style="width:500px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"><span class="noteIdHeader text-primary  px-3"></span> Edit Note</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" class="pb-5">
                <form id="editNoteModalForm">
                    @csrf
                    <div>
                        <label class="form-label">Note <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="note"></textarea>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="editNoteModalForm">Save Note</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let id;
        let note;
        let callback;
        $('#editNoteModal').on('show.bs.modal', function(e) {
            id = $(e.relatedTarget).data('note-id')
            callback = $(e.relatedTarget).data('callback')
            note = $(e.relatedTarget).data('note-note')
            $(this).find('textarea[name="note"]').val(note)
            $(this).find('.noteIdHeader').text('#' + id)
        });
        $('#editNoteModalForm').on('submit', function(e) {
            e.preventDefault();
            var $submitBtn = $('#editNoteModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(window.spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('note.update', '') }}/' + id,
                type: 'PUT',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');

                        if (callback) {
                            try {
                                eval(callback);
                            } catch (error) {
                                console.error('Error executing callback:', error);
                            }
                        }
                        $('#editNoteModal').modal('hide')
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                    window.formReset("#editNoteModalForm");
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        });
    });
</script>
