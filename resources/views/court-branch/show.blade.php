@extends('layout.app')
@section('title')
    {{ $courtBranch->region }} {{ $courtBranch->city }} {{ $courtBranch->branch }}
@endsection
@section('main')
    <div class="container my-5">

        <div class="card mb-4">
            <form id="editCourtBranchForm" class="m-0">
                @csrf
                <div class="card-header bg-light  text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-bg-light d-inline me-2">
                            Court branch
                        </h5>
                    </div>
                    <div>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                aria-expanded="false"></button>
                            <ul class="dropdown-menu shadow">
                                <li>
                                    <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                        <span class="text-muted me-2">#{{ $courtBranch->id }}</span>
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <button class="dropdown-item" id="editCourtBranchBtn">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </button>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                        data-bs-target="#deleteCourtBranchModal"
                                        data-court-branch-id="{{ $courtBranch->id }}"
                                        data-modal-title="{{ $courtBranch->region }} - {{ $courtBranch->city }} - {{ $courtBranch->branch }}">
                                        <i class="bi bi-trash me-2"></i>Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-4">
                            <h6 class="text-primary fw-semibold">Region/Province</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-input position-relative pb-2">
                                    <span class="value" data-class="custom-datalist-input"
                                        data-datalist="{{ $regions->toJson() }}" data-name="region"
                                        data-value="{{ $courtBranch->region }}">
                                        {{ $courtBranch->region }}
                                    </span>
                                </span>
                            </p>
                        </div>
                        <div class="col-4">
                            <h6 class="text-primary fw-semibold">City/Municipality</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-input">
                                    <span class="value" data-name="city" data-value="{{ $courtBranch->city }}">
                                        {{ $courtBranch->city }}
                                    </span>
                                </span>
                            </p>
                        </div>
                        <div class="col-4">
                            <h6 class="text-primary fw-semibold">Court Type</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-input position-relative pb-2">
                                    <span class="value" data-class="custom-datalist-input"
                                        data-datalist="{{ $courtTypes->toJson() }}" data-name="type"
                                        data-value="{{ $courtBranch->type }}">
                                        {{ $courtBranch->type }}
                                    </span>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-2">
                            <h6 class="text-primary fw-semibold">Court Branch</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-input">
                                    <span class="value" data-name="branch" data-value="{{ $courtBranch->branch }}">
                                        {{ $courtBranch->branch }}
                                    </span>
                                </span>
                            </p>
                        </div>
                        <div class="col-10">
                            <h6 class="text-primary fw-semibold">Judge</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-input">
                                    <span class="value" data-name="judge" data-value="{{ $courtBranch->judge }}">
                                        {{ $courtBranch->judge }}
                                    </span>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-4">
                            <x-contact.show :route="route('contact', ['courtBranch', $courtBranch->id])" />
                        </div>
                        <div class="col-8">
                            <h6 class="text-primary fw-semibold">Address</h6>
                            <p>
                                @if ($courtBranch->address)
                                    <span class="editable-text-area">
                                        <span class="value" data-name="address" data-value="{{ $courtBranch->address }}">
                                            {{ $courtBranch->address }}</span>
                                    </span>
                                @else
                                    <span class="editable-text-area">
                                        <span class="value text-muted" data-name="address" data-value="">
                                            No address found</span>
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end m-4 visually-hidden" id="submitBtnCon">
                        <div>
                            <button type="button" class="btn btn-secondary" id="cancelEditBtn">Cancel</button>
                            <button type="submit" class="btn btn-primary" form="editCourtBranchForm">Update
                                Court Branch</button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="blurCon">

                <div class="mt-4 col">
                    <ul class="nav nav-tabs " id="courtBranchTabs" role="tablist">
                        <li class="nav-item ps-2" role="presentation">
                            <a class="nav-link active" href="#hearings" id="hearingsTab" data-bs-toggle="tab"
                                data-bs-target="#hearingsContent" type="button" role="tab"
                                aria-controls="hearingsContent" aria-selected="false">
                                <span class="pe-2">Hearings</span>

                            </a>
                        </li>

                        <li class="nav-item ps-2" role="presentation">
                            <a class="nav-link " href="#notes" id="noteTab" data-bs-toggle="tab"
                                data-bs-target="#notesContent" type="button" role="tab"
                                aria-controls="notesContent" aria-selected="false">
                                <span class="pe-2">Notes</span>
                                <span class="badge rounded-pill bg-primary"
                                    id="note{{ $courtBranch->id }}NotesCount">{{ $courtBranch->notes_count }}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="tab-content">

                                <div class="tab-pane active" id="hearingsContent" role="tabpanel"
                                    aria-labelledby="hearingsTab">
                                    <div id="hearingsTable"></div>
                                </div>
                                <div class="tab-pane " id="notesContent" role="tabpanel" aria-labelledby="notesTab">
                                    <div class="d-flex justify-content-end align-items-start mb-3">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addNoteModal"
                                            data-callback="window.fetchAjaxTable('notes', '#notesTable' , 1, false);
                                            const container =$('#note{{ $courtBranch->id }}NotesCount');
                                            const notesCount = Number(container.text()) + 1;
                                            container.text(notesCount);
                                            "
                                            data-route="{{ route('note', ['courtBranch', $courtBranch->id]) }}">
                                            Add Note
                                        </button>
                                    </div>
                                    <div id="notesTable"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <x-contact.add-contact-modal :route="route('contact', ['courtBranch', $courtBranch->id])" />
    <x-contact.edit-contact-modal :route="route('contact', ['courtBranch', $courtBranch->id])" />
    <x-contact.delete-contact-modal :route="route('contact', ['courtBranch', $courtBranch->id])" />
    <x-court-branch.delete-court-branch-modal />
    <script>
        $(document).on('click', '#editCourtBranchBtn', function(e) {
            $(this).attr('disabled', true)
            $('#submitBtnCon').removeClass('visually-hidden')
            $('#blurCon').addClass('blur')
            // $('.editable-input span.value').each(function() {
            //     $span = $(this);
            //     currentValue = $span.data('value');
            //     name = $span.data('name') || 'editableField';
            //     className = $span.data('class') || '';
            //     datalist = $span.data('datalist');
            //     $span.hide();
            //     $input = window.editableInput(currentValue, name, className, datalist).insertAfter($span);

            // })
            $('.editable-input span.value').each(function() {
                $span = $(this);
                currentValue = $span.data('value');
                name = $span.data('name') || 'editableField';
                className = $span.data('class') || '';
                datalist = $span.data('datalist');
                $span.hide();

                $input = window.editableInput(currentValue, name, className, datalist).insertAfter($span);
                if ($input.hasClass('input-money')) {
                    Inputmask({
                        alias: "currency",
                        groupSeparator: ",",
                        digits: 2,
                        rightAlign: false,

                        removeMaskOnSubmit: true,
                    }).mask($input);
                }
                if ($input.hasClass('date-picker-default')) {
                    $($input).flatpickr({
                        altInput: true,
                        altFormat: "M j, Y",
                        dateFormat: "Y-m-d",
                    });
                }
                if ($input.hasClass('custom-datalist-input')) {
                    window.dataListInputInit($input[0])
                }
            })
            $('.editable-text-area span.value').each(function() {
                $span = $(this);
                currentValue = $span.data('value');
                name = $span.data('name') || 'editableField';
                $span.hide();
                $textarea = window.editableTextArea(currentValue, name).insertAfter($span);
            })
        })

        function cancelEdit(success = false) {
            $('#editCourtBranchBtn').attr('disabled', false)
            $('#submitBtnCon').addClass('visually-hidden')
            $('#blurCon').removeClass('blur')

            $('.editable-input span.value').each(function() {
                $span = $(this);
                if (success) {
                    newValue = $span.next('input.edit').val();
                    $span.data('value', newValue);
                    $span.text(newValue)
                }
                $span.show();
                $span.next('input.edit').remove()
            })
            $('.editable-text-area span.value').each(function() {
                $span = $(this);
                if (success) {
                    newValue = $span.next('textarea.edit').val();
                    $span.data('value', newValue);
                    $span.text(newValue)
                }
                $span.show();
                $span.next('textarea.edit').remove()
            })
        }
        $(document).on('click', '#cancelEditBtn', function(e) {
            cancelEdit()
        })

        $(document).on('submit', '#editCourtBranchForm', function(e) {
            e.preventDefault();
            var $submitBtn = $(this).find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('courtBranch.update', $courtBranch->id) }}',
                type: 'PUT',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        cancelEdit(true);
                        window.showToast('Success', response.message, 'success');
                    }
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                },
                error: function(xhr) {
                    window.toastError(xhr);
                    $submitBtn.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        })
        $(document).ready(function() {
            @if (request()->get('edit'))
                $('#editCourtBranchBtn').click();
            @endif
            var hash = window.location.hash;

            if (hash) {
                $('a[href="' + hash + '"][role="tab"]').tab('show');

                //  console.log($('a[href="' + hash + '"][role="tab"]'))
            }
            $('a[data-bs-toggle="tab"]').on('click', function() {
                var tabId = $(this).attr('href');
                history.pushState(null, null, tabId);
            });
            $(document).on('click', '#hearingsTable  .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                window.fetchAjaxTable('hearings', '#hearingsTable', page, false);
            });
            $(document).on('click', '#notesTable  .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                window.fetchAjaxTable('notes', '#notesTable', page, false);
            });

            function loadTables() {
                window.fetchAjaxTable('hearings', '#hearingsTable', 1, false)
                window.fetchAjaxTable('notes', '#notesTable', 1, false)
            }
            loadTables()
        });
    </script>
@endsection
