@props(['lawCases', 'typeId', 'type'])
@php
    $lawCases = $lawCases
        ->withCount(['billings', 'contacts', 'notes', 'overDueBills', 'upcomingBills', 'dueTodayBills', 'paidBills'])
        ->with(['associate'])
        ->get();
@endphp
<div class="accordion">
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button cases position-relative" type="button" data-bs-toggle="collapse"
                data-bs-target="#caseAccord">
                Cases
                <span
                    class="red-toggle position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                </span>
            </button>
        </div>
        <div id="caseAccord" class="accordion-collapse collapse ">
            <div class="accordion-body p-0">
                <table class="table table-bordered  mb-0">
                    @can('create', getPermissionClass('Cases'))
                        <caption class="text-end pe-2  caption-top"> <a
                                href="{{ route('case.create') }}?{{ $type }}_id={{ $typeId }}"
                                class="btn btn-success ">Add
                                Case</a></caption>
                    @endcan
                    @if ($lawCases->isEmpty())
                        <caption class="text-center p-4">No Cases Found</caption>
                    @else
                        <thead>
                            <tr class="text-uppercase">
                                <th class="table-td-min text-center"></th>
                                <th class="table-td-min text-center">#</th>
                                <th>case number</th>
                                {{-- <th>title</th> --}}
                                <th>
                                    <a tabindex="0" data-bs-custom-class="custom-popover"
                                        class="popover-click text-init" data-bs-toggle="popover"
                                        data-bs-title="Case Type"
                                        data-bs-content="
                                                <table class='table table-sm table-borderless'>
                                                    <tr>
                                                        <td>Litigation</td>
                                                        <td><span class='badge  t-success m-1'>L</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Non Litigation</td>
                                                        <td><span class='badge  t-danger m-1'>N</span></td>
                                                    </tr>
                                                </table>
                                            ">
                                        type
                                    </a>
                                </th>

                                <th>
                                    <a tabindex="0" data-bs-custom-class="custom-popover"
                                        class="popover-click text-init" data-bs-toggle="popover"
                                        data-bs-title="Party Role"
                                        data-bs-content="
                                            <table class='table table-sm table-borderless'>
                                                <tr>
                                                    <td>Petitioner</td>
                                                    <td><span class='badge  t-warning m-1'>P</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Respondent</td>
                                                    <td><span class='badge  t-info m-1'>R</span></td>
                                                </tr>
                                            </table>
                                        ">
                                        Role
                                    </a>

                                </th>
                                <th>opposing party</th>
                                <th class="text-center"><i class="bi bi-phone"></i></th>
                                <th>associate</th>
                                @if (auth()->user()->can('view', getPermissionClass('Case Deposits')) &&
                                        auth()->user()->can('view', getPermissionClass('Case Expenses')))
                                    <th>
                                        <a tabindex="0" data-bs-custom-class="custom-popover"
                                            class="popover-click text-init" data-bs-toggle="popover"
                                            data-bs-title="Deposit"
                                            data-bs-content="
                                                    <span class='badge rounded-pill t-success m-1'>Surplus Balance</span>
                                                    <span class='badge rounded-pill t-secondary  m-1'>0 Balance</span>
                                                    <span class='badge rounded-pill t-danger  m-1'>Deficit Balance</span>
                                                ">
                                            deposit
                                        </a>
                                    </th>
                                @endif
                                @can('view', getPermissionClass('Case Hearings'))
                                    <th>

                                        <div>hearing</div>
                                    </th>
                                @endcan
                                @can('view', getPermissionClass('Case Billings'))
                                    <th class="text-center">
                                        bill
                                    </th>
                                @endcan
                                <th class="text-center">
                                    Status
                                </th>
                                <th class="text-center"><i class="bi bi-journals"></i></th>
                                {{-- <th>
                                    By
                                </th> --}}


                            </tr>
                            </tr>
                        </thead>
                        @php
                            $excludedCaseIds = request()->get('excluded_case_ids', []);
                        @endphp
                        @foreach ($lawCases as $case)
                            <tr>
                                <td>
                                    <label class="bg-primary">
                                        <input type="checkbox" name="uncheck_cases" value="{{ $case->id }}"
                                            {{ in_array($case['id'], $excludedCaseIds) ? '' : 'checked' }}
                                            autocomplete="off">
                                    </label>

                                </td>

                                <td><a class="text-reset text-center" href="{{ route('case.show', $case->id) }}">
                                        <div>{{ $case->id }}</div>
                                    </a></td>
                                <td class="text-nowrap">
                                    <a tabindex="0" data-bs-custom-class="custom-popover"
                                        class="popover-click text-muted" data-bs-toggle="popover"
                                        data-bs-title="<div class='text-center'>{{ $case->case_number }}</div>"
                                        data-bs-content="<div class='fst-italic'>{{ $case->case_title }}</div>">
                                        {{ $case->case_number }}
                                    </a>

                                </td>

                                {{-- <td>{{ $case->case_title }}</td> --}}
                                <td>{!! $case->case_type_badge !!}</td>

                                <td>{!! $case->case_party_role_badge !!}</td>
                                <td>{{ $case->opposing_party }}</td>
                                <td class="text-center">

                                    @if ($case->contacts_count > 0)
                                        <button class="btn-primary btn p-0 px-2" data-bs-toggle="modal"
                                            data-bs-target="#showContactsModal" data-contacts='{{ $case->contacts }}'>
                                            {{ $case->contacts_count }}
                                        </button>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif

                                </td>
                                <td>{{ $case->associate->name }}
                                </td>
                                @if (auth()->user()->can('view', getPermissionClass('Case Deposits')) &&
                                        auth()->user()->can('view', getPermissionClass('Case Expenses')))
                                    <td class="{{ $case->remaining_deposit_table_class }}">

                                        <a tabindex="0" data-bs-custom-class="custom-popover-wo-padding"
                                            class="popover-click text-muted" data-bs-toggle="popover"
                                            data-bs-title="<div class='text-center'>Deposit</div>"
                                            data-bs-content = "<table class='table  table-borderless table-sm mt-2 mb-0 '>
                                              
                                                <tr>
                                                    <td class='pe-3 text-nowrap ps-3'> Deposits:</td>
                                                    <td class='text-success pe-3'>₱{{ number_format($case->total_deposits, 2) }}</td>
                                                </tr>
                                                <tr >
                                                    <td class='text-nowrap ps-3'> Fees:</td>
                                                    <td class='text-danger pe-3'>₱{{ number_format($case->total_fees, 2) }}</td>
                                                </tr>
                                               
                                                <tr class='{{ $case->remaining_deposit_table_class }}'>
                                                    <td class='ps-3'>Total:</td>
                                                    <td class='pe-3'>₱{{ $case->remaining_deposit }}</td>
                                                </tr>
                                           
                                            </table>">
                                            ₱{{ $case->remaining_deposit }} </a>

                                    </td>
                                @endif
                                @can('view', getPermissionClass('Case Hearings'))
                                    <td>


                                        {!! $case->formatted_next_hearing !!}

                                    </td>
                                @endcan
                                @can('view', getPermissionClass('Case Billings'))
                                    <td class="text-center position-relative">
                                        <div class="badge-con">
                                            @if ($case->over_due_bills_count)
                                                <div class="bg-danger"></div>
                                            @endif
                                            @if ($case->due_today_bills_count)
                                                <div class="bg-primary"></div>
                                            @endif
                                            @if ($case->upcoming_bills_count)
                                                <div class="border border-dark"></div>
                                            @endif
                                        </div>
                                        @if ($case->billings_count)
                                            <a tabindex="0" data-bs-custom-class="custom-popover-wo-padding"
                                                class="popover-click text-reset" data-bs-toggle="popover"
                                                data-bs-title="<div class='text-center'>Bills</div>"
                                                data-bs-content="
                                    <div class='p-2'>
                                        @if ($case->over_due_bills_count)
<div>
<span class='badge rounded-pill text-bg-danger  m-1'>
                                                <span class='badge text-bg-light'>{{ $case->over_due_bills_count }}</span>
                                                Over Due
                                            </span>
                                        </div>
@endif
                                        @if ($case->due_today_bills_count)
<div>
<span class='badge rounded-pill text-bg-primary  m-1'>
                                                <span class='badge text-bg-light'>{{ $case->due_today_bills_count }}</span>
                                                Due Today
                                            </span>
                                        </div>
@endif
                                        @if ($case->upcomming_bills_count)
<div>
<span class='badge rounded-pill text-bg-light btn btn-outline-dark m-1' style='cursor:auto'>
                                                <span class='badge text-bg-primary'>{{ $case->upcomming_bills_count }}</span>
                                                Upcomming
                                            </span>
                                        </div>
@endif
                                        @if ($case->paid_bills_count)
<div>
<span class='badge rounded-pill text-bg-success m-1' style='cursor:auto'>
                                                <span class='badge text-bg-light'>{{ $case->paid_bills_count }}</span>
                                                Paid
                                            </span>
                                        </div>
@endif
                                    </div>
                                    ">
                                                {{ $case->billings_count }}
                                            </a>
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                        {{-- {{ $case->unpaid_bill }} --}}
                                    </td>
                                @endcan
                                <td class='text-center'>
                                    <span id='statusCon{{ $case->id }}'>
                                        {!! $case->status_badge !!}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn-outline-dark btn p-0 px-2"
                                        id="case{{ $case->id }}NotesCount" data-bs-toggle="modal"
                                        data-bs-target="#showNotesModal"
                                        data-route='{{ route('note', ['case', $case->id]) }}'>
                                        {{ $case->notes_count }}
                                    </button>

                                </td>
                                {{-- <td>
                                    {{ $case->user->name }}
                                </td> --}}

                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
