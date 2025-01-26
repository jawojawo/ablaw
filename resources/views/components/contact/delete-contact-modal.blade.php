@props(['route'])
<div class="modal fade modal-lg" id="deleteContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " style="width:500px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"><span class="contactIdHeader text-primary  px-3"></span> Contact</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" class="pb-5">
                <form id="deleteContactModalForm">
                    @csrf
                    <input type="hidden" name='id' class="contactId">
                    Are you sure you want to delete <span class="contactIdHeader"></span> Contact?
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" form="deleteContactModalForm">Delete Contact</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#deleteContactModal').on('show.bs.modal', function(e) {
            var id = $(e.relatedTarget).data('contact-id')
            $(this).find('.contactId').val(id)
            $(this).find('.contactIdHeader').text('#' + id)
        });
        $('#deleteContactModalForm').on('submit', function(e) {
            e.preventDefault();
            var $submitBtn = $('#deleteContactModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('contact.delete', '') }}/' + $(this).find(
                    '.contactId').val(),
                type: 'DELETE',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        window.loadContacts('{{ $route }}', 'ul.contacts-con-ul');
                        $('#deleteContactModal').modal('hide')
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
