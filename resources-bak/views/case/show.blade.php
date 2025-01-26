@extends('layout.app')

@section('title', $lawCase->case_number)

@section('main')
    <div class="container py-4">
        <!-- Case Information Section -->
        <div class="card mx-auto shadow-sm">
            <form id="editCaseForm">
                @csrf
                <div class="card-header bg-light  text-white d-flex justify-content-between align-items-center">
                    <div class='d-flex align-items-center'>
                        <h5 class="mb-0 text-bg-light d-inline me-2">
                            Case:
                            <span class="editable-input">
                                <span class="value" data-name="case_number" data-value="{{ $lawCase->case_number }}"
                                    data-class="w-init">
                                    {{ $lawCase->case_number }}
                                </span>
                            </span>
                        </h5>
                        <a href="#" class="update-status-btn" data-bs-toggle="modal"
                            data-bs-target="#changeStatusModal" data-case-id="{{ $lawCase->id }}"
                            data-case-status="{{ $lawCase->status }}">
                            <span id='statusCon{{ $lawCase->id }}'>
                                {!! $lawCase->status_badge !!}
                            </span>
                        </a>
                    </div>
                    <div>
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
                                {{-- <li>
                                <a class="dropdown-item update-status-btn" data-bs-toggle="modal"
                                    data-bs-target="#changeStatusModal" data-case-id="{{ $lawCase->id }}"
                                    data-case-status="{{ $lawCase->status }}">
                                    <i class="bi bi-arrow-repeat me-2"></i>Update Status
                                </a>
                            </li> --}}
                                <li>
                                    <button class="dropdown-item" id="editCaseBtn">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </button>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash me-2"></i>Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <h6 class="text-primary fw-semibold">Title</h6>
                            <p class="mb-0 fst-italic fs-5 text-dark">
                                <span class="editable-text-area">
                                    <span class="value" data-name="case_title"
                                        data-value="{{ $lawCase->case_title }}">{{ $lawCase->case_title }}</span>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-4">
                            <h6 class="text-primary fw-semibold">Client</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-client">
                                    <span class="value" data-name="client_id" data-client-id="{{ $lawCase->client_id }}"
                                        data-client-full-name="{{ $lawCase->client_fullname }}">{{ $lawCase->client_fullname }}</span>
                                </span>
                            </p>
                        </div>
                        <div class="col-lg-4">
                            <h6 class="text-primary fw-semibold">Role</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-select">
                                    <span class="value" data-name="party_role"
                                        data-selected-value="{{ $lawCase->party_role }}"
                                        data-values="{{ json_encode(config('enums.party_roles')) }}">
                                        {{ Str::headline($lawCase->party_role) }}
                                    </span>
                                </span>

                            </p>
                        </div>
                        <div class="col-lg-4">
                            <h6 class="text-primary fw-semibold">Opposing Party</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-input">
                                    <span class="value" data-name="opposing_party"
                                        data-value="{{ $lawCase->opposing_party }}">{{ $lawCase->opposing_party }}</span>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 mb-3">
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
                        </div>
                        <div class="col-lg-4 mb-3">
                            <h6 class="text-primary fw-semibold">Associate</h6>
                            <p class="mb-0 fs-6 text-dark">
                                <span class="editable-associate">
                                    <span class="value" data-name="associate_id"
                                        data-associate-id="{{ $lawCase->associate_id }}"
                                        data-associate-full-name="{{ $lawCase->associate_fullname }}">{{ $lawCase->associate_fullname }}</span>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end m-4 visually-hidden" id="submitBtnCon">
                        <div>
                            <button type="button" class="btn btn-secondary" id="cancelEditBtn"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" form="editCaseForm">Update Case</button>
                        </div>
                    </div>
                </div>
            </form>

            <div id="blurCon">
                <div class="card-body">
                    <div id='extraInfo'>
                        <x-extra-info :lawCase="$lawCase" />
                    </div>
                </div>
                <!-- Tabs for Admin Fees, Hearings, and Billings -->
                <div class="mt-4 col">
                    <ul class="nav nav-tabs " id="lawCaseTabs" role="tablist">
                        <li class="nav-item ps-2" role="presentation">
                            <a class="nav-link active" href="#admin-deposits" id="adminFeeDepositTab"
                                data-bs-toggle="tab" data-bs-target="#adminFeeDepositContent" type="button"
                                role="tab" aria-controls="adminFeeDepositContent" aria-selected="true">
                                Admin Deposits
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#admin-fees" id="adminFeeTab" data-bs-toggle="tab"
                                data-bs-target="#adminFeeContent" type="button" role="tab"
                                aria-controls="adminFeeContent" aria-selected="false">
                                Admin Fees
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#hearings" id="hearingTab" data-bs-toggle="tab"
                                data-bs-target="#hearingContent" type="button" role="tab"
                                aria-controls="hearingContent" aria-selected="false">
                                Hearings
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#billings" id="billingTab" data-bs-toggle="tab"
                                data-bs-target="#billingContent" type="button" role="tab"
                                aria-controls="billingContent" aria-selected="false">
                                Billings
                            </a>
                        </li>
                    </ul>
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Admin Deposits Tab -->
                                <div class="tab-pane  active" id="adminFeeDepositContent">
                                    <div class="alert alert-success " id="adminDepositAlert"></div>
                                    <div class="d-flex justify-content-end mb-3">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addAdminDepositModal">
                                            Add Admin Deposit
                                        </button>
                                    </div>
                                    <div id="adminDepositsTable">
                                        <x-admin-deposit.admin-deposits-table :adminDeposits="$lawCase->adminDeposits()" />
                                    </div>

                                </div>
                                <!-- Admin Fees Tab -->
                                <div class="tab-pane fade " id="adminFeeContent" role="tabpanel"
                                    aria-labelledby="adminFeeTab">
                                    <div class="alert alert-success " id="adminFeeAlert"></div>
                                    <div class="d-flex justify-content-end mb-3">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addAdminFeeModal">
                                            Add Admin Fee
                                        </button>
                                    </div>
                                    <div id="adminFeesTable">
                                        <x-admin-fee.admin-fees-table :adminFees="$lawCase->adminFees()" />
                                    </div>

                                </div>
                                <!-- Hearings Tab -->
                                <div class="tab-pane fade" id="hearingContent" role="tabpanel"
                                    aria-labelledby="hearingTab">
                                    <div class="alert alert-success fade show" id="hearingAlert"></div>
                                    <div class="d-flex justify-content-end align-items-start mb-3">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addHearingModal">
                                            Add Hearing
                                        </button>
                                    </div>
                                    <div id="hearingsTable">
                                        <x-hearing.hearings-table :hearings="$lawCase->hearings()" />
                                    </div>

                                </div>
                                <!-- Billings Tab -->
                                <div class="tab-pane fade" id="billingContent" role="tabpanel"
                                    aria-labelledby="billingTab">
                                    <div class="alert alert-success fade show" id="billingAlert"></div>
                                    <div class="d-flex justify-content-end align-items-start mb-3">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addBillingModal">
                                            Add Billing
                                        </button>
                                    </div>
                                    <div id="billingsTable">
                                        <x-billing.billings-table :billings="$lawCase->billings()" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-admin-deposit.add-admin-deposit-modal :lawCase="$lawCase" :paymentTypes="$paymentTypes" />
    <x-admin-deposit.edit-admin-deposit-modal :paymentTypes="$paymentTypes" />
    <x-admin-deposit.delete-admin-deposit-modal />


    <x-admin-fee.add-admin-fee-modal :adminFeeCategories="$adminFeeCategories" :lawCaseId="$lawCase->id" />
    <x-admin-fee.edit-admin-fee-modal :adminFeeCategories="$adminFeeCategories" />
    <x-admin-fee.delete-admin-fee-modal />

    <x-hearing.add-hearing-modal :lawCase="$lawCase" />
    <x-hearing.edit-hearing-modal />
    <x-hearing.delete-hearing-modal />
    <x-get-branches-modal />

    <x-billing.add-billing-modal :lawCase="$lawCase" />
    <x-billing.edit-billing-modal />
    <x-billing.delete-billing-modal />

    <x-billing-deposit.view-billing-deposits-modal />
    <x-billing-deposit.add-billing-deposit-modal :paymentTypes="$paymentTypes" />
    <x-billing-deposit.edit-billing-deposit-modal :paymentTypes="$paymentTypes" />
    <x-billing-deposit.delete-billing-deposit-modal />


    <x-change-status-modal />
    <x-get-clients-modal />
    <x-get-associates-modal />

    <script>
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
                clientFullname = $span.data('client-full-name')
                $span.hide();
                $(`<div id="clientInfo" class="info edit client">
                    <input type="hidden" name="client_id" id="clientIdInp" value="${clientId}">
                    <div id="clientId" class="id">${clientId}</div>
                    <div id="clientName" class="name">${clientFullname}</div>
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
                associateFullName = $span.data('associate-full-name')
                $span.hide();
                $(`<div id="associateInfo" class="info associate edit">
                     <input  type="hidden" name="associate_id"
                            id="associateIdInp" value="${associateId}" >
                            <div id="associateId" class="id">${associateId}</div>
                            <div id="associateName" class="name">${associateFullName}</div>
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
                    newFullName = $span.next('.info').find('.name').text();
                    $span.data('client-id', newId).data('client-full-name', newFullName);
                    $span.text(window.strHeadline(newFullName))
                }
                $span.show();
                $('.client.edit').remove()
            })
            $('.editable-associate span.value').each(function() {
                $span = $(this);
                if (success) {
                    newId = $span.next('.info').find('.id').text();
                    newFullName = $span.next('.info').find('.name').text();
                    $span.data('associate-id', newId).data('associate-full-name', newFullName);
                    $span.text(window.strHeadline(newFullName))
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
            $('#clientName').text(client.first_name + ' ' + client.last_name + (client.suffix ? ' ' + client.suffix : ''))
        }

        $(document).on('click', '#clientClear', function(e) {
            $('#clientIdInp').val('')
            $('#clientId').text('')
            $('#clientName').text('')
        });

        function selectAssociate(associate) {
            $('#associateIdInp').val(associate.id)
            $('#associateId').text(associate.id)
            $('#associateName').text(associate.first_name + ' ' + associate.last_name + (associate.suffix ? ' ' + associate
                .suffix :
                ''))
        }
        $(document).on('click', '#associateClear', function(e) {
            $('#associateIdInp').val('')
            $('#associateId').text('')
            $('#associateName').text('')
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
        });
    </script>
@endsection
