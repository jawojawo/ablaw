@extends('layout.pdf')
@section('pdfContent')
    <div class="card mb-4">
        @include('case.partials.case-info', ['pdf' => true])
    </div>
    {{-- extra info --}}
    <div class="mb-4">
        @include('components.extra-info', [
            'lawCase' => $lawCase,
            'pdf' => true,
        ])
    </div>

    <div class="mb-4">
        <h6 class="mb-0">Retainer Deposit</h6>
        @include('components.admin-deposit.admin-deposits-table', [
            'adminDeposits' => $lawCase->adminDeposits(),
            'pdf' => true,
        ])
    </div>
    <div class="mb-4">
        <h6 class="mb-0">Expenses</h6>
        @include('components.admin-fee.admin-fees-table', [
            'adminFees' => $lawCase->adminFees(),
            'pdf' => true,
        ])
    </div>

    <div class="mb-4">
        <h6 class="mb-0">Hearings</h6>
        @include('components.hearing.hearings-table', ['hearings' => $lawCase->hearings(), 'pdf' => true])
    </div>
    <div class="mb-4">
        <h6 class="mb-0">Bills</h6>
        @include('components.billing.billings-table', [
            'billings' => $lawCase->billings()->orderedBillings(),
            'pdf' => true,
        ])
    </div>
@endsection
