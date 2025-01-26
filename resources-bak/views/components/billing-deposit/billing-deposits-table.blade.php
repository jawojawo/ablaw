@props(['billing'])
<table class="table table-bordered title">
    <tr>
        <th colspan="2" class="table-primary text-center">
            <span class="title">{{ $billing->title }}</span>
        </th>
    </tr>
    <tr>
        <td>Bill Amount:</td>
        <td>₱<span class="amount">{{ number_format($billing->amount, 2) }}</span></td>
    </tr>
    <tr>
        <td>Paid:</td>
        <td>₱<span class="paid">{{ number_format($billing->total_paid, 2) }}</span></td>
    </tr>
    <tr class="{{ $billing->defecit ? 'table-danger' : 'table-success' }}">
        <td>Defecit:</td>
        <td>₱<span>{{ number_format($billing->defecit, 2) }}</span>
        </td>
    </tr>
    <tr>
        <td>Due Date:</td>
        <td><span class="dueDate">{{ $billing->formatted_due_date }}</span></td>
    </tr>
</table>
@if ($billing->deposits->isEmpty())
    <p class="text-center text-muted">No #{{ $billing->id }} Bill deposits found.
    </p>
@else
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="table-td-min text-center">#</th>
                <th>Payment Type</th>
                <th>Amount </th>
                <th>Deposit Date</th>
                <th>By</th>
                <th class="table-td-min"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($billing->deposits()->orderBy('deposit_date', 'desc')->paginate(10, ['*'], 'billing-deposits-page') as $deposit)
                <tr>
                    <td class="text-nowrap">{{ $deposit->id }}</td>
                    <td>{{ Str::headline($deposit->payment_type->name) }}</td>
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
                                    <button class="dropdown-item editBillingDepositModalBtn"
                                        data-billing-id="{{ $billing->id }}"
                                        data-billing-deposit-id="{{ $deposit->id }}"
                                        data-billing-deposit-payment-type-id="{{ $deposit->payment_type_id }}"
                                        data-billing-deposit-amount="{{ $deposit->amount }}"
                                        data-billing-deposit-deposit-date="{{ $deposit->deposit_date }}">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </button>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <button class="dropdown-item text-danger deleteBillingDepositModalBtn"
                                        data-billing-id="{{ $billing->id }}"
                                        data-billing-deposit-id="{{ $deposit->id }}">
                                        <i class="bi bi-trash me-2"></i>Delete
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="id-con" data-billing-id="{{ $billing->id }}">
        {{ $billing->deposits()->orderBy('deposit_date', 'desc')->paginate(10, ['*'], 'billing-deposits-page')->links() }}
    </div>
    <script>
        function openEditBillingDepositModal(e) {
            var billingId = e.data('billing-id')
            var id = e.data('billing-deposit-id')
            var paymentTypeId = e.data('billing-deposit-payment-type-id')
            var amount = e.data('billing-deposit-amount')
            var depositDate = e.data('billing-deposit-deposit-date')
            $('#editBillingDepositModal').data('billing-id', billingId).data('billing-deposit-id', id).data(
                    'billing-deposit-payment-type-id',
                    paymentTypeId)
                .data('billing-deposit-amount', amount).data('billing-deposit-deposit-date', depositDate)
            $('#editBillingDepositModal').modal('show')
        }
        $(document).on('click', '.editBillingDepositModalBtn', function(e) {
            openEditBillingDepositModal($(this));
        });

        function openDeleteBillingDepositModal(e) {
            var billingId = e.data('billing-id')
            var id = e.data('billing-deposit-id')
            $('#deleteBillingDepositModal').data('billing-id', billingId).data('billing-deposit-id', id)
            $('#deleteBillingDepositModal').modal('show')
        }
        $(document).on('click', '.deleteBillingDepositModalBtn', function(e) {
            openDeleteBillingDepositModal($(this));
        });
    </script>
@endif
