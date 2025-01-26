@props(['billings', 'multiCase' => false, 'pdf' => false])

@php
    if ($pdf) {
        $paginatedBillings = $billings
            ->with(['lawCase'])
            ->withCount(['notes'])
            ->orderedBillings()
            ->get();
    } else {
        $paginatedBillings = $billings
            ->with(['lawCase'])
            ->withCount(['notes'])
            ->orderedBillings()
            ->paginate(10, ['*'], 'billings-page');
    }
@endphp

@if ($paginatedBillings->isEmpty())
    <p class="text-center text-muted">No Bills found.</p>
@else
    <table class="table table-bordered">
        @if (!$pdf)
            <caption class="caption-top">
                <div class="d-flex">
                    <div class="d-flex me-4">
                        <div class="border border-secondary bg-danger pe-4 me-2"></div>
                        <span>Overdue</span>
                    </div>
                    <div class="d-flex me-4">
                        <div class="border border-secondary bg-primary pe-4 me-2"></div>
                        <span>Due Today</span>
                    </div>
                    <div class="d-flex me-4">
                        <div class="border border-secondary pe-4 me-2"></div>
                        <span>Upcoming</span>
                    </div>
                    <div class="d-flex me-4">
                        <div class="border border-secondary bg-success pe-4 me-2"></div>
                        <span>Paid</span>
                    </div>
                </div>
            </caption>
        @endif
        <thead>
            <tr>
                <th class="table-td-min text-center">#</th>
                <th>Title</th>
                <th>Amount</th>
                <th>Paid</th>
                @if (!$pdf)
                    <th class="text-center"><i class="bi bi-cash-stack"></i></th>
                @endif
                <th>Defecit</th>
                <th>Billing Date</th>
                <th>Due Date</th>

                <th class="table-td-min text-center"><i class="bi bi-info-square"></i></th>
                @if (!$pdf)
                    <th class="table-td-min text-center"><i class="bi bi-file-earmark-pdf"></i></th>
                    <th class="table-td-min text-center"><i class="bi bi-journals"></i></th>
                    <th class="table-td-min"></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php
                $previousLawCaseNumber = null;
            @endphp
            @foreach ($paginatedBillings as $billing)
                @if ($multiCase)
                    @if ($previousLawCaseNumber !== $billing->lawCase->case_number)
                        <tr>
                            <td class="text-center table-info border-end-0">
                                <a class="text-reset text-center"
                                    href="{{ route('case.show', $billing->lawCase->id) }}">
                                    <div>{{ $billing->lawCase->id }}</div>
                                </a>
                            </td>
                            <td colspan="100" class="table-info border-start-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold pe-3">{{ $billing->lawCase->case_number }}</span>
                                    <span class="fst-italic">{{ $billing->lawCase->case_title }}
                                        <button class="btn btn-sm btn-primary ms-4"
                                            data-law-case-id={{ $billing->lawCase->id }} data-bs-toggle="modal"
                                            data-bs-target="#addBillingModal">
                                            Add Bill
                                        </button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @php
                            $previousLawCaseNumber = $billing->lawCase->case_number;
                        @endphp
                    @endif
                @endif
                <tr>
                    <td class="text-nowrap">{{ $billing->id }}</td>
                    <td>{{ $billing->title }}</td>
                    <td>₱{{ number_format($billing->amount, 2) }}</td>
                    <td>₱{{ number_format($billing->total_paid, 2) }}</td>
                    @if (!$pdf)
                        <td class="text-center">
                            <a class="text-underline text-reset text-center" href="#"
                                data-billing-id="{{ $billing->id }}" data-bs-toggle="modal"
                                data-bs-target="#ViewBillingDeposistModal">
                                {{ $billing->deposits()->count() }}
                            </a>

                        </td>
                    @endif
                    <td>₱{{ number_format($billing->defecit, 2) }}</td>
                    <td class="text-nowrap">{{ $billing->formatted_billing_date }}</td>
                    <td class="text-nowrap">{{ $billing->formatted_due_date }}</td>
                    <td>
                        @if (!$pdf)
                            {!! $billing->status_badge !!}
                        @else
                            {{ Str::headline($billing->status) }}
                        @endif
                    </td>
                    @if (!$pdf)
                        <td>
                            <button class="btn-outline-dark btn-sm btn" class="export-invoice-btn"
                                data-header="Invoice for billing #{{ $billing->id }}"
                                data-route="{{ route('pdf.billingInvoice', $billing->id) }}"
                                data-file-name = "AB-{{ sprintf('%03d', $billing->id) }}.pdf" data-bs-toggle="modal"
                                data-bs-target="#pdfViewModal">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </button>
                        </td>

                        <td class="text-center">

                            <button class="btn-outline-dark btn p-0 px-2" id="billing{{ $billing->id }}NotesCount"
                                data-bs-toggle="modal" data-bs-target="#showNotesModal"
                                data-route='{{ route('note', ['billing', $billing->id]) }}'>
                                {{ $billing->notes_count }}
                            </button>

                        </td>

                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                    data-bs-display="dynamic" aria-expanded="false"></button>
                                <ul class="dropdown-menu shadow">
                                    <li>
                                        <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                            <span class="text-muted">#{{ $billing->id }}</span>
                                        </h6>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-success" href="#"
                                            data-billing-id="{{ $billing->id }}"
                                            data-billing-title="{{ $billing->title }}"
                                            data-billing-amount="{{ number_format($billing->amount, 2) }}"
                                            data-billing-paid="{{ number_format($billing->total_paid, 2) }}"
                                            data-billing-defecit="{{ number_format($billing->defecit, 2) }}"
                                            data-billing-due-date="{{ $billing->formatted_due_date }}"
                                            data-billing-client-name="{{ $billing->client->name }}"
                                            data-bs-toggle="modal" data-bs-target="#addBillingDepositModal">
                                            <i class="bi bi-cash-stack me-2"></i>Add Payment
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" data-billing-id="{{ $billing->id }}"
                                            data-bs-toggle="modal" data-bs-target="#ViewBillingDeposistModal">
                                            <i class="bi bi-eye me-2"></i>View Payments
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            data-header="Invoice for billing #{{ $billing->id }}"
                                            data-route="{{ route('pdf.billingInvoice', $billing->id) }}"
                                            data-file-name = "AB-{{ sprintf('%03d', $billing->id) }}.pdf"
                                            data-bs-toggle="modal" data-bs-target="#pdfViewModal">
                                            <i class="bi bi-file-earmark-pdf me-2"></i>Generate Invoice
                                        </a>

                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" data-billing-id="{{ $billing->id }}"
                                            data-billing-title="{{ $billing->title }}"
                                            data-billing-amount="{{ $billing->amount }}"
                                            data-billing-billing-date="{{ $billing->billing_date }}"
                                            data-billing-due-date="{{ $billing->due_date }}" data-bs-toggle="modal"
                                            data-bs-target="#editBillingModal">
                                            <i class="bi bi-pencil me-2"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#"
                                            data-billing-id="{{ $billing->id }}" data-bs-toggle="modal"
                                            data-bs-target="#deleteBillingModal">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    @endif
                </tr>
                @if ($pdf)
                    <tr>
                        <td colspan="2" class="p-0">
                        </td>
                        <td colspan="100" class="p-0">
                            <table class="table mb-0">
                                <thead>
                                    <th>Amount</th>
                                    <th>Deposit Date</th>
                                    <th>Received From</th>
                                    <th>Received By</th>
                                </thead>
                                <tbody>
                                    @forelse ($billing->deposits as $deposit)
                                        <tr>
                                            <td>{{ formattedMoney($deposit->amount) }}</td>
                                            <td>{{ formattedDate($deposit->deposit_date) }}</td>
                                            <td>{{ $deposit->received_from }}</td>
                                            <td>{{ $deposit->user->name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100">
                                                <div class="text-center">No Payments Made</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @if (!$pdf)
        {{ $paginatedBillings->links() }}
    @endif
@endif
