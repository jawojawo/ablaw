<div class="modal fade modal-lg" id="showContactsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " style="width:500px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Contact</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group contacts-con-ul"></ul>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#showContactsModal').on('show.bs.modal', function(e) {
            var contacts = $(e.relatedTarget).data('contacts')
            var ul = $("#showContactsModal").find('ul');
            console.log(contacts)
            ul.empty();
            contacts.forEach(function(contact) {
                var icon = contact.contact_type === 'phone' ? 'bi-phone' :
                    'bi-envelope';
                var listItem = `
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">
                        <i class="bi ${icon} fw-bold"></i> ${contact.contact_value}
                    </div>
                    ${contact.contact_label ? contact.contact_label : ''}
                </div>
            </li>`;
                ul.append(listItem);
            });
        });
    });
</script>
