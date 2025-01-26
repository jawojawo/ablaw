@extends('layout.app')

@section('title', $lawCase->case_number)

@section('main')
    <div class="container py-4">
        <!-- Case Information Section -->
        <div class="card mx-auto shadow-sm">
            <form id="editCaseForm">
                @csrf
                @include('case.partials.case-info')
            </form>

            <div id="blurCon">
                <div class="card-body">
                    <div id='extraInfo'>
                        <x-extra-info :lawCase="$lawCase" />
                    </div>
                </div>
                <!-- Tabs for Admin Fees, Hearings, and Billings -->
                <div class="mt-4 col">
                    <ul class="nav nav-tabs ps-2" id="lawCaseTabs" role="tablist">
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
                                <span class="badge rounded-pill border-dark border text-dark"
                                    id="note{{ $lawCase->id }}NotesCount">{{ $lawCase->notes_count }}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="tab-content">
                                @can('view', getPermissionClass('Case Deposits'))
                                    <!-- Admin Deposits Tab -->
                                    <div class="tab-pane" id="adminFeeDepositContent">
                                        @can('create', getPermissionClass('Case Deposits'))
                                            <div class="d-flex justify-content-end mb-3">
                                                <button class="btn btn-primary" data-law-case-id={{ $lawCase->id }}
                                                    data-bs-toggle="modal" data-bs-target="#addAdminDepositModal">
                                                    Add Deposit
                                                </button>
                                            </div>
                                        @endcan
                                        <div id="adminDepositsTable"></div>
                                    </div>
                                @endcan
                                @can('view', getPermissionClass('Case Expenses'))
                                    <!-- Admin Fees Tab -->
                                    <div class="tab-pane fade " id="adminFeeContent" role="tabpanel"
                                        aria-labelledby="adminFeeTab">
                                        @can('create', getPermissionClass('Case Expenses'))
                                            <div class="d-flex justify-content-end mb-3">
                                                <button class="btn btn-primary" data-law-case-id="{{ $lawCase->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#addAdminFeeModal">
                                                    Add Expense
                                                </button>
                                            </div>
                                        @endcan
                                        <div id="adminFeesTable"></div>
                                    </div>
                                @endcan
                                @can('view', getPermissionClass('Case Hearings'))
                                    <!-- Hearings Tab -->
                                    <div class="tab-pane fade" id="hearingContent" role="tabpanel" aria-labelledby="hearingTab">
                                        @can('create', getPermissionClass('Case Hearings'))
                                            <div class="d-flex justify-content-end align-items-start mb-3">
                                                <button class="btn btn-primary" data-law-case-id="{{ $lawCase->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#addHearingModal">
                                                    Add Hearing
                                                </button>
                                            </div>
                                        @endcan
                                        <div id="hearingsTable"></div>
                                    </div>
                                @endcan
                                @can('view', getPermissionClass('Case Billings'))
                                    <!-- Billings Tab -->
                                    <div class="tab-pane fade" id="billingContent" role="tabpanel"
                                        aria-labelledby="billingTab">
                                        @can('create', getPermissionClass('Case Billings'))
                                            <div class="d-flex justify-content-end align-items-start mb-3">
                                                <button class="btn btn-primary" data-bs-toggle="modal"
                                                    data-law-case-id="{{ $lawCase->id }}" data-bs-target="#addBillingModal">
                                                    Add Bill
                                                </button>
                                            </div>
                                        @endcan
                                        <div id="billingsTable"></div>
                                    </div>
                                @endcan

                                <div class="tab-pane fade" id="noteContent" role="tabpanel" aria-labelledby="noteTab">
                                    <div class="d-flex justify-content-end align-items-start mb-3">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addNoteModal"
                                            data-callback="window.fetchAjaxTable('notes', '#notesTable' , 1, false);
                                            const container =$('#note{{ $lawCase->id }}NotesCount');
                                            const notesCount = Number(container.text()) + 1;
                                            container.text(notesCount);
                                            "
                                            data-route="{{ route('note', ['case', $lawCase->id]) }}">
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

    @can('update', getPermissionClass('Cases'))
        <x-change-status-modal />
        <x-get-clients-modal />
        <x-get-associates-modal />

        <x-contact.add-contact-modal :route="route('contact', ['case', $lawCase->id])" />
        <x-contact.edit-contact-modal :route="route('contact', ['case', $lawCase->id])" />
        <x-contact.delete-contact-modal :route="route('contact', ['case', $lawCase->id])" />
    @endcan
    @can('delete', getPermissionClass('Cases'))
        <x-case.delete-case-modal />
    @endcan
    <script>
        @can('update', $lawCase)
            $(document).on('click', '#editCaseBtn', function(e) {
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
                $('.editable-select span.value').each(function() {
                    $span = $(this);
                    currentValue = $span.data('value');
                    name = $span.data('name') || 'editableField';
                    values = $span.data('values')
                    $span.hide();
                    $select = window.editableSelect(values, name, currentValue).insertAfter($span);
                })
                $('.editable-client span.value').each(function() {
                    $span = $(this);
                    clientId = $span.data('client-id')
                    clientName = $span.data('client-name')
                    $span.hide();
                    $(`<div id="clientInfo" class="info edit client">
                    <input type="hidden" name="client_id" id="clientIdInp" value="${clientId}">
                    <div id="clientId" class="id">${clientId}</div>
                    <div id="clientName" class="name">${clientName}</div>
                    <button type="button" class="btn btn-primary infoBtn searchBtn" id="clientSearch"
                        data-bs-toggle="modal" data-bs-target="#getClientsModal"><i
                            class="bi bi-search"></i></button>
                    <button type="button" class="btn btn-danger infoBtn clearBtn" id="clientClear"><i
                            class="bi bi-x-lg"></i></button>
                </div>`).insertAfter($span);
                })
                $('.editable-associate span.value').each(function() {
                    $span = $(this);
                    associateId = $span.data('associate-id')
                    associateName = $span.data('associate-name')
                    $span.hide();
                    $(`<div id="associateInfo" class="info associate edit">
                     <input  type="hidden" name="associate_id"
                            id="associateIdInp" value="${associateId}" >
                            <div id="associateId" class="id">${associateId}</div>
                            <div id="associateName" class="name">${associateName}</div>
                            <button type="button" class="btn btn-primary infoBtn searchBtn" id="associateSearch"
                                data-bs-toggle="modal" data-bs-target="#getAssociatesModal"><i
                                    class="bi bi-search"></i></button>
                            <button type="button" class="btn btn-danger infoBtn clearBtn" id="associateClear"><i
                                    class="bi bi-x-lg"></i></button>
                        </div>
                       `).insertAfter($span);
                })

            })

            function cancelEdit(success = false) {
                $('#editCaseBtn').attr('disabled', false)
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
                $('.editable-select span.value').each(function() {
                    $span = $(this);
                    if (success) {
                        newValue = $span.next('select.edit').val();
                        $span.data('value', newValue);
                        $span.text(window.strHeadline(newValue))
                    }
                    $span.show();
                    $span.next('select.edit').remove()
                })
                $('.editable-client span.value').each(function() {
                    $span = $(this);
                    if (success) {
                        newId = $span.next('.info').find('.id').text();
                        newName = $span.next('.info').find('.name').text();
                        $span.data('client-id', newId).data('client-name', newName);
                        $span.text(window.strHeadline(newName))
                    }
                    $span.show();
                    $('.client.edit').remove()
                })
                $('.editable-associate span.value').each(function() {
                    $span = $(this);
                    if (success) {
                        newId = $span.next('.info').find('.id').text();
                        newName = $span.next('.info').find('.name').text();
                        $span.data('associate-id', newId).data('associate-name', newName);
                        $span.text(window.strHeadline(newName))
                    }
                    $span.show();
                    $('.associate.edit').remove()
                })
            }
            $(document).on('click', '#cancelEditBtn', function(e) {
                cancelEdit()
            })
            $(document).on('submit', '#editCaseForm', function(e) {
                e.preventDefault();
                var $submitBtn = $(this).find('button[type="submit"]');
                $submitBtn.prop('disabled', true).prepend(spinner);
                var formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('case.update', $lawCase->id) }}',
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

            function selectClient(client) {
                $('#clientIdInp').val(client.id)
                $('#clientId').text(client.id)
                $('#clientName').text(client.name)
            }

            $(document).on('click', '#clientClear', function(e) {
                $('#clientIdInp').val('')
                $('#clientId').text('')
                $('#clientName').text('')
            });

            function selectAssociate(associate) {
                $('#associateIdInp').val(associate.id)
                $('#associateId').text(associate.id)
                $('#associateName').text(associate.name)
            }
            $(document).on('click', '#associateClear', function(e) {
                $('#associateIdInp').val('')
                $('#associateId').text('')
                $('#associateName').text('')
            });
        @else
            $(document).on('click', '#editCaseBtn', function(e) {
                e.preventDefault()
            })
        @endcan

        @canany(['create', 'update'], getPermissionClass('Case Hearings'))
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
        @endcanany

        $(document).ready(function() {

            @if (request()->get('edit'))
                $('#editCaseBtn').click();
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
                window.fetchAjaxTable('admin-deposits', '#adminDepositsTable', page);
            });

            $(document).on('click', '#adminFeesTable  .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                window.fetchAjaxTable('admin-fees', '#adminFeesTable', page);
            });

            $(document).on('click', '#hearingsTable  .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                window.fetchAjaxTable('hearings', '#hearingsTable', page);
            });


            $(document).on('click', '#billingsTable > nav .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                window.fetchAjaxTable('billings', '#billingsTable', page);
            });

            $(document).on('click', '#billingDepositsTable > .id-con .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                var billingId = $(this).closest('.id-con').data('billing-id');
                window.fetchAjaxTable('billing-deposits', '#billingDepositsTable', page, false,
                    billingId);
            });

            function loadTables() {
                @if (auth()->user()->can('view', getPermissionClass('Case Deposits')) ||
                        auth()->user()->can('view', getPermissionClass('Case Expenses')))
                    window.fetchAjaxTable("extra-info", "#extraInfo");
                @endif
                @can('view', getPermissionClass('Case Deposits'))
                    window.fetchAjaxTable('admin-deposits', '#adminDepositsTable', 1, false)
                @endcan
                @can('view', getPermissionClass('Case Expenses'))
                    window.fetchAjaxTable('admin-fees', '#adminFeesTable', 1, false)
                @endcan
                @can('view', getPermissionClass('Case Hearings'))
                    window.fetchAjaxTable('hearings', '#hearingsTable', 1, false)
                @endcan
                @can('view', getPermissionClass('Case Billings'))
                    window.fetchAjaxTable('billings', '#billingsTable', 1, false)
                @endcan
                window.fetchAjaxTable('notes', '#notesTable', 1, false)
            }

            loadTables();
        });
    </script>
@endsection
