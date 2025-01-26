@props(['lawCases'])
@php
    $lawCases = $lawCases->with(['user', 'associate'])->get();
@endphp
<div class="accordion" id="accordionExample">
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
            <div class="accordion-body">
                <table class="table table-striped table-bordered table-center mb-0">
                    @if ($lawCases->isEmpty())
                        <caption>No Cases Found</caption>
                    @else
                        <thead>
                            <tr class="text-uppercase">
                                <th class="table-td-min text-center"></th>
                                <th class="table-td-min text-center">#</th>
                                <th>case number</th>
                                <th>title</th>
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
                                <th>associate</th>
                                <th>
                                    <a tabindex="0" data-bs-custom-class="custom-popover"
                                        class="popover-click text-init" data-bs-toggle="popover" data-bs-title="Deposit"
                                        data-bs-content="
                                                    <span class='badge rounded-pill t-success m-1'>Surplus Balance</span>
                                                    <span class='badge rounded-pill t-secondary  m-1'>0 Balance</span>
                                                    <span class='badge rounded-pill t-danger  m-1'>Deficit Balance</span>
                                                ">
                                        deposit
                                    </a>
                                </th>
                                <th>

                                    <div>hearing</div>
                                </th>
                                <th>
                                    bill
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    By
                                </th>


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

                                <td>{{ $case->case_title }}</td>
                                <td>{!! $case->case_type_badge !!}</td>

                                <td>{!! $case->case_party_role_badge !!}</td>
                                <td>{{ $case->opposing_party }}</td>
                                <td>{{ $case->associate_fullname }}
                                </td>
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

                                <td>


                                    {!! $case->formatted_next_hearing !!}

                                </td>
                                <td>{{ $case->unpaid_bill }}</td>
                                <td class='text-center'>
                                    <span id='statusCon{{ $case->id }}'>
                                        {!! $case->status_badge !!}
                                    </span>
                                </td>
                                <td>
                                    {{ $case->user->name }}
                                </td>

                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
