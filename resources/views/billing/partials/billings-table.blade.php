@props(['pdf' => false])
<table class="table table-bordered ">
    <caption class="caption-top">
        <div class="row mb-4">
            <div class=" col-lg-12">
                <div class="card">
                    <div class="card-header text-bg-primary">
                        <h6 class="mb-0">Bills Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-secondary pb-2">
                                    Bills from <span class="text-primary">{{ formattedDate($minBillDate) }}</span> to
                                    <span class="text-primary">{{ formattedDate($maxBillDate) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 ">
                                    <span>Total Amount Billed:</span>
                                    <span>{{ formattedMoney($totalAmount) }}</span>
                                </div>
                                <div class="border-top border-bottom">
                                    <div class="d-flex justify-content-between align-items-center  my-2">
                                        <div>Total Amount Paid</div>
                                        <div class="d-flex justify-content-end flex-wrap">
                                            {{ formattedMoney($totalPaid) }}
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="border-top border-bottom">
                                    <div class="d-flex justify-content-between align-items-center  my-2">
                                        <div>Total Deposits Received From <span
                                                class="text-primary">{{ formattedDate($minDepositDate) }}</span> to
                                            <span class="text-primary">{{ formattedDate($maxDepositDate) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-end flex-wrap">
                                            {{ formattedMoney($totalDeposits) }}
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="border-bottom">
                                    <div class="d-flex justify-content-between align-items-center my-2">
                                        <div>Total Remaining Payables</div>
                                        <div class="d-flex justify-content-end flex-wrap">
                                            {{ formattedMoney($totalOutstandingBalance) }}
                                        </div>

                                    </div>
                                </div>
                                <div class="border-bottom">
                                    <div class="d-flex justify-content-between align-items-center my-2">
                                        <div>
                                            @if ($pdf)
                                                <span>{{ $bills->count() }}</span>
                                            @else
                                                <span>{{ $bills->total() }}</span>
                                            @endif
                                            Bills
                                        </div>
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
                                                <span class="badge rounded-pill text-bg-light btn btn-outline-dark m-1"
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
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </caption>
    @if ($bills->isEmpty())
        <caption class="caption-top">
            <div class="text-center fs-3 card p-5">No Bills Found</div>
        </caption>
    @else
        <thead>
            <tr>
                <th class="table-td-min text-center">#</th>
                <th>Title</th>
                <th>Case</th>
                <th>Amount</th>
                <th>Paid</th>
                <th>Defecit</th>
                <th>Billing Date</th>
                <th>Due Date</th>
                <th class="text-center">
                    <a tabindex="0" data-bs-custom-class="custom-popover" class="popover-click text-init"
                        data-bs-toggle="popover" data-bs-title="Bills"
                        data-bs-content="
                            <div class='d-flex'>
                                <div class='bg-danger ' style='width:15px;height:15px'></div>
                                <span class='ps-2'>Over Due</span>
                            </div>
                            <div class='d-flex'>
                                <div class='bg-primary ' style='width:15px;height:15px'></div>
                                <span class='ps-2'>Due Today</span>
                            </div>
                             <div class='d-flex'>
                                <div class='border border-dark ' style='width:15px;height:15px'></div>
                                <span class='ps-2'>Upcoming</span>
                            </div>
                             <div class='d-flex'>
                                <div class='bg-success' style='width:15px;height:15px'></div>
                                <span class='ps-2'>Paid</span>
                            </div>
                        ">
                        <i class="bi bi-info-square"></i>
                    </a>
                </th>
                @if (!$pdf)
                    <th class="text-center"><i class="bi bi-journals"></i></th>
                @endif
                {{-- <th></th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($bills as $bill)
                <tr>
                    <td class="text-center">

                        <a class="text-underline text-reset text-center" data-billing-id="{{ $bill->id }}"
                            data-bs-toggle="modal" data-bs-target="#ViewBillingDeposistModal">
                            {{ $bill->id }}
                        </a>
                    </td>
                    <td>{{ $bill->title }}</td>
                    <td class="text-nowrap">
                        <a tabindex="0" data-bs-custom-class="custom-popover-wo-padding"
                            class="popover-click text-reset" data-bs-toggle="popover"
                            data-bs-title="<div >#{{ $bill->lawCase->id }} Case</div>"
                            data-bs-content="<div class='fs-italic py-2 px-4'>
                            {{ $bill->lawCase->case_title }}
                            </div>
                          
                                <a class='btn btn-sm btn-primary w-100 ' href='{{ route('case.show', $bill->lawCase->id) }}' >
                                    Go to Case
                                </a>
                            
                                ">
                            {{ $bill->lawCase->case_number }}
                        </a>


                    </td>
                    <td>{{ formattedMoney($bill->amount) }}</td>
                    <td class="text-nowrap">
                        {{ formattedMoney($bill->total_paid) }}

                    </td>
                    <td>{{ formattedMoney($bill->defecit) }}</td>
                    <td class="text-nowrap">{{ formattedDate($bill->billing_date) }}</td>
                    <td class="text-nowrap">{{ formattedDate($bill->due_date) }}</td>
                    @if (!$pdf)
                        <td class="text-center ">{!! $bill->status_badge !!}</td>
                    @else
                        <td>{{ Str::headline($bill->status) }}</td>
                    @endif

                    @if (!$pdf)
                        <td class="text-center">

                            <button class="btn-outline-dark   btn p-0 px-2" id="billing{{ $bill->id }}NotesCount"
                                data-bs-toggle="modal" data-bs-target="#showNotesModal"
                                data-route='{{ route('note', ['billing', $bill->id]) }}'>
                                {{ $bill->notes_count }}
                            </button>

                        </td>
                    @endif
                    {{-- <td>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                data-bs-display="dynamic" aria-expanded="false"></button>
                            <ul class="dropdown-menu shadow">
                                <li>
                                    <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                        <span class="text-muted">#{{ $bill->id }}</span>
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item text-success" href="#" data-refresh=true
                                        data-billing-id="{{ $bill->id }}" data-billing-title="{{ $bill->title }}"
                                        data-billing-amount="{{ number_format($bill->amount, 2) }}"
                                        data-billing-paid="{{ number_format($bill->total_paid, 2) }}"
                                        data-billing-defecit="{{ number_format($bill->defecit, 2) }}"
                                        data-billing-due-date="{{ $bill->formatted_due_date }}" data-bs-toggle="modal"
                                        data-bs-target="#addBillingDepositModal">
                                        <i class="bi bi-cash-stack me-2"></i>Add Deposit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-billing-id="{{ $bill->id }}"
                                        data-bs-toggle="modal" data-bs-target="#ViewBillingDeposistModal">
                                        <i class="bi bi-eye me-2"></i>View Deposits
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-billing-id="{{ $bill->id }}"
                                        data-billing-title="{{ $bill->title }}"
                                        data-billing-amount="{{ $bill->amount }}"
                                        data-billing-billing-date="{{ $bill->billing_date }}"
                                        data-billing-due-date="{{ $bill->due_date }}" data-bs-toggle="modal"
                                        data-bs-target="#editBillingModal">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        data-billing-id="{{ $bill->id }}" data-bs-toggle="modal"
                                        data-bs-target="#deleteBillingModal">
                                        <i class="bi bi-trash me-2"></i>Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td> --}}
                </tr>
            @endforeach


        </tbody>
    @endif
</table>
