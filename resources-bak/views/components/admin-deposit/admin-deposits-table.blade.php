@props(['adminDeposits'])
@php
    $paginatedAdminDeposits = $adminDeposits
        ->with(['paymentType', 'user'])
        ->orderBy('deposit_date', 'desc')
        ->paginate(10, ['*'], 'admin-deposits-page');
@endphp
@if ($paginatedAdminDeposits->isEmpty())
    <p class="text-center text-muted">No admin deposits found.</p>
@else
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="table-td-min  text-center">#</th>
                <th>Payment Type</th>
                <th>Amount </th>
                <th>Deposit Date</th>
                <th>By</th>
                <th class="table-td-min"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paginatedAdminDeposits as $deposit)
                <tr>
                    <td class="text-nowrap">{{ $deposit->id }}</td>
                    <td>{{ Str::headline($deposit->paymentType->name) }}</td>
                    <td>₱{{ number_format($deposit->amount, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($deposit->deposit_date)->format('M j, Y ') }}
                    </td>
                    <td>{{ $deposit->user->name }}</td>
                    <td>
                        <div class="dropdown">

                            <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                aria-expanded="false"></button>
                            <ul class="dropdown-menu shadow">
                                <li>
                                    <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                        <span class="text-muted">#{{ $deposit->id }}</span>
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-admin-deposit-id="{{ $deposit->id }}"
                                        data-admin-deposit-payment-type-id="{{ $deposit->payment_type_id }}"
                                        data-admin-deposit-amount="{{ $deposit->amount }}"
                                        data-admin-deposit-deposit-date="{{ $deposit->deposit_date }}"
                                        data-bs-toggle="modal" data-bs-target="#editAdminDepositModal">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        data-admin-deposit-id="{{ $deposit->id }}" data-bs-toggle="modal"
                                        data-bs-target="#deleteAdminDepositModal">
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
    {{ $paginatedAdminDeposits->links() }}

@endif
