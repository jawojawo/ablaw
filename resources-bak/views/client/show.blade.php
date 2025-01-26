@extends('layout.app')
@section('title')
    client
@endsection
@section('main')
    <div class="container my-5">
        <!-- Client Overview -->
        <div class="card mb-4">
            <div class="card-header bg-light  text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 text-bg-light d-inline me-2">
                        Client:
                        <span class="editable-input">
                            <span class="value" data-name="client_fullname" data-value-first-name="{{ $client->first_name }}"
                                data-value-last-name="{{ $client->last_name }}"
                                data-value-suffix-name="{{ $client->suffix }}" data-class="w-init">
                                {{ $client->fullname }}
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
                                    <span class="text-muted me-2">{{ $client->id }}</span>
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
                    <div class="col-lg-4">
                        <h6 class="text-primary fw-semibold">Email</h6>
                        <p class="mb-0 fs-6 text-dark">
                            {{ $client->email }}
                        </p>
                    </div>
                    <div class="col-lg-4">
                        <h6 class="text-primary fw-semibold">Phone</h6>

                        <p class="mb-0 fs-6 text-dark">
                            <span class="text-muted">+63</span> {{ $client->phone }}
                        </p>
                    </div>
                </div>

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
                        <x-case.cases-table :lawCases="$client->lawCases()" />
                    </div>
                </div>
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
                <ul class="nav nav-tabs " id="lawCaseTabs" role="tablist">
                    <li class="nav-item ps-2" role="presentation">
                        <a class="nav-link active" href="#admin-deposits" id="adminFeeDepositTab" data-bs-toggle="tab"
                            data-bs-target="#adminFeeDepositContent" type="button" role="tab"
                            aria-controls="adminFeeDepositContent" aria-selected="true">
                            Admin Deposits
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#admin-fees" id="adminFeeTab" data-bs-toggle="tab"
                            data-bs-target="#adminFeeContent" type="button" role="tab" aria-controls="adminFeeContent"
                            aria-selected="false">
                            Admin Fees
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#hearings" id="hearingTab" data-bs-toggle="tab"
                            data-bs-target="#hearingContent" type="button" role="tab" aria-controls="hearingContent"
                            aria-selected="false">
                            Hearings
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#billings" id="billingTab" data-bs-toggle="tab"
                            data-bs-target="#billingContent" type="button" role="tab" aria-controls="billingContent"
                            aria-selected="false">
                            Billings
                        </a>
                    </li>
                </ul>
                <div class="card border-0">
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Admin Deposits Tab -->
                            <div class="tab-pane  active" id="adminFeeDepositContent">
                                <div id="adminDepositsTable">
                                    {{-- <x-admin-deposit.admin-deposits-table :adminDeposits="$client->adminDeposits()" /> --}}
                                </div>

                            </div>
                            <!-- Admin Fees Tab -->
                            <div class="tab-pane fade " id="adminFeeContent" role="tabpanel"
                                aria-labelledby="adminFeeTab">
                                <div id="adminFeesTable">
                                    {{-- <x-admin-fee.admin-fees-table :adminFees="$client->adminFees()" /> --}}
                                </div>

                            </div>
                            <!-- Hearings Tab -->
                            <div class="tab-pane fade" id="hearingContent" role="tabpanel" aria-labelledby="hearingTab">

                                <div id="hearingsTable">
                                    {{-- <x-hearing.hearings-table :hearings="$client->hearings()" /> --}}
                                </div>

                            </div>
                            <!-- Billings Tab -->
                            <div class="tab-pane fade" id="billingContent" role="tabpanel" aria-labelledby="billingTab">

                                <div id="billingsTable">
                                    {{-- <x-billing.billings-table :billings="$client->billings()" /> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-admin-deposit.edit-admin-deposit-modal :paymentTypes="$paymentTypes" />
    <x-admin-deposit.delete-admin-deposit-modal />

    <x-admin-fee.edit-admin-fee-modal :adminFeeCategories="$adminFeeCategories" />
    <x-admin-fee.delete-admin-fee-modal />

    <x-hearing.edit-hearing-modal />
    <x-hearing.delete-hearing-modal />
    <x-get-branches-modal />

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
                window.fetchAjaxTable("extra-info", "#extraInfo");
                window.fetchAjaxTable('admin-deposits', '#adminDepositsTable', 1, false)
                window.fetchAjaxTable('admin-fees', '#adminFeesTable', 1, false)
                window.fetchAjaxTable('hearings', '#hearingsTable', 1, false)
                window.fetchAjaxTable('billings', '#billingsTable', 1, false)
            }
            toggleRed();
            loadTables();

        });
    </script>
@endsection
