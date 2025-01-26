@props(['lawCase'])

<div class="row">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header text-bg-primary">
                <h6 class="mb-0">Admin Deposits/Fees</h6>
            </div>
            <div class="card-body">
                <div class='d-flex justify-content-between'>
                    <div># Admin Deposits</div>
                    <div>
                        {{ $lawCase->adminDeposits->count() }}
                    </div>
                </div>
                <div class='d-flex justify-content-between'>
                    <div># Admin Fees</div>
                    <div>
                        {{ $lawCase->adminFees->count() }}
                    </div>
                </div>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="align-middle">Total Admin Deposits: </td>
                        <td class="align-middle text-end">
                            ₱{{ number_format($lawCase->total_deposits, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="align-middle">Total Fees: </td>
                        <td class="align-middle text-end">₱{{ number_format($lawCase->total_fees, 2) }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between {{ $lawCase->remaining_deposit_class }}">
                <div>Remaining: </div>
                <div>₱{{ $lawCase->remaining_deposit }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
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
                    @if ($lawCase->upcomming_hearing_count)
                        <span class="badge rounded-pill text-bg-light btn btn-outline-dark m-1" style="cursor:auto">
                            <span class="badge text-bg-primary">{{ $lawCase->upcomming_hearing_count }}</span>
                            Upcomming Hearing
                        </span>
                    @endif

                    @if ($lawCase->past_hearing_count)
                        <span class='badge rounded-pill t-secondary  m-1'>
                            <span class='badge text-bg-light'>{{ $lawCase->past_hearing_count }}</span>
                            Past Hearing
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
    <div class="col-md-4">
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
                {{-- @if ($lawCase->paid_bills_count)
                    <span class="badge rounded-pill text-bg-success  m-1">
                        <span class="badge text-bg-primary">{{ $lawCase->paid_bills_count }}</span>
                        Paid
                    </span>
                @endif
                @if ($lawCase->partially_paid_bills_count)
                    <span class="badge rounded-pill text-bg-warning  m-1">
                        <span class="badge text-bg-primary">{{ $lawCase->partially_paid_bills_count }}</span>
                        Partially Paid
                    </span>
                @endif
                @if ($lawCase->unpaid_bills_count)
                    <span class="badge rounded-pill text-bg-danger  m-1">
                        <span class="badge text-bg-primary">{{ $lawCase->unpaid_bills_count }}</span>
                        Unpaid
                    </span>
                @endif --}}
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
                @if ($lawCase->upcomming_bills_count)
                    <span class="badge rounded-pill text-bg-light btn btn-outline-dark m-1" style="cursor:auto">
                        <span class="badge text-bg-primary">{{ $lawCase->upcomming_bills_count }}</span>
                        Upcomming
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

</div>
