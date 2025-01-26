@extends('layout.app')
@section('title')
    {{ $customEvent->type }}
@endsection
@section('main')
    <div class="container my-5">

        <div class="card mb-4">
            <form id="editCustomEventForm" class="m-0">
                @csrf
                <div class="card-header bg-light  text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-bg-light d-inline me-2">
                            Custom Event
                        </h5>
                    </div>
                    <div>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                aria-expanded="false"></button>
                            <ul class="dropdown-menu shadow">
                                <li>
                                    <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                        <span class="text-muted me-2">#{{ $customEvent->id }}</span>
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <button class="dropdown-item" id="editCustomEventBtn">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </button>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                        data-bs-target="#deleteCustomEventModal"
                                        data-custom-event-id="{{ $customEvent->id }}"
                                        data-modal-title="{{ $customEvent->type }}">
                                        <i class="bi bi-trash me-2"></i>Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-8">
                            <h6 class="text-primary fw-semibold">Title</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-input">
                                    <span class="value" data-name="title" data-value="{{ $customEvent->title }}">
                                        {{ $customEvent->title }}
                                    </span>
                                </span>
                            </p>
                        </div>
                        <div class="col-4">
                            <h6 class="text-primary fw-semibold">Type</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-input position-relative pb-2">
                                    <span class="value" data-class="custom-datalist-input "
                                        data-datalist="{{ $types->toJson() }}" data-name="type"
                                        data-value="{{ $customEvent->type }}">
                                        {{ $customEvent->type }}
                                    </span>
                                </span>
                            </p>
                        </div>

                        {{-- <div class="col-4">
                            <h6 class="text-primary fw-semibold">Date</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-input">
                                    <span class="value" data-name="expense_date" data-class="date-picker-default"
                                        data-value="{{ $customEvent->expense_date }}">
                                        {{ formattedDate($customEvent->expense_date) }}
                                    </span>
                                </span>
                            </p>
                        </div> --}}
                    </div>
                    <div class="row mb-4">
                        <div class="col-4">
                            <div class="row mb-4">
                                <div class="col-6">
                                    <h6 class="text-primary fw-semibold">From</h6>
                                    <p class="mb-0 fs-6 text-dark">
                                        <span class="editable-input position-relative">
                                            <span class="value" data-name="start_time"
                                                data-class="date-time-picker-default"
                                                data-value="{{ $customEvent->start_time }}">
                                                {{ formattedDateTime($customEvent->start_time) }}
                                            </span>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-primary fw-semibold">To</h6>
                                    <p class="mb-0 fs-6 text-dark">
                                        <span class="editable-input">
                                            <span class="value" data-name="end_time" data-class="date-time-picker-default"
                                                data-value="{{ $customEvent->end_time }}">
                                                {{ formattedDateTime($customEvent->end_time) }}
                                            </span>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-primary fw-semibold">Location</h6>
                                    <p class="mb-0 fs-6 text-dark">
                                        <span class="editable-input">
                                            <span class="value" data-name="location"
                                                data-value="{{ $customEvent->location }}">
                                                @if ($customEvent->location)
                                                    {{ $customEvent->location }}
                                                @else
                                                    <span class="text-muted">None</span>
                                                @endif
                                            </span>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
                            <h6 class="text-primary fw-semibold">Description</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-text-area">
                                    <span class="value" data-name="description"
                                        data-value="{{ $customEvent->description }}">
                                        {{ $customEvent->description }}
                                    </span>
                                </span>
                            </p>
                        </div>

                    </div>
                    <div class="d-flex justify-content-end m-4 visually-hidden" id="submitBtnCon">
                        <div>
                            <button type="button" class="btn btn-secondary" id="cancelEditBtn">Cancel</button>
                            <button type="submit" class="btn btn-primary" form="editCustomEventForm">Update
                                Custom Event</button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="blurCon">

                <div class="mt-4 col">
                    <ul class="nav nav-tabs " id="customEventTabs" role="tablist">


                        <li class="nav-item ps-2" role="presentation">
                            <a class="nav-link active" href="#notes" id="noteTab" data-bs-toggle="tab"
                                data-bs-target="#notesContent" type="button" role="tab" aria-controls="notesContent"
                                aria-selected="false">
                                <span class="pe-2">Notes</span>
                                <span class="badge rounded-pill bg-primary"
                                    id="note{{ $customEvent->id }}NotesCount">{{ $customEvent->notes_count }}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="notesContent" role="tabpanel"
                                    aria-labelledby="notesTab">
                                    <div class="d-flex justify-content-end align-items-start mb-3">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addNoteModal"
                                            data-callback="window.fetchAjaxTable('notes', '#notesTable' , 1, false);
                                            const container =$('#note{{ $customEvent->id }}NotesCount');
                                            const notesCount = Number(container.text()) + 1;
                                            container.text(notesCount);
                                            "
                                            data-route="{{ route('note', ['customEvent', $customEvent->id]) }}">
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

    @can('delete', getPermissionClass('Custom Events'))
        <x-custom-event.delete-custom-event-modal />
    @endcan
    <script>
        $(document).on('click', '#editCustomEventBtn', function(e) {
            $(this).attr('disabled', true)
            $('#submitBtnCon').removeClass('visually-hidden')
            $('#blurCon').addClass('blur')
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
                        onReady: function(selectedDates, dateStr, instance) {
                            instance.calendarContainer.style.marginTop = "-40px";
                        },
                    });
                }
                if ($input.hasClass('date-time-picker-default')) {
                    $($input).flatpickr({
                        altInput: true,
                        altFormat: "M j, Y h:i K",
                        dateFormat: "Y-m-d H:i",
                        enableTime: true,
                        onReady: function(selectedDates, dateStr, instance) {
                            instance.calendarContainer.style.marginTop = "-40px";
                        },

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
            $('#editCustomEventBtn').attr('disabled', false)
            $('#submitBtnCon').addClass('visually-hidden')
            $('#blurCon').removeClass('blur')

            $('.editable-input span.value').each(function() {
                $span = $(this);

                if (success) {
                    newValue = $span.next('input.edit').val();
                    $span.data('value', newValue);
                    $span.text(newValue)
                    if ($span.data('class') == 'date-picker-default') {
                        $span.text(window.formatDate(newValue))
                    }
                    if ($span.data('class') == 'date-time-picker-default') {
                        $span.text(window.formatDateTime(newValue))
                    }
                }
                $span.show();
                $span.parent().find('input.edit').remove()
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

        $(document).on('submit', '#editCustomEventForm', function(e) {
            e.preventDefault();
            var $submitBtn = $(this).find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('customEvent.update', $customEvent->id) }}',
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
                $('#editCustomEventBtn').click();
            @endif
            var hash = window.location.hash;

            if (hash) {
                $('a[href="' + hash + '"][role="tab"]').tab('show');
            }
            $('a[data-bs-toggle="tab"]').on('click', function() {
                var tabId = $(this).attr('href');
                history.pushState(null, null, tabId);
            });

            $(document).on('click', '#notesTable  .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                window.fetchAjaxTable('notes', '#notesTable', page, false);
            });

            function loadTables() {

                window.fetchAjaxTable('notes', '#notesTable', 1, false)
            }
            loadTables()
        });
    </script>
@endsection
