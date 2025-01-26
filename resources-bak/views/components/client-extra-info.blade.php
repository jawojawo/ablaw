@props(['client'])
<div class="row  mb-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header text-bg-primary">
                <h6 class="mb-0">Admin Deposits/Fees</h6>
            </div>
            <div class="card-body">
                <div class='d-flex justify-content-between'>
                    <div># Admin Deposits</div>
                    <div>
                        {{ $client->admin_deposits_count }}
                    </div>
                </div>
                <div class='d-flex justify-content-between'>
                    <div># Admin Fees</div>
                    <div>
                        {{ $client->admin_fees_count }}
                    </div>
                </div>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="align-middle">Total Admin Deposits: </td>
                        <td class="align-middle text-end">
                            ₱{{ number_format($client->lawCases->sum('total_deposits'), 2) }}</td>
                    </tr>
                    <tr>
                        <td class="align-middle">Total Fees: </td>
                        <td class="align-middle text-end">
                            ₱{{ number_format($client->lawCases->sum('total_fees'), 2) }}
                        </td>
                    </tr>
                </table>
            </div>
            <div
                class="card-footer d-flex justify-content-between {{ $client->lawCases->sum('total_deposits') - $client->lawCases->sum('total_fees') > 0 ? 'text-bg-success' : 'text-bg-danger' }}">
                <div>Remaining: </div>
                <div>
                    ₱{{ number_format($client->lawCases->sum('total_deposits') - $client->lawCases->sum('total_fees'), 2) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card  h-100">
            <div class="card-header text-bg-primary">
                <h6 class="mb-0">Hearings</h6>
            </div>
            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <div># Hearings: </div>
                    <div>{{ $client->hearings_count }}</div>
                </div>
                <div class="mb-2">

                    @if ($client->upcoming_hearing_count)
                        <span class="badge rounded-pill text-bg-light btn btn-outline-dark m-1" style="cursor:auto">
                            <span class="badge text-bg-primary">{{ $client->upcoming_hearing_count }}</span>
                            Upcomming Hearing
                        </span>
                    @endif

                    @if ($client->past_hearing_count)
                        <span class='badge rounded-pill t-secondary  m-1'>
                            <span class='badge text-bg-light'>{{ $client->past_hearing_count }}</span>
                            Past Hearing
                        </span>
                    @endif
                </div>
                @if ($client->next_hearing)
                    <div class="text-primary">Next Hearing</div>
                    <div>{{ $client->next_hearing->formatted_hearing_date['full'] }}</div>
                @else
                    <div class="text-muted">No Hearings found.</div>
                @endif

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card  h-100">
            <div class="card-header text-bg-primary">
                <h6 class="mb-0">Billings</h6>
            </div>
            <div class="card-body">

                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="align-middle"># Bills</td>
                        <td class="align-middle text-end">{{ $client->billings_count }}</td>
                    </tr>
                    <tr>
                        <td class="align-middle">Total Bills:</td>
                        <td class="align-middle text-end">
                            ₱{{ number_format($client->billings->sum('amount'), 2) }}</td>
                    </tr>
                    <tr>
                        <td class="align-middle">Total Paid:</td>
                        <td class="align-middle text-end">
                            ₱{{ number_format($client->billings->sum('total_paid'), 2) }}</td>
                    </tr>
                    <tr>
                        <td class="align-middle">Remaining Payables:</td>
                        <td class="align-middle text-end">
                            ₱{{ number_format($client->total_outstanding_bill_balance, 2) }}</td>
                    </tr>
                </table>
                <div>
                    @if ($client->over_due_bills_count)
                        <span class="badge rounded-pill text-bg-danger  m-1">
                            <span class="badge text-bg-light">{{ $client->over_due_bills_count }}</span>
                            Over Due
                        </span>
                    @endif
                    @if ($client->due_today_bills_count)
                        <span class="badge rounded-pill text-bg-primary  m-1">
                            <span class="badge text-bg-light">{{ $client->due_today_bills_count }}</span>
                            Due Today
                        </span>
                    @endif
                    @if ($client->upcomming_bills_count)
                        <span class="badge rounded-pill text-bg-light btn btn-outline-dark m-1" style="cursor:auto">
                            <span class="badge text-bg-primary">{{ $client->upcomming_bills_count }}</span>
                            Upcomming
                        </span>
                    @endif
                    @if ($client->paid_bills_count)
                        <span class="badge rounded-pill text-bg-success m-1" style="cursor:auto">
                            <span class="badge text-bg-light">{{ $client->paid_bills_count }}</span>
                            Paid
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
