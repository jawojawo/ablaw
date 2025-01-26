@props(['adminDeposits', 'multiCase' => false, 'pdf' => false])
@php
    if ($pdf) {
        $paginatedAdminDeposits = $adminDeposits
            ->withCount(['notes'])
            ->with(['lawCase'])
            ->orderBy('deposit_date', 'desc')
            ->get();
    } else {
        $paginatedAdminDeposits = $adminDeposits
            ->withCount(['notes'])
            ->with(['lawCase'])
            ->orderBy('deposit_date', 'desc')
            ->paginate(10, ['*'], 'admin-deposits-page');
    }
@endphp
@if ($paginatedAdminDeposits->isEmpty())
    <p class="text-center text-muted">No admin deposits found.</p>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="table-td-min  text-center">#</th>
                <th>Payment Type</th>
                <th>Amount </th>
                <th class="text-nowrap">Deposit Date</th>
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
            @foreach ($paginatedAdminDeposits as $deposit)
                @if ($multiCase)
                    @if ($previousLawCaseNumber !== $deposit->lawCase->case_number)
                        <tr>

                            <td class="table-info border-end-0">
                                <a class="text-reset text-center" href="{{ route('case', $deposit->lawCase->id) }}">
                                    <div>{{ $deposit->lawCase->id }}</div>
                                </a>
                            </td>
                            <td colspan="100" class="table-info border-start-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold pe-3">{{ $deposit->lawCase->case_number }}</span>
                                    <span class="fst-italic">
                                        {{ $deposit->lawCase->case_title }}
                                        <button class="btn btn-sm btn-primary ms-4"
                                            data-law-case-id={{ $deposit->lawCase->id }} data-bs-toggle="modal"
                                            data-bs-target="#addAdminDepositModal">
                                            Add Deposit
                                        </button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @php
                            $previousLawCaseNumber = $deposit->lawCase->case_number;
                        @endphp
                    @endif
                @endif
                <tr>
                    <td class="text-nowrap text-center">{{ $deposit->id }}</td>
                    <td class="w-100">{{ Str::headline($deposit->payment_type) }}</td>
                    <td>₱{{ number_format($deposit->amount, 2) }}</td>
                    <td class="text-nowrap">{{ formattedDate($deposit->deposit_date) }}
                    </td>
                    @if (!$pdf)
                        <td class="text-center">

                            <button class="btn-outline-dark btn p-0 px-2" id="deposit{{ $deposit->id }}NotesCount"
                                data-bs-toggle="modal" data-bs-target="#showNotesModal"
                                data-route='{{ route('note', ['deposit', $deposit->id]) }}'>
                                {{ $deposit->notes_count }}
                            </button>

                        </td>

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
                                        <a class="dropdown-item" href="#"
                                            data-admin-deposit-id="{{ $deposit->id }}"
                                            data-admin-deposit-payment-type="{{ $deposit->payment_type }}"
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
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    @if (!$pdf)
        {{ $paginatedAdminDeposits->links() }}
    @endif
@endif
