@extends('layout.app')
@section('title')
    {{ $officeExpense->type }}
@endsection
@section('main')
    <div class="container my-5">

        <div class="card mb-4">
            <form id="editOfficeExpenseForm" class="m-0">
                @csrf
                <div class="card-header bg-light  text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-bg-light d-inline me-2">
                            Office Expense
                        </h5>
                    </div>
                    <div>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                aria-expanded="false"></button>
                            <ul class="dropdown-menu shadow">
                                <li>
                                    <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                        <span class="text-muted me-2">#{{ $officeExpense->id }}</span>
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <button class="dropdown-item" id="editOfficeExpenseBtn">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </button>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                        data-bs-target="#deleteOfficeExpenseModal"
                                        data-office-expense-id="{{ $officeExpense->id }}"
                                        data-modal-title="{{ $officeExpense->type }}">
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
                            <h6 class="text-primary fw-semibold">Type</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-input position-relative pb-2">
                                    <span class="value" data-class="custom-datalist-input "
                                        data-datalist="{{ $types->toJson() }}" data-name="type"
                                        data-value="{{ $officeExpense->type }}">
                                        {{ $officeExpense->type }}
                                    </span>
                                </span>
                            </p>
                        </div>
                        <div class="col-4">
                            <h6 class="text-primary fw-semibold">Amount</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-input">
                                    <span class="value" data-name="amount" data-class="input-money"
                                        data-value="{{ $officeExpense->amount }}">
                                        {{ formattedMoney($officeExpense->amount) }}
                                    </span>
                                </span>
                            </p>
                        </div>
                        <div class="col-4">
                            <h6 class="text-primary fw-semibold">Date</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-input">
                                    <span class="value" data-name="expense_date" data-class="date-picker-default"
                                        data-value="{{ $officeExpense->expense_date }}">
                                        {{ formattedDate($officeExpense->expense_date) }}
                                    </span>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary fw-semibold">Description</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-text-area">
                                    <span class="value" data-name="description"
                                        data-value="{{ $officeExpense->description }}">
                                        {{ $officeExpense->description }}
                                    </span>
                                </span>
                            </p>
                        </div>

                    </div>
                    <div class="d-flex justify-content-end m-4 visually-hidden" id="submitBtnCon">
                        <div>
                            <button type="button" class="btn btn-secondary" id="cancelEditBtn">Cancel</button>
                            <button type="submit" class="btn btn-primary" form="editOfficeExpenseForm">Update
                                Office Expense</button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="blurCon">

                <div class="mt-4 col">
                    <ul class="nav nav-tabs " id="officeExpenseTabs" role="tablist">


                        <li class="nav-item ps-2" role="presentation">
                            <a class="nav-link active" href="#notes" id="noteTab" data-bs-toggle="tab"
                                data-bs-target="#notesContent" type="button" role="tab" aria-controls="notesContent"
                                aria-selected="false">
                                <span class="pe-2">Notes</span>
                                <span class="badge rounded-pill bg-primary"
                                    id="note{{ $officeExpense->id }}NotesCount">{{ $officeExpense->notes_count }}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="notesContent" role="tabpanel" aria-labelledby="notesTab">
                                    <div class="d-flex justify-content-end align-items-start mb-3">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addNoteModal"
                                            data-callback="window.fetchAjaxTable('notes', '#notesTable' , 1, false);
                                            const container =$('#note{{ $officeExpense->id }}NotesCount');
                                            const notesCount = Number(container.text()) + 1;
                                            container.text(notesCount);
                                            "
                                            data-route="{{ route('note', ['officeExpense', $officeExpense->id]) }}">
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
    @can('delete', getPermissionClass('Office Expenses'))
        <x-office-expense.delete-office-expense-modal />
    @endcan

    <script>
        $(document).on('click', '#editOfficeExpenseBtn', function(e) {
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
            $('#editOfficeExpenseBtn').attr('disabled', false)
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

        $(document).on('submit', '#editOfficeExpenseForm', function(e) {
            e.preventDefault();
            var $submitBtn = $(this).find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('officeExpense.update', $officeExpense->id) }}',
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
                $('#editOfficeExpenseBtn').click();
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
