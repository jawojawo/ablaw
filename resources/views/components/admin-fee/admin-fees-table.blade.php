@props(['adminFees', 'multiCase' => false, 'pdf' => false])
@php
    if ($pdf) {
        $paginatedAdminFees = $adminFees
            ->withCount(['notes'])
            ->with(['lawCase'])
            ->orderBy('fee_date', 'desc')
            ->get();
    } else {
        $paginatedAdminFees = $adminFees
            ->withCount(['notes'])
            ->with(['lawCase'])
            ->orderBy('fee_date', 'desc')
            ->paginate(10, ['*'], 'admin-fees-page');
    }
@endphp
@if ($paginatedAdminFees->isEmpty())
    <p class="text-center text-muted">No Expenses found.</p>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="table-td-min text-center">#</th>
                <th class="w-100">Type</th>
                <th>Amount </th>
                <th>Fee Date</th>
                @if (!$pdf)
                    <th class="table-td-min text-center"><i class="bi bi-journals"></i></th>
                    <th class="table-td-min"></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php
                $previousLawCaseNumber = null;
            @endphp
            @foreach ($paginatedAdminFees as $fee)
                @if ($multiCase)
                    @if ($previousLawCaseNumber !== $fee->lawCase->case_number)
                        <tr>
                            <td class="text-center table-info border-end-0">
                                <a class="text-reset text-center" href="{{ route('case.show', $fee->lawCase->id) }}">
                                    <div>{{ $fee->lawCase->id }}</div>
                                </a>
                            </td>
                            <td colspan="100" class="table-info border-start-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold pe-3">{{ $fee->lawCase->case_number }}</span>
                                    <span class="fst-italic">{{ $fee->lawCase->case_title }}
                                        <button class="btn btn-sm btn-primary ms-4"
                                            data-law-case-id={{ $fee->lawCase->id }} data-bs-toggle="modal"
                                            data-bs-target="#addAdminFeeModal">
                                            Add Expense
                                        </button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @php
                            $previousLawCaseNumber = $fee->lawCase->case_number;
                        @endphp
                    @endif
                @endif
                <tr>
                    <td class="text-nowrap">{{ $fee->id }}</td>
                    <td>{{ $fee->type }}</td>
                    <td>₱{{ number_format($fee->amount, 2) }}</td>
                    <td class="text-nowrap">{{ formattedDate($fee->fee_date) }}
                    </td>
                    @if (!$pdf)
                        <td class="text-center">

                            <button class="btn-outline-dark btn p-0 px-2" id="admin-fee{{ $fee->id }}NotesCount"
                                data-bs-toggle="modal" data-bs-target="#showNotesModal"
                                data-route='{{ route('note', ['admin-fee', $fee->id]) }}'>
                                {{ $fee->notes_count }}
                            </button>

                        </td>

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
                                            data-admin-fee-type="{{ $fee->type }}"
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
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    @if (!$pdf)
        {{ $paginatedAdminFees->links() }}
    @endif
@endif
