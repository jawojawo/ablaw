<div class="modal fade" id="deleteCustomEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success"></div>
                <div class="alert alert-danger"></div>
                <form id="deleteCustomEventForm" action="" method="DELETE">
                    @csrf
                    <div class="mb-4">
                        Are you sure you want to delete <span class="modal-title fw-bold"></span>?
                    </div>
                    <div>
                        <label class="form-label">Enter Password to confirm</label>
                        <input type="password" class="form-control" name="password" placeholder="********">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" form="deleteCustomEventForm">Delete Custom Event</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let id;
        let modalTitle;
        $('#deleteCustomEventModal').on('show.bs.modal', function(e) {
            id = $(e.relatedTarget).data('custom-event-id')
            modalTitle = $(e.relatedTarget).data('modal-title')
            $('.modal-title').text(modalTitle)
        });
        $('#deleteCustomEventForm').on('submit', function(e) {

            e.preventDefault();
            var $submitBtn = $('#deleteCustomEventModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('customEvent.delete', '') }}/' + id,
                type: 'DELETE',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        setTimeout(function() {
                            window.location.replace("{{ route('customEvent') }}");
                        }, 5000);

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
