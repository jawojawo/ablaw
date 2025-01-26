@props(['adminFees'])
@php
    $paginatedAdminFees = $adminFees
        ->with(['user', 'adminFeeCategory'])
        ->orderBy('fee_date', 'desc')
        ->paginate(10, ['*'], 'admin-fees-page');
@endphp
@if ($paginatedAdminFees->isEmpty())
    <p class="text-center text-muted">No admin fees found.</p>
@else
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="table-td-min text-center">#</th>
                <th>Title</th>
                <th>Amount </th>
                <th>Fee Date</th>
                <th>By</th>
                <th class="table-td-min"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paginatedAdminFees as $fee)
                <tr>
                    <td class="text-nowrap">{{ $fee->id }}</td>
                    <td>{{ Str::headline($fee->adminFeeCategory->name) }}</td>
                    <td>₱{{ number_format($fee->amount, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($fee->fee_date)->format('M j, Y') }}
                    </td>
                    <td>{{ $fee->user->name }}</td>
                    <td>
                        <div class="dropdown">

                            <button class="btn btn-light dropdown-toggle p-2 w-100 btn-sm" data-bs-toggle="dropdown"
                                aria-expanded="false"></button>
                            <ul class="dropdown-menu shadow">
                                <li>
                                    <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                        <span class="text-muted">#{{ $fee->id }}</span>
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-admin-fee-id="{{ $fee->id }}"
                                        data-admin-fee-category-id="{{ $fee->administrative_fee_category_id }}"
                                        data-admin-fee-amount="{{ $fee->amount }}"
                                        data-admin-fee-date="{{ $fee->fee_date }}" data-bs-toggle="modal"
                                        data-bs-target="#editAdminFeeModal">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        data-admin-fee-id="{{ $fee->id }}" data-bs-toggle="modal"
                                        data-bs-target="#deleteAdminFeeModal">
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
    {{ $paginatedAdminFees->links() }}
@endif
