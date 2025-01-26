<div class="modal fade modal-lg" id="deleteNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " style="width:500px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"><span class="noteIdHeader text-primary  px-3"></span> Note</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" class="pb-5">
                <form id="deleteNoteModalForm">
                    @csrf
                    Are you sure you want to delete <span class="noteIdHeader"></span> Note?
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" form="deleteNoteModalForm">Delete Note</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let id;
        let callback;
        $('#deleteNoteModal').on('show.bs.modal', function(e) {
            id = $(e.relatedTarget).data('note-id')
            callback = $(e.relatedTarget).data('callback')
            $(this).find('.noteIdHeader').text('#' + id)
        });
        $('#deleteNoteModalForm').on('submit', function(e) {
            e.preventDefault();
            var $submitBtn = $('#deleteNoteModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('note.delete', '') }}/' + id,
                type: 'DELETE',
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
                        $('#deleteNoteModal').modal('hide')
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
