@props(['notes'])
@php
    $paginatedNotes = $notes
        ->with(['user'])
        ->orderBy('updated_at', 'desc')
        ->paginate(10, ['*'], 'notes-page');
@endphp
@if ($paginatedNotes->isEmpty())
    <p class="text-center text-muted">No Notes found.</p>
@else
    @foreach ($paginatedNotes as $note)
        <div class="alert alert-light border-secondary" role="alert">
            {{-- <div class="position-absolute top-0 start-0 px-3">#{{ $note->id }}</div> --}}
            <div class="position-absolute top-0 end-0">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                        data-bs-display="dynamic" aria-expanded="false"></button>
                    <ul class="dropdown-menu shadow">
                        <li>
                            <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                <span class="text-muted">#{{ $note->id }}</span>
                            </h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item " href="#" data-bs-toggle="modal"
                                data-bs-target="#editNoteModal" data-note-id="{{ $note->id }}"
                                data-note-note = "{{ $note->note }}"
                                data-callback="window.fetchAjaxTable('notes', '#notesTable', 1, false)">
                                <i class="bi bi-pencil me-2"></i>Edit
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                data-bs-target="#deleteNoteModal" data-note-id="{{ $note->id }}"
                                data-callback="window.fetchAjaxTable('notes', '#notesTable', 1, false)
                                 const container =$('#note{{ $note->notable_id }}NotesCount');
                                const notesCount = Number(container.text() - 1);
                                container.text(notesCount);
                                ">
                                <i class="bi bi-trash me-2"></i>Delete
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <p class="fst-italic pt-2 m-0">{{ $note->note }}</p>
            <div class="d-flex justify-content-between">
                <div>
                    by <span class="text-muted fw-bold">{{ $note->user->name }}</span>
                </div>
                <span>{{ formattedDateTime($note->updated_at) }}</span>
            </div>
        </div>
    @endforeach
    {{ $paginatedNotes->links() }}
@endif
