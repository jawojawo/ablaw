@props(['lawCase'])

<div class="row">

    @if (auth()->user()->can('view', getPermissionClass('Case Deposits')) ||
            auth()->user()->can('view', getPermissionClass('Case Expenses')))
        <div class="col-4">
            <div class="card h-100">
                <div class="card-header text-bg-primary">
                    <h6 class="mb-0">Deposits/Expenses</h6>
                </div>
                <div class="card-body">
                    @can('view', getPermissionClass('Case Deposits'))
                        <div class='d-flex justify-content-between'>
                            <div># Deposits</div>
                            <div>
                                {{ $lawCase->adminDeposits->count() }}
                            </div>
                        </div>
                    @endcan
                    @can('view', getPermissionClass('Case Expenses'))
                        <div class='d-flex justify-content-between'>
                            <div># Deductable Expenses</div>
                            <div>
                                {{ $lawCase->adminFees->count() }}
                            </div>
                        </div>
                    @endcan
                    <table class="table table-sm table-borderless mb-0">
                        @can('view', getPermissionClass('Case Deposits'))
                            <tr>
                                <td class="align-middle">Total Deposits: </td>
                                <td class="align-middle text-end">
                                    ₱{{ number_format($lawCase->total_deposits, 2) }}</td>
                            </tr>
                        @endcan
                        @can('view', getPermissionClass('Case Expenses'))
                            <tr>
                                <td class="align-middle">Total Expenses: </td>
                                <td class="align-middle text-end">₱{{ number_format($lawCase->total_fees, 2) }}
                                </td>
                            </tr>
                        @endcan
                    </table>
                </div>
                @if (auth()->user()->can('view', getPermissionClass('Case Deposits')) &&
                        auth()->user()->can('view', getPermissionClass('Case Expenses')))
                    <div class="card-footer d-flex justify-content-between {{ $lawCase->remaining_deposit_class }}">
                        <div>Remaining: </div>
                        <div>₱{{ $lawCase->remaining_deposit }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endif
    @can('view', getPermissionClass('Case Hearings'))
        <div class="col-4">
            <div class="card  h-100">
                <div class="card-header text-bg-primary">
                    <h6 class="mb-0">Hearings</h6>
                </div>
                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">
                        <div># Hearings: </div>
                        <div>{{ $lawCase->hearings()->count() }}</div>
                    </div>
                    <div class="mb-2">
                        @if ($lawCase->ongoing_hearings_count)
                            <span class='badge rounded-pill t-primary  m-1'>
                                <span class='badge text-bg-light'>{{ $lawCase->ongoing_hearings_count }}</span>
                                Ongoing Hearings
                            </span>
                        @endif
                        @if ($lawCase->upcoming_hearings_count)
                            <span class="badge rounded-pill text-bg-light btn btn-outline-dark m-1" style="cursor:auto">
                                <span class="badge text-bg-dark">{{ $lawCase->upcoming_hearings_count }}</span>
                                Upcoming Hearings
                            </span>
                        @endif
                        @if ($lawCase->completed_hearings_count)
                            <span class='badge rounded-pill t-secondary  m-1'>
                                <span class='badge text-bg-light'>{{ $lawCase->completed_hearings_count }}</span>
                                Completed Hearings
                            </span>
                        @endif
                        @if ($lawCase->canceled_hearings_count)
                            <span class='badge rounded-pill t-danger  m-1'>
                                <span class='badge text-bg-light'>{{ $lawCase->canceled_hearings_count }}</span>
                                Canceled Hearings
                            </span>
                        @endif
                    </div>
                    @if ($lawCase->next_hearing)
                        <div class="text-primary">Next Hearing</div>
                        <div>{{ $lawCase->next_hearing->formatted_hearing_date['full'] }}</div>
                    @else
                        <div class="text-muted">No Hearings found.</div>
                    @endif

                </div>
            </div>
        </div>
    @endcan
    @can('view', getPermissionClass('Case Billings'))
        <div class="col-4">
            <div class="card  h-100">
                <div class="card-header text-bg-primary">
                    <h6 class="mb-0">Billings</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="align-middle"># Bills</td>
                            <td class="align-middle text-end">{{ $lawCase->billings->count() }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle">Total Bills:</td>
                            <td class="align-middle text-end">
                                ₱{{ number_format($lawCase->billings->sum('amount'), 2) }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle">Total Paid:</td>
                            <td class="align-middle text-end">
                                ₱{{ number_format($lawCase->billings->sum('total_paid'), 2) }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle">Remaining Payables:</td>
                            <td class="align-middle text-end">
                                ₱{{ number_format($lawCase->total_outstanding_bill_balance, 2) }}</td>
                        </tr>
                    </table>
                    @if ($lawCase->over_due_bills_count)
                        <span class="badge rounded-pill text-bg-danger  m-1">
                            <span class="badge text-bg-light">{{ $lawCase->over_due_bills_count }}</span>
                            Over Due
                        </span>
                    @endif
                    @if ($lawCase->due_today_bills_count)
                        <span class="badge rounded-pill text-bg-primary  m-1">
                            <span class="badge text-bg-light">{{ $lawCase->due_today_bills_count }}</span>
                            Due Today
                        </span>
                    @endif
                    @if ($lawCase->upcoming_bills_count)
                        <span class="badge rounded-pill text-bg-light btn btn-outline-dark m-1" style="cursor:auto">
                            <span class="badge text-bg-primary">{{ $lawCase->upcoming_bills_count }}</span>
                            Upcoming
                        </span>
                    @endif
                    @if ($lawCase->paid_bills_count)
                        <span class="badge rounded-pill text-bg-success m-1" style="cursor:auto">
                            <span class="badge text-bg-light">{{ $lawCase->paid_bills_count }}</span>
                            Paid
                        </span>
                    @endif

                </div>


            </div>
        </div>
    @endcan
</div>
