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

        <table class="table table-striped table-bordered with-toggle-column-table table-center" id="casesTableMain">
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
                                            Associate
                                        </div>
                                    </label>
                                </li>
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
                                <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox10" type="checkbox" checked
                                                onchange="toggleColumn(this,10)">
                                            Next Hearing
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox11" type="checkbox" checked
                                                onchange="toggleColumn(this,11)">
                                            Bill
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox12" type="checkbox" checked
                                                onchange="toggleColumn(this,12)">
                                            Status
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox13" type="checkbox" checked
                                                onchange="toggleColumn(this,13)">
                                            By
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <label class="form-check-label dropdown-item">
                                        <div class="form-check ">
                                            <input class="form-check-input toggle-column-check"
                                                id="casesTableMainColumnCheckBox14" type="checkbox" checked
                                                onchange="toggleColumn(this,14)">
                                            Last Updated
                                        </div>
                                    </label>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <a href="{{ route('case.create') }}" class="btn btn-success ">Create New Case</a>
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
                                                        <input type="checkbox" class="btn-check"
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
                                                <div class="btn-group radio-group party_role-group w-100" role="group"
                                                    aria-label="Basic radio toggle button group">
                                                    @foreach (config('enums.party_roles') as $party_role)
                                                        <input type="checkbox" class="btn-check"
                                                            value="{{ $party_role }}" name="party_role[]"
                                                            id="btn{{ $party_role }}Search"
                                                            @if (is_array(request()->get('party_role')) && in_array($party_role, request()->get('party_role'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-primary text-capitalize"
                                                            for="btn{{ $party_role }}Search">{{ $party_role }}</label>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label d-block">Opposing Party</label>
                                                <textarea name="opposing_party" rows="2" class="form-control">{{ request()->get('opposing_party') }}</textarea>
                                            </div>
                                            <div class="">
                                                <label class="form-label d-block">Status</label>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <span>
                                                        <input type="checkbox" class="btn-check case-status"
                                                            name="status[]" value="open" id="openSearchBadge"
                                                            @if (is_array(request()->get('status')) && in_array('open', request()->get('status'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-primary text-nowrap"
                                                            for="openSearchBadge">Open</label>
                                                    </span>
                                                    <span>
                                                        <input type="checkbox" class="btn-check case-status"
                                                            name="status[]" value="in_progress"
                                                            @if (is_array(request()->get('status')) && in_array('in_progress', request()->get('status'))) checked @endif
                                                            id="in_progressSearchBadge" autocomplete="off">
                                                        <label class="btn btn-outline-info text-nowrap"
                                                            for="in_progressSearchBadge">In Progress</label>
                                                    </span>
                                                    <span>
                                                        <input type="checkbox" class="btn-check case-status"
                                                            name="status[]" value="settled" id="settledSearchBadge"
                                                            @if (is_array(request()->get('status')) && in_array('settled', request()->get('status'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-warning text-nowrap"
                                                            for="settledSearchBadge">Settled</label>
                                                    </span>
                                                    <span>
                                                        <input type="checkbox" class="btn-check case-status"
                                                            name="status[]" value="won" id="wonSearchBadge"
                                                            @if (is_array(request()->get('status')) && in_array('won', request()->get('status'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-success text-nowrap"
                                                            for="wonSearchBadge">Won</label>
                                                    </span>
                                                    <span>
                                                        <input type="checkbox" class="btn-check case-status"
                                                            name="status[]" value="lost" id="lostSearchBadge"
                                                            @if (is_array(request()->get('status')) && in_array('lost', request()->get('status'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-danger text-nowrap"
                                                            for="lostSearchBadge">Lost</label>
                                                    </span>
                                                    <span>
                                                        <input type="checkbox" class="btn-check case-status"
                                                            name="status[]" value="closed" id="closedSearchBadge"
                                                            @if (is_array(request()->get('status')) && in_array('closed', request()->get('status'))) checked @endif
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-secondary text-nowrap"
                                                            for="closedSearchBadge">Closed</label>
                                                    </span>

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
                        <th>
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
                        <th>
                            <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-init"
                                data-bs-toggle="popover" data-bs-title="Party Role"
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
                        <th>
                            Last updated
                        </th>
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
                            <td>{!! $case->case_type_badge !!}</td>
                            <td>{{ $case->client_fullname }}
                            </td>
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
                            {{-- @if ($case->unpaid_bill)
                                <td class= "{{ $case->unpaid_bill->due_date_table_class }}">
                                    ₱{{ number_format($case->unpaid_bill->amount, 2) }}
                                </td>
                            @else
                                <td class="text-muted">None</td>
                            @endif --}}
                            <td>{{ $case->unpaid_bill }}</td>
                            <td class='text-center'>
                                <span id='statusCon{{ $case->id }}'>
                                    {!! $case->status_badge !!}
                                </span>
                            </td>
                            <td>
                                {{ $case->user->name }}
                            </td>
                            <td class="fs-6">
                                {{ $case->updated_at->diffForHumans() }}
                            </td>
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
