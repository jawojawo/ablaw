@props(['pdf' => false])
<table class="table table-bordered">
    <caption class="caption-top">
        <div class="row mb-4">
            <div class=" col-lg-12">
                <div class="card">
                    <div class="card-header text-bg-primary">
                        <h6 class="mb-0">Office Expenses Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-secondary pb-2">
                                    Expenses from <span class="text-primary">{{ formattedDate($minExpenseDate) }}</span>
                                    to
                                    <span class="text-primary">{{ formattedDate($maxExpenseDate) }}</span>
                                </div>
                                <div class=" border-bottom">
                                    <div class="d-flex justify-content-between  my-2 ">
                                        <span># Expenses</span>
                                        @if ($pdf)
                                            <span>{{ $officeExpenses->count() }}</span>
                                        @else
                                            <span>{{ $officeExpenses->total() }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class=" border-bottom">
                                    <div class="d-flex justify-content-between  my-2 ">
                                        <span>Total Amount</span>
                                        <span>{{ formattedMoney($totalAmount) }}</span>
                                    </div>
                                </div>

                                {{-- <div class="border-bottom">
                                    <div class="d-flex justify-content-between align-items-center my-2">
                                        <div>Total Remaining Payables</div>
                                        <div class="d-flex justify-content-end flex-wrap">
                                            {{ formattedMoney($totalOutstandingBalance) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="border-bottom">
                                    <div class="d-flex justify-content-between align-items-center my-2">
                                        <div>{{ $bills->total() }} Bills</div>
                                        <div class="d-flex justify-content-end flex-wrap">
                                            @if ($overDueCount)
                                                <span class="badge rounded-pill text-bg-danger  m-1">
                                                    <span class="badge text-bg-light">{{ $overDueCount }}</span>
                                                    Over Due
                                                </span>
                                            @endif

                                            @if ($dueTodayCount)
                                                <span class="badge rounded-pill text-bg-primary  m-1">
                                                    <span class="badge text-bg-light">{{ $dueTodayCount }}</span>
                                                    Due Today
                                                </span>
                                            @endif
                                            @if ($upcomingCount)
                                                <span
                                                    class="badge rounded-pill text-bg-light btn btn-outline-dark m-1"
                                                    style="cursor:auto">
                                                    <span class="badge text-bg-primary">{{ $upcomingCount }}</span>
                                                    Upcoming
                                                </span>
                                            @endif
                                            @if ($paidCount)
                                                <span class="badge rounded-pill text-bg-success  m-1">
                                                    <span class="badge text-bg-light">{{ $paidCount }}</span>
                                                    Paid
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </caption>
    @if ($officeExpenses->isEmpty())
        <caption class="caption-top">
            <div class="text-center fs-3 card p-5">No Office Expense Found</div>
        </caption>
    @else
        <thead>
            <tr>
                <th class="table-td-min text-center">#</th>
                <th>Type</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
                @if (!$pdf)
                    <th class="text-center"><i class="bi bi-journals"></i></th>
                    <th class="table-td-min"></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($officeExpenses as $officeExpense)
                <tr>
                    <td>
                        <a class="text-reset text-center" href="{{ route('officeExpense.show', $officeExpense->id) }}">
                            <div>{{ $officeExpense->id }}</div>
                        </a>
                    </td>
                    <td>{{ $officeExpense->type }}</td>
                    <td>{{ $officeExpense->description }}</td>
                    <td>{{ formattedMoney($officeExpense->amount) }}</td>
                    <td>{{ formattedDate($officeExpense->expense_date) }}</td>

                    @if (!$pdf)
                        <td class="text-center">

                            <button class="btn-outline-dark btn p-0 px-2"
                                id="officeExpense{{ $officeExpense->id }}NotesCount" data-bs-toggle="modal"
                                data-bs-target="#showNotesModal"
                                data-route='{{ route('note', ['officeExpense', $officeExpense->id]) }}'>
                                {{ $officeExpense->notes_count }}
                            </button>

                        </td>

                        <td>
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
                                        <a class="dropdown-item"
                                            href="{{ route('officeExpense.show', $officeExpense->id) }}">
                                            <i class="bi bi-eye me-2"></i>View
                                        </a>
                                    </li>

                                    <li>
                                        <a
                                            class="dropdown-item"href="{{ route('officeExpense.show', $officeExpense->id) }}?edit=true">
                                            <i class="bi bi-pencil me-2"></i>Edit
                                        </a>
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
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    @endif
</table>
