@props(['route'])
<div class="modal fade modal-lg" id="editContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " style="width:500px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"><span class="contactIdHeader text-primary  px-3"></span> Edit Contact</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" class="pb-5">
                <form id="editContactModalForm">
                    @csrf
                    <input type="hidden" class="contactId" name="id">
                    <div class="input-group mb-2">
                        <span class="input-group-text p-0">
                            <div class="btn-group">
                                <button type="button" class="btn dropdown-toggle phone-email-select"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-phone"></i>
                                </button>
                                <ul class="dropdown-menu text-center" style="min-width:60px !important">
                                    <li><a class="dropdown-item contact-type-select phone d-flex justify-content-between align-items-center"
                                            href="#" data-value="phone"><i class="bi bi-phone pe-2"></i>
                                            <span>Phone</span></a></li>
                                    <li><a class="dropdown-item contact-type-select email d-flex justify-content-between align-items-center"
                                            href="#" data-value="email"><i
                                                class="bi bi-envelope pe-2"></i><span>Email</span></a></li>
                                    <input type="hidden" name="contact_type" value="phone">
                                </ul>
                            </div>
                        </span>
                        <input type="text" class="form-control" placeholder="Value" name="contact_value" required>

                    </div>
                    <div>
                        <input type="text" class="form-control" name="contact_label" placeholder="Label" />
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="editContactModalForm">Save Contact</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#editContactModal').on('show.bs.modal', function(e) {
            var id = $(e.relatedTarget).data('contact-id')
            var type = $(e.relatedTarget).data('contact-type')
            var value = $(e.relatedTarget).data('contact-value')
            var label = $(e.relatedTarget).data('contact-label')

            $(this).find('.contactId').val(id)
            $(this).find('input[name="contact_type"]').val(type)
            $(this).find('input[name="contact_value"]').val(value)
            $(this).find('input[name="contact_label"]').val(label)
            $(this).find('.contactIdHeader').text('#' + id)
        });
        $('#editContactModalForm').on('submit', function(e) {
            e.preventDefault();
            var $submitBtn = $('#editContactModal').find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(window.spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('contact.update', '') }}/' + $(this).find(
                    '.contactId').val(),
                type: 'PUT',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.showToast('Success', response.message, 'success');
                        window.loadContacts('{{ $route }}', 'ul.contacts-con-ul');
                        $('#editContactModal').modal('hide')
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                    window.formReset("#editContactModalForm");
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        });
    });
</script>
