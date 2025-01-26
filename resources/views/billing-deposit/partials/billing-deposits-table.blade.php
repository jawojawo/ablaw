@props(['pdf' => false])
<table class="table table-bordered ">

    <caption class="caption-top">
        <div class="row mb-4">
            <div class=" col-lg-12">
                <div class="card">
                    <div class="card-header text-bg-primary">
                        <h6 class="mb-0">Bill Deposits Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-secondary pb-2">
                                    Deposits from <span class="text-primary">{{ formattedDate($minDepositDate) }}</span>
                                    to
                                    <span class="text-primary">{{ formattedDate($maxDepositDate) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 ">
                                    <span># Deposits</span>
                                    @if ($pdf)
                                        <span>{{ $deposits->count() }}</span>
                                    @else
                                        <span>{{ $deposits->total() }}</span>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between mb-2 ">
                                    <span>Total Amount Received</span>
                                    <span>{{ formattedMoney($totalAmount) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </caption>
    @if ($deposits->isEmpty())
        <caption class="caption-top">
            <div class="text-center fs-3 card p-5">No Bill Deposit Found</div>
        </caption>
    @else
        <thead>
            <tr>
                <th class="table-td-min text-center">#</th>
                <th>Case</th>

                <th>For</th>
                <th>Type</th>
                <th>Amount</th>
                <th class='text-nowrap'>Deposit Date</th>
                <th class='text-nowrap'>Received From</th>
                <th class='text-nowrap'>Received By</th>
                @if (!$pdf)
                    <th class="text-center"><i class="bi bi-journals"></i></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($deposits as $deposit)
                <tr>
                    <td class="text-center">
                        {{ $deposit->id }}
                    </td>

                    <td class="text-nowrap">
                        <a tabindex="0" data-bs-custom-class="custom-popover-wo-padding"
                            class="popover-click text-reset" data-bs-toggle="popover"
                            data-bs-title="<div >#{{ $deposit->lawCase->id }} Case</div>"
                            data-bs-content="<div class='fs-italic py-2 px-4'>
                            {{ $deposit->lawCase->case_title }}
                            </div>
                          
                                <a class='btn btn-sm btn-primary w-100 ' href='{{ route('case.show', $deposit->lawCase->id) }}' >
                                    Go to Case
                                </a>
                            
                                ">
                            {{ $deposit->lawCase->case_number }}
                        </a>
                    </td>


                    <td>{{ $deposit->billing->title }}</td>
                    <td class="text-nowrap">
                        {{ $deposit->payment_type }}
                    </td>
                    <td>{{ formattedMoney($deposit->amount) }}</td>
                    <td class="text-nowrap">{{ formattedDate($deposit->deposit_date) }}</td>

                    <td>{{ $deposit->received_from }}</td>
                    <td>{{ $deposit->user->name }}</td>
                    @if (!$pdf)
                        <td class="text-center">
                            <button class="btn-outline-dark   btn p-0 px-2" id="deposit{{ $deposit->id }}NotesCount"
                                data-bs-toggle="modal" data-bs-target="#showNotesModal"
                                data-route='{{ route('note', ['billingDeposit', $deposit->id]) }}'>
                                {{ $deposit->notes_count }}
                            </button>

                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    @endif
</table>
