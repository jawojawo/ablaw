@props(['pdf' => false])
<div class="card-header bg-light  text-white d-flex justify-content-between align-items-center">
    <div class='d-flex align-items-center'>
        <h5 class="mb-0 text-bg-light d-inline me-2">
            Case:
            <span class="editable-input">
                <span class="value" data-name="case_number" data-value="{{ $lawCase->case_number }}" data-class="w-init">
                    {{ $lawCase->case_number }}
                </span>
            </span>
        </h5>
        @can('update', $lawCase)
            <a href="#" class="update-status-btn" data-bs-toggle="modal" data-bs-target="#changeStatusModal"
                data-case-id="{{ $lawCase->id }}" data-case-status="{{ $lawCase->status }}">
                <span id='statusCon{{ $lawCase->id }}'>
                    {!! $lawCase->status_badge !!}
                </span>
            </a>
        @else
            <span id='statusCon{{ $lawCase->id }}'>
                {!! $lawCase->status_badge !!}
            </span>
        @endcan
    </div>
    @if (!$pdf)
        <div>
            @canany(['update', 'delete'], $lawCase)
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                        aria-expanded="false"></button>
                    <ul class="dropdown-menu shadow">
                        <li>
                            <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                <span class="text-muted me-2">#{{ $lawCase->id }}</span>
                                <span>{{ $lawCase->case_number }}</span>
                            </h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" data-header="Case {{ $lawCase->case_number }}"
                                data-route="{{ request()->fullUrlWithQuery(['pdf' => 'view']) }}" data-bs-toggle="modal"
                                data-file-name="case-{{ $lawCase->case_number }}.pdf" data-bs-target="#pdfViewModal">
                                <i class="bi bi-file-earmark-pdf me-2"></i>Export Pdf
                            </a>

                        </li>
                        @can('update', $lawCase)
                            <li>
                                <a class="dropdown-item update-status-btn" data-bs-toggle="modal"
                                    data-bs-target="#changeStatusModal" data-case-id="{{ $lawCase->id }}"
                                    data-case-status="{{ $lawCase->status }}">
                                    <i class="bi bi-arrow-repeat me-2"></i>Update Status
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" id="editCaseBtn">
                                    <i class="bi bi-pencil me-2"></i>Edit
                                </a>
                            </li>
                        @endcan
                        @can('delete', $lawCase)
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                    data-bs-target="#deleteCaseModal" data-case-id="{{ $lawCase->id }}"
                                    data-modal-title="{{ $lawCase->case_number }}">
                                    <i class="bi bi-trash me-2"></i>Delete
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            @endcan
        </div>
    @endif
</div>
<div class="card-body">
    <div class="row mb-4">
        <div class="col-8">
            <h6 class="text-primary fw-semibold">Title</h6>
            <p class="mb-0 fst-italic fs-5 text-dark">
                <span class="editable-text-area">
                    <span class="value" data-name="case_title"
                        data-value="{{ $lawCase->case_title }}">{{ $lawCase->case_title }}</span>
                </span>
            </p>
        </div>
        <div class="col-4 mb-3">
            <h6 class="text-primary fw-semibold">Case Type</h6>
            <p class="mb-0 fs-6 text-dark">
                <span class="editable-select">
                    <span class="value" data-name="case_type" data-selected-value="{{ $lawCase->case_type }}"
                        data-values="{{ json_encode(config('enums.case_types')) }}">
                        {{ Str::headline($lawCase->case_type) }}
                    </span>
                </span>
            </p>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-4">
            <h6 class="text-primary fw-semibold">Client</h6>
            <p class="mb-0 fs-6 text-dark">
                <span class="editable-client">
                    <span class="value" data-name="client_id" data-client-id="{{ $lawCase->client_id }}"
                        data-client-name="{{ $lawCase->client->name }}">{{ $lawCase->client->name }}</span>
                </span>
            </p>
        </div>
        <div class="col-4">
            <h6 class="text-primary fw-semibold">Role</h6>
            <p class="mb-0 fs-6 text-dark">
                <span class="editable-select">
                    <span class="value" data-name="party_role" data-selected-value="{{ $lawCase->party_role }}"
                        data-values="{{ json_encode(config('enums.party_roles')) }}">
                        {{ Str::headline($lawCase->party_role) }}
                    </span>
                </span>

            </p>
        </div>
        {{-- <div class="col-lg-4">
            <h6 class="text-primary fw-semibold">Opposing Party</h6>
            <p class="mb-0 fs-6 text-dark">
                <span class="editable-input">
                    <span class="value" data-name="opposing_party"
                        data-value="{{ $lawCase->opposing_party }}">{{ $lawCase->opposing_party }}</span>
                </span>
            </p>
        </div> --}}
        <div class="col-4 mb-3">
            <h6 class="text-primary fw-semibold">Associate</h6>
            <p class="mb-0 fs-6 text-dark">
                <span class="editable-associate">
                    <span class="value" data-name="associate_id" data-associate-id="{{ $lawCase->associate_id }}"
                        data-associate-name="{{ $lawCase->associate->name }}">{{ $lawCase->associate->name }}</span>
                </span>
            </p>
        </div>
    </div>
    <div class="row">
        {{-- <div class="col-lg-4 mb-3">
            <h6 class="text-primary fw-semibold">Case Type</h6>
            <p class="mb-0 fs-6 text-dark">
                <span class="editable-select">
                    <span class="value" data-name="case_type"
                        data-selected-value="{{ $lawCase->case_type }}"
                        data-values="{{ json_encode(config('enums.case_types')) }}">
                        {{ Str::headline($lawCase->case_type) }}
                    </span>
                </span>
            </p>
        </div> --}}
        <div class="col-4">
            @if (!$pdf)
                <x-contact.show :route="route('contact', ['case', $lawCase->id])" />
            @else
                <div>
                    <h6 class="text-primary fw-semibold d-flex justify-content-between">
                        <span>Contact Info</span>
                    </h6>
                    <ul class="list-group contacts-con-ul">
                        @foreach ($lawCase->contacts as $contact)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">
                                        @if ($contact->contact_type == 'phone')
                                            <i class="bi bi-phone fw-bold"></i>
                                        @else
                                            <i class="bi bi-envelope fw-bold"></i>
                                        @endif

                                        {{ $contact->contact_value }}
                                    </div>
                                    {{ $contact->contact_label }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="col-8">
            <h6 class="text-primary fw-semibold">Opposing Party</h6>
            <p class="mb-0 fs-6 text-dark">
                <span class="editable-input">
                    <span class="value" data-name="opposing_party"
                        data-value="{{ $lawCase->opposing_party }}">{{ $lawCase->opposing_party }}</span>
                </span>
            </p>
        </div>

    </div>
    <div class="d-flex justify-content-end m-4 visually-hidden" id="submitBtnCon">
        <div>
            <button type="button" class="btn btn-secondary" id="cancelEditBtn">Cancel</button>
            <button type="submit" class="btn btn-primary" form="editCaseForm">Update Case</button>
        </div>
    </div>
</div>
