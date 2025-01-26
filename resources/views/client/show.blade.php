@extends('layout.app')
@section('title')
    {{ $client->name }}
@endsection
@section('main')
    <div class="container my-5">
        <!-- Client Overview -->
        <div class="card mb-4">
            <form id="editClientForm" class="m-0">
                @csrf
                <div class="card-header bg-light  text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 text-bg-light d-inline me-2">
                            Client:
                            <span class="editable-input">
                                <span class="value" data-name="name" data-value="{{ $client->name }}" data-class="w-init">
                                    {{ $client->name }}
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
                                        <span class="text-muted me-2">#{{ $client->id }}</span>
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <button class="dropdown-item" id="editClientBtn">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </button>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>

                                    <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                        data-bs-target="#deleteClientModal" data-client-id="{{ $client->id }}"
                                        data-modal-title="{{ $client->name }}">
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
                            <x-contact.show :route="route('contact', ['client', $client->id])" />
                        </div>
                        <div class="col-8">
                            <h6 class="text-primary fw-semibold">Address</h6>
                            <p>
                                @if ($client->address)
                                    <span class="editable-text-area">
                                        <span class="value" data-name="address" data-value="{{ $client->address }}">
                                            {{ $client->address }}</span>
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
                            <button type="submit" class="btn btn-primary" form="editClientForm">Update Client</button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="blurCon">
                <div class="card-body">
                    @can('view', getPermissionClass('Cases'))
                        <div class="row mb-4">
                            <div class=" col-lg-12">
                                <div class="card">
                                    <div class="card-header text-bg-primary">
                                        <h6 class="mb-0">Case Overview</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2 ">
                                            <span>Total Cases:</span>
                                            <span>{{ $client->law_cases_count }}</span>
                                        </div>
                                        <div class="border-top border-bottom">
                                            <div class="d-flex justify-content-between align-items-center  my-2">
                                                <div>Status</div>
                                                <div class="d-flex justify-content-end flex-wrap">
                                                    {!! $client->case_status_count_badges !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="border-bottom">
                                            <div class="d-flex justify-content-between align-items-center my-2">
                                                <div>Type</div>
                                                <div class="d-flex justify-content-end flex-wrap">
                                                    {!! $client->case_type_count_badges !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <div>Clients Role</div>
                                            <div class="d-flex justify-content-end flex-wrap">
                                                {!! $client->role_count_badges !!}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div id='extraInfo'>
                            {{-- <x-client-extra-info :client="$client" /> --}}
                        </div>
                        <div class="row">
                            <div class="col">
                                <x-case.cases-table :lawCases="$client->lawCases()" :typeId="$client->id" :type="'client'" />
                            </div>
                        </div>
                    @endcan
                    {{-- <div class="row">
                    <div class="col">

                        <x-billing.multi-billings-table :billings="$client->billings()" />
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <h5>Hearings</h5>
                        <div>Next Hearing: @if ($client->next_hearing)
                                {{ $client->next_hearing->formatted_hearing_date['full'] }}
                            @else
                                none
                            @endif
                        </div>
                        <table class="table table-striped table-bordered">
                            @if ($client->hearings->isEmpty())
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
                                @foreach ($client->hearings as $hearing)
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
                    <ul class="nav nav-tabs ps-2" role="tablist">
                        @can('view', getPermissionClass('Case Deposits'))
                            <li class="nav-item " role="presentation">
                                <a class="nav-link " href="#admin-deposits" id="adminFeeDepositTab" data-bs-toggle="tab"
                                    data-bs-target="#adminFeeDepositContent" type="button" role="tab"
                                    aria-controls="adminFeeDepositContent" aria-selected="true">
                                    Deposits
                                </a>
                            </li>
                        @endcan
                        @can('view', getPermissionClass('Case Expenses'))
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" href="#admin-fees" id="adminFeeTab" data-bs-toggle="tab"
                                    data-bs-target="#adminFeeContent" type="button" role="tab"
                                    aria-controls="adminFeeContent" aria-selected="false">
                                    Deductable Expenses
                                </a>
                            </li>
                        @endcan
                        @can('view', getPermissionClass('Case Hearings'))
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" href="#hearings" id="hearingTab" data-bs-toggle="tab"
                                    data-bs-target="#hearingContent" type="button" role="tab"
                                    aria-controls="hearingContent" aria-selected="false">
                                    Hearings
                                </a>
                            </li>
                        @endcan
                        @can('view', getPermissionClass('Case Billings'))
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" href="#billings" id="billingTab" data-bs-toggle="tab"
                                    data-bs-target="#billingContent" type="button" role="tab"
                                    aria-controls="billingContent" aria-selected="false">
                                    Bills
                                </a>
                            </li>
                        @endcan

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#notes" id="noteTab" data-bs-toggle="tab"
                                data-bs-target="#noteContent" type="button" role="tab" aria-controls="noteContent"
                                aria-selected="false">
                                <span class="pe-2">Notes</span>
                                <span class="badge rounded-pill border border-dark text-dark"
                                    id="note{{ $client->id }}NotesCount">{{ $client->notes_count }}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="tab-content">
                                @can('view', getPermissionClass('Case Deposits'))
                                    <!-- Admin Deposits Tab -->
                                    <div class="tab-pane" id="adminFeeDepositContent">
                                        <div id="adminDepositsTable">
                                            {{-- <x-admin-deposit.admin-deposits-table :adminDeposits="$client->adminDeposits()" /> --}}
                                        </div>

                                    </div>
                                @endcan
                                @can('view', getPermissionClass('Case Expenses'))
                                    <!-- Admin Fees Tab -->
                                    <div class="tab-pane fade " id="adminFeeContent" role="tabpanel"
                                        aria-labelledby="adminFeeTab">
                                        <div id="adminFeesTable">
                                            {{-- <x-admin-fee.admin-fees-table :adminFees="$client->adminFees()" /> --}}
                                        </div>

                                    </div>
                                @endcan
                                @can('view', getPermissionClass('Case Hearings'))
                                    <!-- Hearings Tab -->
                                    <div class="tab-pane fade" id="hearingContent" role="tabpanel"
                                        aria-labelledby="hearingTab">

                                        <div id="hearingsTable">
                                            {{-- <x-hearing.hearings-table :hearings="$client->hearings()" /> --}}
                                        </div>
                                    @endcan
                                    @can('view', getPermissionClass('Case Billings'))
                                    </div>
                                    <!-- Billings Tab -->
                                    <div class="tab-pane fade" id="billingContent" role="tabpanel"
                                        aria-labelledby="billingTab">

                                        <div id="billingsTable">
                                            {{-- <x-billing.billings-table :billings="$client->billings()" /> --}}
                                        </div>
                                    </div>
                                @endcan
                                <div class="tab-pane fade" id="noteContent" role="tabpanel" aria-labelledby="noteTab">
                                    <div class="d-flex justify-content-end align-items-start mb-3">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addNoteModal"
                                            data-callback="window.fetchAjaxTable('notes', '#notesTable' , 1, false);
                                            const container =$('#note{{ $client->id }}NotesCount');
                                            const notesCount = Number(container.text()) + 1;
                                            container.text(notesCount);
                                            "
                                            data-route="{{ route('note', ['client', $client->id]) }}">
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


    {{-- <x-admin-deposit.edit-admin-deposit-modal />
    <x-admin-deposit.delete-admin-deposit-modal />

    <x-admin-fee.edit-admin-fee-modal />
    <x-admin-fee.delete-admin-fee-modal />

    <x-hearing.edit-hearing-modal />
    <x-hearing.delete-hearing-modal />
    <x-get-branches-modal />

    <x-billing.edit-billing-modal />
    <x-billing.delete-billing-modal />

    <x-billing-deposit.view-billing-deposits-modal />
    <x-billing-deposit.add-billing-deposit-modal />
    <x-billing-deposit.edit-billing-deposit-modal />
    <x-billing-deposit.delete-billing-deposit-modal /> --}}

    @can('create', getPermissionClass('Case Deposits'))
        <x-admin-deposit.add-admin-deposit-modal />
    @endcan
    @can('update', getPermissionClass('Case Deposits'))
        <x-admin-deposit.edit-admin-deposit-modal />
    @endcan
    @can('delete', getPermissionClass('Case Deposits'))
        <x-admin-deposit.delete-admin-deposit-modal />
    @endcan

    @can('create', getPermissionClass('Case Expenses'))
        <x-admin-fee.add-admin-fee-modal />
    @endcan
    @can('update', getPermissionClass('Case Expenses'))
        <x-admin-fee.edit-admin-fee-modal />
    @endcan
    @can('delete', getPermissionClass('Case Expenses'))
        <x-admin-fee.delete-admin-fee-modal />
    @endcan

    @can('create', getPermissionClass('Case Hearings'))
        <x-hearing.add-hearing-modal />
    @endcan
    @can('update', getPermissionClass('Case Hearings'))
        <x-hearing.edit-hearing-modal />
    @endcan
    @can('delete', getPermissionClass('Case Hearings'))
        <x-hearing.delete-hearing-modal />
    @endcan
    @canany(['create', 'update'], getPermissionClass('Case Hearings'))
        <x-get-branches-modal />
    @endcanany

    @can('create', getPermissionClass('Case Billings'))
        <x-billing.add-billing-modal />
    @endcan
    @can('update', getPermissionClass('Case Billings'))
        <x-billing.edit-billing-modal />
    @endcan
    @can('delete', getPermissionClass('Case Billings'))
        <x-billing.delete-billing-modal />
    @endcan

    @can('view', getPermissionClass('Case Billing Deposits'))
        <x-billing-deposit.view-billing-deposits-modal />
    @endcan
    @can('create', getPermissionClass('Case Billing Deposits'))
        <x-billing-deposit.add-billing-deposit-modal />
    @endcan
    @can('update', getPermissionClass('Case Billing Deposits'))
        <x-billing-deposit.edit-billing-deposit-modal />
    @endcan
    @can('delete', getPermissionClass('Case Billing Deposits'))
        <x-billing-deposit.delete-billing-deposit-modal />
    @endcan


    {{-- <x-get-clients-modal />
    <x-get-associates-modal /> --}}

    <x-contact.add-contact-modal :route="route('contact', ['client', $client->id])" />
    <x-contact.edit-contact-modal :route="route('contact', ['client', $client->id])" />
    <x-contact.delete-contact-modal :route="route('contact', ['client', $client->id])" />

    @can('delete', getPermissionClass('Cases'))
        <x-client.delete-client-modal />
    @endcan

    <script>
        $(document).on('click', '#editClientBtn', function(e) {
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
            $('#editClientBtn').attr('disabled', false)
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

        $(document).on('submit', '#editClientForm', function(e) {
            e.preventDefault();
            var $submitBtn = $(this).find('button[type="submit"]');
            $submitBtn.prop('disabled', true).prepend(spinner);
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('client.update', $client->id) }}',
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

        $(document).on('click', '.courtBranchClear', function(e) {
            let hearingCardId = $(this).data('id')
            $(`#${hearingCardId} .courtBranchIdInp`).val('')
            $(`#${hearingCardId} .id`).text('')
            $(`#${hearingCardId} span`).text('')
        });

        function selectCourtBranch(courtBranch, hearingCardId) {
            $(`#${hearingCardId} .courtBranchIdInp`).val(courtBranch.id)
            $(`#${hearingCardId} .courtBranchInfo .id`).text(courtBranch.id)
            $(`#${hearingCardId} .courtBranchInfo .cBRegion`).text(courtBranch.region)
            $(`#${hearingCardId} .courtBranchInfo .cBCity`).text(courtBranch.city)
            $(`#${hearingCardId} .courtBranchInfo .cBType`).text(courtBranch.type)
            $(`#${hearingCardId} .courtBranchInfo .cBBranch`).text(courtBranch.branch)
        }

        function openCourtBranchModal() {
            $('#getCourtBranchesModal').modal('show')
        }
        $(document).on('click', '.courtBranchSearch', function(e) {
            openCourtBranchModal();
        });
        $(document).ready(function() {

            @if (request()->get('edit'))
                $('#editClientBtn').click();
            @endif
            var hash = window.location.hash;
            if (hash) {
                $('a[href="' + hash + '"][role="tab"]').tab('show');
            }
            $('a[data-bs-toggle="tab"]').on('click', function() {
                var tabId = $(this).attr('href');
                history.pushState(null, null, tabId);
            });

            $(document).on('click', '#adminDepositsTable  .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                window.fetchAjaxTable('admin-deposits', '#adminDepositsTable', page, false);
            });

            $(document).on('click', '#adminFeesTable  .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                window.fetchAjaxTable('admin-fees', '#adminFeesTable', page, false);
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


            $(document).on('click', '#billingsTable > nav .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                window.fetchAjaxTable('billings', '#billingsTable', page, false);
            });

            $(document).on('click', '#billingDepositsTable > .id-con .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                var billingId = $(this).closest('.id-con').data('billing-id');
                window.fetchAjaxTable('billing-deposits', '#billingDepositsTable', page, false,
                    billingId);
            });

            function getExcludedCaseIds() {
                return $('input[name="uncheck_cases"]:not(:checked)')
                    .map(function() {
                        return $(this).val();
                    })
                    .get();
            }

            function toggleRed() {
                const excludedIds = getExcludedCaseIds();
                if (excludedIds.length > 0) {
                    $('.accordion-button.cases .red-toggle').show();
                } else {
                    $('.accordion-button.cases .red-toggle').hide();
                }
            }
            $(document).on('change', 'input[name="uncheck_cases"]', function() {
                const excludedIds = getExcludedCaseIds();
                if (excludedIds.length > 0) {
                    $('.accordion-button.cases .red-toggle').show();
                } else {
                    $('.accordion-button.cases .red-toggle').hide();
                }
                toggleRed();
                // console.log($('input[name="uncheck_cases"]').length)
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.delete('excluded_case_ids[]');
                excludedIds.forEach((id) => {
                    urlParams.append('excluded_case_ids[]', id);
                });
                const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
                window.history.pushState({
                    path: newUrl
                }, '', newUrl);
                loadTables();
            });

            function loadTables() {
                $('#caseAccord').css("position", "relative").append(window.spinnerOverlay)
                const fetchPromises = [
                    window.fetchAjaxTable("extra-info", "#extraInfo"),
                    window.fetchAjaxTable('admin-deposits', '#adminDepositsTable', 1, false),
                    window.fetchAjaxTable('admin-fees', '#adminFeesTable', 1, false),
                    window.fetchAjaxTable('hearings', '#hearingsTable', 1, false),
                    window.fetchAjaxTable('billings', '#billingsTable', 1, false),
                    window.fetchAjaxTable('notes', '#notesTable', 1, false),
                ];

                Promise.all(fetchPromises).then(() => {
                    $('#caseAccord').find(".spinner-overlay").remove();
                }).catch(error => {
                    $('#caseAccord').find(".spinner-overlay").remove();
                });
            }
            toggleRed();
            loadTables();

        });
    </script>
@endsection
