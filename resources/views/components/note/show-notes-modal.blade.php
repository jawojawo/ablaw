<div class="modal fade modal-lg" id="showNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable" style="width:800px">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Notes</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding-bottom:70px">
                <div class="d-flex justify-content-end align-items-start mb-3">
                    {{-- <button class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addNoteModal"
                        data-callback="window.fetchAjaxTable('notes', '#notesTable' , 1, false);
                        const container =$('#client{{ $client->id }}NotesCount');
                        const notesCount = Nunmber(container.text() + 1);
                        container.text(notesCount);
                        "
                        data-route="{{ route('note', ['client', $client->id]) }}">
                        Add Note
                    </button> --}}
                    <div class="d-flex justify-content-end align-items-start mb-3">
                        <a class="btn btn-primary" id="addNoteBtn">
                            Add Note
                        </a>
                    </div>
                </div>
                <div class="notes-con"></div>
            </div>
            <div class="modal-footer">
                <ul class="pagination"></ul>
            </div>
        </div>
    </div>
</div>

<script>
    //$(document).ready(function() {
    let route;
    let countCon;

    $('#showNotesModal').on('show.bs.modal', function(e) {
        route = $(e.relatedTarget).data('route');
        countCon = $(e.relatedTarget).attr('id');
        fetchNotes(route);
    });

    function fetchNotes(url) {
        const container = $('#showNotesModal').find('.notes-con');
        const paginationContainer = $('#showNotesModal').find('.modal-footer .pagination');



        //container.empty();

        paginationContainer.empty();
        container.append(window.spinnerOverlay);
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    renderNotes(response);
                    renderPagination(response.data.links);
                }
            },
            error: function(xhr) {
                console.error('Error loading notes:', xhr);
                container.html(
                    '<p class="text-danger text-center">Failed to load notes. Please try again.</p>'
                );

            },
            complete: function() {
                container.find(".spinner-overlay").remove();
            }
        });
    }

    function renderNotes(response) {
        const container = $('#showNotesModal').find('.notes-con');
        container.empty();


        response.data.data.forEach(note => {
            const noteHtml = `
                    <div class="alert alert-light border-secondary position-relative" role="alert" id="note${note.id}">
                        
                        <div class="position-absolute top-0 end-0">
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown" data-bs-display="dynamic" aria-expanded="false"></button>
                                <ul class="dropdown-menu shadow">
                                    <li>
                                        <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                            <span class="text-muted">#${note.id}</span>
                                        </h6>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item editNoteBtn"  data-note-id='${note.id}' data-note-note='${note.note.replace(/'/g, '&#39;').replace(/"/g, '&quot;')}'>
                                            <i class="bi bi-pencil me-2"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger deleteNoteBtn"    data-note-id='${note.id}'>
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <p class="fst-italic pt-2 m-0">${note.note}</p>
                        <div class="d-flex justify-content-between">
                            <div>
                            by <span class="text-muted fw-bold">${note.user.name}</span>
                            </div>
                            <span>${window.formatDateTime(note.updated_at)}</span>
                        </div>
                    </div>`;
            container.append(noteHtml);
        });


        if (response.data.data.length === 0) {
            container.html('<p class="text-center text-muted">No notes available.</p>');
        }
    }

    function renderPagination(links) {
        const paginationContainer = $('#showNotesModal').find('.modal-footer .pagination');
        paginationContainer.empty();


        links.forEach(link => {
            link.label = link.label.replace('&laquo; Previous', '‹');
            link.label = link.label.replace('Next &raquo;', '›');
            const pageLink = `
                    <li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link ${link.url ?'': 'disabled'}" href="${link.url}" ${link.url ? '' : 'tabindex="-1"'}>${link.label}</a>
                    </li>`;
            paginationContainer.append(pageLink);
        });


        paginationContainer.find('.page-link').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (url) {
                fetchNotes(url);
            }
        });
    }

    function removeNote(id) {
        $("#note" + id).remove();
    }
    $(document).on("click", ".deleteNoteBtn", function() {
        const currentCount = Number($('#' + countCon).text());
        notesCount = currentCount - 1;
        $(this).attr('data-callback', `fetchNotes('${route}');$('#${countCon}').text('${notesCount}')`)
        $("#deleteNoteModal").modal('show', $(this));
    });
    $(document).on("click", ".editNoteBtn", function() {
        $(this).attr('data-callback', `fetchNotes('${route}')`)
        $("#editNoteModal").modal('show', $(this));
    });
    $(document).on("click", "#addNoteBtn", function() {

        const currentCount = Number($('#' + countCon).text());
        const notesCount = currentCount + 1;
        const callback = `
        fetchNotes('${route}');
        $('#${countCon}').text(${notesCount});
    `;
        // $(this).attr('data-route',route).attr('data-callback',callback)
        const el = $(`<button data-route="${route}" data-callback="${callback}"></button>`)
        $("#addNoteModal").modal('show', el);
    });
</script>
