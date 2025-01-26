@props(['billings'])

@php
    $paginatedBillings = $billings
        ->with(['user'])
        ->orderedBillings()
        ->paginate(10, ['*'], 'billings-page');
@endphp

@if ($paginatedBillings->isEmpty())
    <p class="text-center text-muted">No Billings found.</p>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="table-td-min text-center">#</th>
                <th>Title</th>
                <th>Amount</th>
                <th>Paid</th>
                <th>Billing Date</th>
                <th>
                    Due Date
                    </a>
                </th>
                <th>By</th>
                <th class="table-td-min"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paginatedBillings as $billing)
                <tr class="{{ $billing->due_date_table_class }}">
                    <td class="text-nowrap">{{ $billing->id }}</td>
                    <td>{{ $billing->title }}</td>
                    <td>
                        ₱{{ number_format($billing->amount, 2) }}</td>
                    <td>
                        ₱{{ number_format($billing->total_paid, 2) }}</td>
                    <td class="text-nowrap">{{ $billing->formatted_billing_date }}</td>
                    <td class="text-nowrap">
                        {{ $billing->formatted_due_date }}</td>
                    <td>{{ $billing->user->name }}</td>
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
                                        data-bs-toggle="modal" data-bs-target="#addBillingDepositModal">
                                        <i class="bi bi-cash-stack me-2"></i>Add Deposit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-billing-id="{{ $billing->id }}"
                                        data-bs-toggle="modal" data-bs-target="#ViewBillingDeposistModal">
                                        <i class="bi bi-eye me-2"></i>View Deposits
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
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $paginatedBillings->links() }}
@endif
