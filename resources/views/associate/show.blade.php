@extends('layout.app')
@section('title')
    {{ $associate->name }}
@endsection
@section('main')
    <div class="container my-5">
        <!-- associate Overview -->
        <div class="card mb-4">
            <form id="editAssociateForm" class="m-0">
                @csrf
                <div class="card-header bg-light  text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-bg-light d-inline me-2">
                            Associate:
                            <span class="editable-input">
                                <span class="value" data-name="name" data-value="{{ $associate->name }}" data-class="w-init">
                                    {{ $associate->name }}
                                </span>
                            </span>
                        </h5>
                    </div>
                    <div>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                aria-expanded="false"></button>
                            <ul class="dropdown-menu shadow">
                                <li>
                                    <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                        <span class="text-muted me-2">#{{ $associate->id }}</span>
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <button class="dropdown-item" id="editAssociateBtn">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </button>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                        data-bs-target="#deleteAssociateModal" data-associate-id="{{ $associate->id }}"
                                        data-modal-title="{{ $associate->name }}">
                                        <i class="bi bi-trash me-2"></i>Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row ">
                        <div class="col-4">
                            <x-contact.show :route="route('contact', ['associate', $associate->id])" />
                        </div>
                        <div class="col-8">
                            <h6 class="text-primary fw-semibold">Address</h6>
                            <p>
                                @if ($associate->address)
                                    <span class="editable-text-area">
                                        <span class="value" data-name="address" data-value="{{ $associate->address }}">
                                            {{ $associate->address }}</span>
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
                            <button type="submit" class="btn btn-primary" form="editAssociateForm">Update
                                Associate</button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="blurCon">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class=" col-lg-12">
                            <div class="card">
                                <div class="card-header text-bg-primary">
                                    <h6 class="mb-0">Case Overview</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2 ">
                                        <span>Total Cases:</span>
                                        <span>{{ $associate->law_cases_count }}</span>
                                    </div>
                                    <div class="border-top border-bottom">
                                        <div class="d-flex justify-content-between align-items-center  my-2">
                                            <div>Status</div>
                                            <div class="d-flex justify-content-end flex-wrap">
                                                {!! $associate->case_status_count_badges !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center my-2">
                                            <div>Type</div>
                                            <div class="d-flex justify-content-end flex-wrap">
                                                {!! $associate->case_type_count_badges !!}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    {{-- <div id='extraInfo'>
                        <x-associate-extra-info :associate="$associate" />
                    </div> --}}
                    {{-- <div class="row">
                        <div class="col">
                            <x-case.cases-table-default :lawCases="$associate->lawCases()" :excludeCol="['associate']" />
                        </div>
                    </div> --}}
                    {{-- <div class="row">
                    <div class="col">

                        <x-billing.multi-billings-table :billings="$associate->billings()" />
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <h5>Hearings</h5>
                        <div>Next Hearing: @if ($associate->next_hearing)
                                {{ $associate->next_hearing->formatted_hearing_date['full'] }}
                            @else
                                none
                            @endif
                        </div>
                        <table class="table table-striped table-bordered">
                            @if ($associate->hearings->isEmpty())
                                <caption>No Hearings Found</caption>
                            @else
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Case Number</th>
                                        <th>Title</th>
                                        <th>Hearing Date</th>

                                    </tr>
                                </thead>
                                @foreach ($associate->hearings as $hearing)
                                    <tr>
                                        <td>{{ $hearing->id }}</td>
                                        <td>{{ $hearing->lawCase->case_number }}</td>
                                        <td>{{ $hearing->title }}</td>
                                        <td>{{ $hearing->hearing_date }}</td>

                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div> --}}
                </div>

                <!-- Tabs for Admin Fees, Hearings, and Billings -->
                <div class="mt-4 col">
                    <ul class="nav nav-tabs " id="associateTabs" role="tablist">
                        <li class="nav-item ps-2" role="presentation">
                            <a class="nav-link active" href="#cases" id="caseTab" data-bs-toggle="tab"
                                data-bs-target="#casesContent" type="button" role="tab" aria-controls="casesContent"
                                aria-selected="false">
                                <span class="pe-2">Cases</span>

                            </a>
                        </li>
                        <li class="nav-item ps-2" role="presentation">
                            <a class="nav-link " href="#notes" id="noteTab" data-bs-toggle="tab"
                                data-bs-target="#notesContent" type="button" role="tab" aria-controls="notesContent"
                                aria-selected="false">
                                <span class="pe-2">Notes</span>
                                <span class="badge rounded-pill bg-primary"
                                    id="note{{ $associate->id }}NotesCount">{{ $associate->notes_count }}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="tab-content">

                                <div class="tab-pane active" id="casesContent" role="tabpanel"
                                    aria-labelledby="casesTab">
                                    <div id="casesTable"></div>
                                </div>
                                <div class="tab-pane" id="notesContent" role="tabpanel" aria-labelledby="notesTab">
                                    <div class="d-flex justify-content-end align-items-start mb-3">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addNoteModal"
                                            data-callback="window.fetchAjaxTable('notes', '#notesTable' , 1, false);
                                            const container =$('#note{{ $associate->id }}NotesCount');
                                            const notesCount = Number(container.text()) + 1;
                                            container.text(notesCount);
                                            "
                                            data-route="{{ route('note', ['associate', $associate->id]) }}">
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
    <x-contact.add-contact-modal :route="route('contact', ['associate', $associate->id])" />
    <x-contact.edit-contact-modal :route="route('contact', ['associate', $associate->id])" />
    <x-contact.delete-contact-modal :route="route('contact', ['associate', $associate->id])" />
    @can('delete', getPermissionClass('Associates'))
        <x-associate.delete-associate-modal />
    @endcan
    <script>
        $(document).on('click', '#editAssociateBtn', function(e) {
            $(this).attr('disabled', true)
            $('#submitBtnCon').removeClass('visually-hidden')
            $('#blurCon').addClass('blur')
            $('.editable-input span.value').each(function() {
                $span = $(this);
                currentValue = $span.data('value');
                name = $span.data('name') || 'editableField';
                className = $span.data('class') || '';
                $span.hide();
                $input = window.editableInput(currentValue, name, className).insertAfter($span);
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
            $('#editAssociateBtn').attr('disabled', false)
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

        $(document).on('submit', '#editAssociateForm', function(e) {
            e.preventDefault();
            var $submitBtn = $(this).find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('associate.update', $associate->id) }}',
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
                $('#editAssociateBtn').click();
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
            $(document).on('click', '#casesTable  .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                window.fetchAjaxTable('cases', '#casesTable', page, false);
            });
            $(document).on('click', '#notesTable  .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                window.fetchAjaxTable('notes', '#notesTable', page, false);
            });

            function loadTables() {
                window.fetchAjaxTable('cases', '#casesTable', 1, false)
                window.fetchAjaxTable('notes', '#notesTable', 1, false)
            }
            loadTables()
        });
    </script>
@endsection
