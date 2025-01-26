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
    <table class="table table-bordered billing-deposit-table">
        <thead>
            <tr>
                <th class="table-td-min text-center">#</th>
                <th>Payment Type</th>
                <th>Amount </th>
                <th>Deposit Date</th>
                <th>Received From</th>
                <th class="table-td-min text-center"><i class="bi bi-file-earmark-pdf"></i></th>
                <th class="table-td-min text-center"><i class="bi bi-journals"></i></th>
                <th class="table-td-min"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($billing->deposits()->orderBy('deposit_date', 'desc')->paginate(10, ['*'], 'billing-deposits-page') as $deposit)
                <tr>
                    <td class="text-nowrap">{{ $deposit->id }}</td>
                    <td>{{ Str::headline($deposit->payment_type) }}</td>
                    <td>₱{{ number_format($deposit->amount, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($deposit->deposit_date)->format('M j, Y ') }}</td>
                    <td>{{ $deposit->received_from }} </td>
                    <td>
                        <button class="btn-outline-dark btn-sm btn export-invoice-btn"
                            data-header="Acknowledgement Receipt for depost #{{ $deposit->id }}"
                            data-route="{{ route('pdf.acknowledgeReceipt', $deposit->id) }}"
                            data-file-name = "AR-{{ sprintf('%03d', $deposit->id) }}.pdf">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </button>
                    </td>
                    <td class="text-center">
                        <button class="btn-outline-dark btn p-0 px-2 billing-deposits-notes-btn"
                            id="deposit{{ $deposit->id }}NotesCount"
                            data-route='{{ route('note', ['billingDeposit', $deposit->id]) }}'>
                            {{ $deposit->notes->count() }}
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
                                    <button class="dropdown-item editBillingDepositModalBtn"
                                        data-billing-id="{{ $billing->id }}"
                                        data-billing-deposit-id="{{ $deposit->id }}"
                                        data-billing-deposit-payment-type="{{ $deposit->payment_type }}"
                                        data-billing-deposit-amount="{{ $deposit->amount }}"
                                        data-billing-deposit-deposit-date="{{ $deposit->deposit_date }}"
                                        data-billing-deposit-received-from="{{ $deposit->received_from }}">
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
            var paymentType = e.data('billing-deposit-payment-type')
            var amount = e.data('billing-deposit-amount')
            var depositDate = e.data('billing-deposit-deposit-date')
            var receivedFrom = e.data('billing-deposit-received-from')

            $('#editBillingDepositModal').data('billing-id', billingId).data('billing-deposit-id', id).data(
                    'billing-deposit-payment-type',
                    paymentType)
                .data('billing-deposit-amount', amount).data('billing-deposit-deposit-date', depositDate).data(
                    'billing-deposit-deposit-received-from', receivedFrom)
            $('#editBillingDepositModal').modal('show')
        }
        $(document).on('click', '.editBillingDepositModalBtn', function(e) {
            openEditBillingDepositModal($(this));
        });
        $(document).on('click', '.export-invoice-btn', function(e) {
            const header = $(this).data('header');
            const route = $(this).data('route');
            const fileName = $(this).data('file-name');

            console.log('clicked')
            const el = $(
                `<button data-route="${route}" data-header="${header}" data-file-name="${fileName}"></button>`)
            $('#pdfViewModal').modal('show', el)

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
        $(document).on('click', '.billing-deposits-notes-btn', function(e) {
            // data - bs - toggle = "modal"
            // data - bs - target = "#showNotesModal"
            // const route = $(this).data('route');
            // const route = $(this).data('route');
            // const fileName = $(this).data('file-name');

            // console.log('clicked')
            // const el = $(
            //     `<button data-route="${route}" data-header="${header}" data-file-name="${fileName}"></button>`)
            $('#showNotesModal').modal('show', $(this))

        });
    </script>
@endif
<div class="text-end">
    <a class="btn btn-primary addDepositBtn" data-billing-id="{{ $billing->id }}"
        data-billing-title="{{ $billing->title }}" data-billing-amount="{{ number_format($billing->amount, 2) }}"
        data-billing-paid="{{ number_format($billing->total_paid, 2) }}"
        data-billing-defecit="{{ number_format($billing->defecit, 2) }}"
        data-billing-due-date="{{ $billing->formatted_due_date }}"
        data-billing-client-name="{{ $billing->client->name }}" data-refresh-deposit=true>
        <i class="bi bi-cash-stack me-2"></i>Add Payment
    </a>
</div>
<script>
    $(document).on('click', '#billingDepositsTable .addDepositBtn', function(e) {
        // const header = $(this).data('header');
        // const route = $(this).data('route');
        // const fileName = $(this).data('file-name');
        // console.log('clicked')
        // const el = $(
        //     `<button data-route="${route}" data-header="${header}" data-file-name="${fileName}"></button>`)
        $('#addBillingDepositModal').modal('show', $(this))

    });
</script>
