@extends('layout.app')
@section('title')
    case
@endsection
@section('main')
    <div class="container h-100 py-4" style="max-width: 1500px">

        {{-- <div class="d-flex justify-content-between ">
            <h5 class="my-5 ">Cases</h5>
            <a href="{{ route('case.create') }}" class="btn btn-success my-5"><i class="bi bi-plus"></i> Add Case</a>
        </div> --}}

        <table class="table  table-bordered with-toggle-column-table " id="casesTableMain">
            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex">
                        <h2 class="me-2">Cases</h2>
                        <div class="align-self-center">
                            <button type="button" class="btn  btn-outline-secondary position-relative"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="bi bi-filter"></i>
                                <span
                                    class="red-toggle position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <h6 class="dropdown-header">Toggle Column</h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox3" type="checkbox" checked
                                                onchange="toggleColumn(this,3)">
                                            Title
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox4" type="checkbox" checked
                                                onchange="toggleColumn(this,4)">
                                            Type
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox5" type="checkbox" checked
                                                onchange="toggleColumn(this,5)">
                                            Client
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox6" type="checkbox" checked
                                                onchange="toggleColumn(this,6)">
                                            Role
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox7" type="checkbox" checked
                                                onchange="toggleColumn(this,7)">
                                            Opposing Party
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox8" type="checkbox" checked
                                                onchange="toggleColumn(this,8)">
                                            Associates
                                        </div>
                                    </label>
                                </li>
                                @if (auth()->user()->can('view', getPermissionClass('Case Deposits')) &&
                                        auth()->user()->can('view', getPermissionClass('Case Expenses')))
                                    <li>
                                        <label class="form-check-label dropdown-item">
                                            <div class="form-check ">
                                                <input class="form-check-input toggle-column-check"
                                                    id="casesTableMainColumnCheckBox9" type="checkbox" checked
                                                    onchange="toggleColumn(this,9)">
                                                Remaining Deposit
                                            </div>
                                        </label>
                                    </li>
                                @endif
                                {{-- <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox10" type="checkbox" checked
                                                onchange="toggleColumn(this,10)">
                                            Next Hearing
                                        </div>
                                    </label>
                                </li> --}}
                                @can('view', getPermissionClass('Case Billings'))
                                    <li>
                                        <label class="form-check-label dropdown-item">
                                            <div class="form-check ">
                                                <input class="form-check-input toggle-column-check"
                                                    id="casesTableMainColumnCheckBox11" type="checkbox" checked
                                                    onchange="toggleColumn(this,10)">
                                                Bill
                                            </div>
                                        </label>
                                    </li>
                                @endcan
                                <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox12" type="checkbox" checked
                                                onchange="toggleColumn(this,11)">
                                            Status
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox13" type="checkbox" checked
                                                onchange="toggleColumn(this,12)">
                                            <i class="bi bi-journals"></i> Notes
                                        </div>
                                    </label>
                                </li>
                                {{-- <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox14" type="checkbox" checked
                                                onchange="toggleColumn(this,13)">
                                            Updated
                                        </div>
                                    </label>
                                </li> --}}

                            </ul>
                        </div>
                    </div>
                    @can('create', getPermissionClass('Cases'))
                        <a href="{{ route('case.create') }}" class="btn btn-success ">Create New Case</a>
                    @endcan
                </div>
            </caption>

            {{-- <caption class="caption-top">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center bg-info text-white">
                            <div class="card-body">
                                <h5>Extra Info</h5>
                                <p class="display-6">test</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-warning text-white">
                            <div class="card-body">
                                <h5>Extra Info</h5>
                                <p class="display-6">test</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-success text-white">
                            <div class="card-body">
                                <h5>Extra Info</h5>
                                <p class="display-6">test</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-danger text-white">
                            <div class="card-body">
                                <h5>Extra Info</h5>
                                <p class="display-6">test</p>
                            </div>
                        </div>
                    </div>
                </div>
            </caption> --}}
            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        @if (request()->get('search') ||
                                request()->get('case_type') ||
                                request()->get('party_role') ||
                                request()->get('opposing_party') ||
                                request()->get('bills') ||
                                request()->get('status'))
                            <a href="{{ route('case') }}" class="btn btn-link text-danger p-0">
                                Clear Search
                            </a>
                        @endif
                    </div>
                    <div class=" w-50 ">
                        <form class="mb-0">
                            <div class="input-group dropstart">
                                <input type="search" class="form-control" placeholder="Case Number / Case Title"
                                    aria-label="Text input with dropdown button" name="search"
                                    value="{{ request()->get('search') }}">
                                <button class="btn btn-outline-secondary dropdown-toggle dropstart z-3" type="button"
                                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    @if (request()->get('search') ||
                                            request()->get('case_type') ||
                                            request()->get('party_role') ||
                                            request()->get('opposing_party') ||
                                            request()->get('bills') ||
                                            request()->get('status'))
                                        <span
                                            class="red-toggle position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                        </span>
                                    @endif
                                </button>
                                <div class="dropdown-menu p-0 " style="width:350px">
                                    <div class="card border-0">
                                        <div class="card-header text-bg-primary">
                                            Advance Search
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label d-block">Case Type</label>
                                                <div class="btn-group radio-group case_type-group w-100" role="group"
                                                    aria-label="Basic radio toggle button group">
                                                    @foreach (config('enums.case_types') as $case_type)
                                                        <input type="checkbox" class="btn-check reverse"
                                                            value="{{ $case_type }}" name="case_type[]"
                                                            id="btn{{ $case_type }}Search"
                                                            @if (is_array(request()->get('case_type')) && in_array($case_type, request()->get('case_type'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-primary text-capitalize"
                                                            for="btn{{ $case_type }}Search">{{ Str::headline($case_type) }}</label>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label d-block">Role</label>
                                                <div class="d-flex flex-wrap gap-2 justify-content-between" role="group"
                                                    aria-label="Basic radio toggle button group">
                                                    @foreach (config('enums.party_roles') as $party_role)
                                                        <input type="checkbox" class="btn-check reverse"
                                                            value="{{ $party_role }}" name="party_role[]"
                                                            id="btn{{ $party_role }}Search"
                                                            @if (is_array(request()->get('party_role')) && in_array($party_role, request()->get('party_role'))) checked @endif
                                                            autocomplete="off">
                                                        <label
                                                            class="btn btn-outline{{ substr(config('enums.party_role_colors.' . $party_role), 1) }} text-capitalize"
                                                            for="btn{{ $party_role }}Search">{{ $party_role }}</label>
                                                        {{-- <label class="btn btn-outline-primary text-capitalize"
                                                            for="btn{{ $party_role }}Search">{{ $party_role }}</label> --}}
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label d-block">Opposing Party</label>
                                                <textarea name="opposing_party" rows="2" class="form-control">{{ request()->get('opposing_party') }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Bills</label>
                                                <div>
                                                    <div class="btn-group mb-1 w-100" role="group">
                                                        <input type="checkbox" name="bills[]" value="overDue"
                                                            class="btn-check reverse" id="btn-over-due-outlined"
                                                            @if (is_array(request()->get('bills')) && in_array('overDue', request()->get('bills'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-danger w-50"
                                                            for="btn-over-due-outlined">Over
                                                            Due</label>
                                                        <input type="checkbox" name="bills[]" value="dueToday"
                                                            class="btn-check reverse" id="btn-due-today-outlined"
                                                            @if (is_array(request()->get('bills')) && in_array('dueToday', request()->get('bills'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-primary w-50"
                                                            for="btn-due-today-outlined">Due
                                                            Today</label>
                                                    </div>
                                                    <div class="btn-group w-100" role="group">
                                                        <input type="checkbox" name="bills[]" value="upcoming"
                                                            class="btn-check reverse" id="btn-upcoming-outlined"
                                                            @if (is_array(request()->get('bills')) && in_array('upcoming', request()->get('bills'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-dark w-50"
                                                            for="btn-upcoming-outlined">Upcoming</label>
                                                        <input type="checkbox" name="bills[]" value="paid"
                                                            class="btn-check reverse" id="btn-paid-outlined"
                                                            @if (is_array(request()->get('bills')) && in_array('paid', request()->get('bills'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-success w-50"
                                                            for="btn-paid-outlined">Paid</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                <label class="form-label d-block">Status</label>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <span>
                                                        <input type="checkbox" class="btn-check case-status reverse"
                                                            name="status[]" value="open" id="openSearchBadge"
                                                            @if (is_array(request()->get('status')) && in_array('open', request()->get('status'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-primary text-nowrap"
                                                            for="openSearchBadge">Active</label>
                                                    </span>
                                                    {{-- <span>
                                                        <input type="checkbox" class="btn-check case-status reverse"
                                                            name="status[]" value="in_progress"
                                                            @if (is_array(request()->get('status')) && in_array('in_progress', request()->get('status'))) checked @endif
                                                            id="in_progressSearchBadge" autocomplete="off">
                                                        <label class="btn btn-outline-info text-nowrap"
                                                            for="in_progressSearchBadge">In Progress</label>
                                                    </span> --}}
                                                    <span>
                                                        <input type="checkbox" class="btn-check case-status reverse"
                                                            name="status[]" value="settled" id="settledSearchBadge"
                                                            @if (is_array(request()->get('status')) && in_array('settled', request()->get('status'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-warning text-nowrap"
                                                            for="settledSearchBadge">Withdrawn</label>
                                                    </span>
                                                    <span>
                                                        <input type="checkbox" class="btn-check case-status reverse"
                                                            name="status[]" value="won" id="wonSearchBadge"
                                                            @if (is_array(request()->get('status')) && in_array('won', request()->get('status'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-success text-nowrap"
                                                            for="wonSearchBadge">Won</label>
                                                    </span>
                                                    <span>
                                                        <input type="checkbox" class="btn-check case-status reverse"
                                                            name="status[]" value="lost" id="lostSearchBadge"
                                                            @if (is_array(request()->get('status')) && in_array('lost', request()->get('status'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-danger text-nowrap"
                                                            for="lostSearchBadge">Lost</label>
                                                    </span>
                                                    {{-- <span>
                                                        <input type="checkbox" class="btn-check  case-status reverse"
                                                            name="status[]" value="appeal" id="appealRadtioButton"
                                                            @if (is_array(request()->get('status')) && in_array('appeal', request()->get('status'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-secondary  text-nowrap"
                                                            for="appealRadtioButton">Appeal</label>
                                                    </span> --}}
                                                    <span>
                                                        <input type="checkbox" class="btn-check  case-status reverse"
                                                            name="status[]" value="archived" id="archivedRadioButton"
                                                            @if (is_array(request()->get('status')) && in_array('archived', request()->get('status'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-secondary  text-nowrap"
                                                            for="archivedRadioButton">Archived</label>
                                                    </span>
                                                    {{-- <span>
                                                        <input type="checkbox" class="btn-check  case-status"
                                                            name="status[]" value="archived" id="archivedRadioButton"
                                                            autocomplete="off">
                                                        <label class="checkbox btn-outline-secondary  text-nowrap"
                                                            for="archivedRadioButton">Archived</label>
                                                    </span> --}}

                                                    {{-- <span>
                                                        <input type="checkbox" class="btn-check case-status reverse"
                                                            name="status[]" value="closed" id="closedSearchBadge"
                                                            @if (is_array(request()->get('status')) && in_array('closed', request()->get('status'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-secondary text-nowrap"
                                                            for="closedSearchBadge">Closed</label>
                                                    </span> --}}

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit" aria-expanded="false"><i
                                        class="bi bi-search"></i> Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </caption>
            @if ($cases->isEmpty())
                <caption class="caption-top">
                    <div class="text-center fs-3 card p-5">No Cases Found</div>
                </caption>
            @else
                <caption>
                    {{ $cases->appends(request()->query())->links() }}
                </caption>
                <thead class="text-uppercase">
                    <tr class="align-middle">
                        <th class="table-td-min text-center">#</th>
                        <th>case number</th>
                        <th>title</th>
                        <th class="text-center">
                            <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-init"
                                data-bs-toggle="popover" data-bs-title="Case Type"
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
                        <th>client</th>
                        <th class="text-center">
                            <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-init"
                                data-bs-toggle="popover" data-bs-title="Party Role"
                                data-bs-content="
                            <table class='table table-sm table-borderless'>
                                @foreach (config('enums.party_roles') as $party_role)
<tr>
                                        <td>{{ "<span class='badge  " . config('enums.party_role_colors.' . $party_role) . " m-1'>" . $party_role . '</span>' }}</td>
                                      
                                    </tr>
@endforeach
                                {{-- <tr>
                                    <td>Petitioner</td>
                                    <td><span class='badge  t-warning m-1'>P</span></td>
                                </tr>
                                <tr>
                                    <td>Respondent</td>
                                    <td><span class='badge  t-info m-1'>R</span></td>
                                </tr> --}}
                            </table>
                        ">
                                Role
                            </a>

                        </th>
                        <th>opposing party</th>
                        <th>associates</th>
                        @if (auth()->user()->can('view', getPermissionClass('Case Deposits')) &&
                                auth()->user()->can('view', getPermissionClass('Case Expenses')))
                            <th>
                                <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-init"
                                    data-bs-toggle="popover" data-bs-title="Deposit"
                                    data-bs-content="
                                    <span class='badge rounded-pill t-success m-1'>Surplus Balance</span>
                                    <span class='badge rounded-pill t-secondary  m-1'>0 Balance</span>
                                    <span class='badge rounded-pill t-danger  m-1'>Deficit Balance</span>
                                ">
                                    deposit
                                </a>
                            </th>
                        @else
                            <th class="p-0"></th>
                        @endif
                        {{-- <th>

                            <div>hearing</div>
                        </th> --}}
                        @can('view', getPermissionClass('Case Billings'))
                            <th class="text-center">
                                <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-init"
                                    data-bs-toggle="popover" data-bs-title="Bills"
                                    data-bs-content="
                                <div class='d-flex'>
                                    <div class='bg-danger rounded-circle' style='width:15px;height:15px'></div>
                                    <span class='ps-2'>Over Due</span>
                                </div>
                                <div class='d-flex'>
                                    <div class='bg-primary rounded-circle' style='width:15px;height:15px'></div>
                                    <span class='ps-2'>Due Today</span>
                                </div>
                                 <div class='d-flex'>
                                    <div class='border border-dark rounded-circle' style='width:15px;height:15px'></div>
                                    <span class='ps-2'>Upcoming</span>
                                </div>
                            ">
                                    bills
                                </a>
                            </th>
                        @else
                            <th class="p-0"></th>
                        @endcan
                        <th class="text-center">
                            Status
                        </th>
                        <th class="text-center">
                            <i class="bi bi-journals"></i>
                        </th>
                        {{-- <th>
                            updated
                        </th> --}}
                        <th class="table-td-min"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cases as $case)
                        <tr>
                            <td><a class="text-reset text-center" href="{{ route('case.show', $case->id) }}">
                                    <div>{{ $case->id }}</div>
                                </a></td>
                            <td class="text-nowrap">
                                <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-muted"
                                    data-bs-toggle="popover"
                                    data-bs-title="<div class='text-center'>{{ $case->case_number }}</div>"
                                    data-bs-content="<div class='fst-italic'>{{ $case->case_title }}</div>">
                                    {{ $case->case_number }}
                                </a>

                            </td>

                            <td>{{ $case->case_title }}</td>
                            <td class="text-center">{!! $case->case_type_badge !!}</td>
                            <td>{{ $case->client->name }}
                            </td>
                            <td class="text-center">{!! $case->case_party_role_badge !!}</td>
                            <td>{{ $case->opposing_party }}</td>
                            <td>
                                <ul class="list-group">
                                    @foreach ($case->associates as $associate)
                                        <li class="list-group-item">{{ $associate->name }}</li>
                                    @endforeach
                                </ul>
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
                            @else
                                <td class="p-0"></td>
                            @endif
                            {{-- <td>
                                {!! $case->formatted_next_hearing !!}
                            </td> --}}
                            {{-- @if ($case->unpaid_bill)
                                <td class= "{{ $case->unpaid_bill->due_date_table_class }}">
                                    ₱{{ number_format($case->unpaid_bill->amount, 2) }}
                                </td>
                            @else
                                <td class="text-muted">None</td>
                            @endif --}}
                            @can('view', getPermissionClass('Case Billings'))
                                <td class="position-relative text-center">
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
                                    @if ($case->upcoming_bills_count)
<div>
<span class='badge rounded-pill text-bg-light btn btn-outline-dark m-1' style='cursor:auto'>
                                            <span class='badge text-bg-primary'>{{ $case->upcoming_bills_count }}</span>
                                            Upcoming
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
                                </td>
                            @else
                                <td class='p-0'></td>
                            @endcan
                            <td class='text-center'>
                                <span id='statusCon{{ $case->id }}'>
                                    {!! $case->status_badge !!}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class=" btn-outline-dark btn p-0 px-2" id="case{{ $case->id }}NotesCount"
                                    data-bs-toggle="modal" data-bs-target="#showNotesModal"
                                    data-route='{{ route('note', ['case', $case->id]) }}'>
                                    {{ $case->notes_count }}
                                </button>

                            </td>
                            {{-- <td class="fs-6">
                                <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-muted"
                                    data-bs-toggle="popover"
                                    data-bs-content="{{ formattedDateTime($case->updated_at) }}">
                                    {{ $case->updated_at->diffForHumans() }}
                                </a>

                            </td> --}}
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm"
                                        data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu shadow">
                                        <li>
                                            <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                                <span class="text-muted me-2">#{{ $case->id }}</span>
                                                <span>{{ $case->case_number }}</span>
                                            </h6>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('case.show', $case->id) }}">
                                                <i class="bi bi-eye me-2"></i>View
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a>
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
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            @endif
        </table>

    </div>

@endsection
