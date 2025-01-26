@props(['route'])
<div>
    <h6 class="text-primary fw-semibold d-flex justify-content-between">
        <span>Contact Info</span>
        <div class="add-contact-modal-btn btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContactModal">
            <i class="bi bi-plus-lg"></i>
        </div>
    </h6>
    <ul class="list-group contacts-con-ul"></ul>
</div>
<script>
    $(document).ready(function() {
        window.loadContacts('{{ $route }}', 'ul.contacts-con-ul');
    })
</script>
