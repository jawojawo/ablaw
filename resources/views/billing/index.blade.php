@extends('layout.app')
@section('title')
    Bills
@endsection
@section('main')
    <div class="container h-100 py-4">
        <table class="table table-bordered">
            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex gap-4">
                        <h2 class="me-2">Bills</h2>
                        <div>
                            <button class="btn btn-primary"
                                data-header="Bills from {{ formattedDate($minBillDate) }} to {{ formattedDate($maxBillDate) }}"
                                data-route="{{ request()->fullUrlWithQuery(['pdf' => 'view']) }}"
                                data-file-name="bills-{{ formattedDate($minBillDate) }}-{{ formattedDate($maxBillDate) }}.pdf"
                                data-bs-toggle="modal" data-bs-target="#pdfViewModal"><i class="bi bi-file-earmark-pdf"></i>
                                Export PDF
                            </button>
                        </div>
                    </div>

                </div>
            </caption>

            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        @if (request()->get('title') ||
                                request()->get('billing_date_from') ||
                                request()->get('billing_date_to') ||
                                request()->get('due_date_from') ||
                                request()->get('due_date_to') ||
                                request()->get('bills'))
                            <a href="{{ route('billing') }}" class="btn btn-link text-danger p-0">
                                Clear Search
                            </a>
                        @endif
                    </div>
                    <div class=" w-50">
                        <form class="mb-0">
                            <div class="input-group dropstart ">
                                <input type="search" class="form-control" placeholder="Title" name="title"
                                    value="{{ request()->get('title') }}" style="width:auto">


                                <button class="btn btn-outline-secondary dropdown-toggle dropstart z-3 " type="button"
                                    data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="true">
                                    @if (request()->get('billing_date_from') ||
                                            request()->get('billing_date_to') ||
                                            request()->get('due_date_from') ||
                                            request()->get('due_date_to') ||
                                            request()->get('bills'))
                                        <span
                                            class="red-toggle position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                        </span>
                                    @endif
                                </button>
                                <div class="dropdown-menu p-0 " style="width:350px">
                                    <div class="card border-0">
                                        <div class="card-header text-bg-primary">
                                            Advance Search
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label d-block">Billing Date</label>
                                                <div class="d-flex gap-2">
                                                    <div>
                                                        <input type="search" class="form-control date-picker-default"
                                                            placeholder="From" name="billing_date_from"
                                                            value="{{ request()->get('billing_date_from') }}">
                                                    </div>
                                                    <div>
                                                        <input type="search" class="form-control date-picker-default"
                                                            placeholder="To" name="billing_date_to"
                                                            value="{{ request()->get('billing_date_to') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label d-block">Due Date</label>
                                                <div class="d-flex gap-2">
                                                    <div>
                                                        <input type="search" class="form-control date-picker-default"
                                                            placeholder="From" name="due_date_from"
                                                            value="{{ request()->get('due_date_from') }}">
                                                    </div>
                                                    <div>
                                                        <input type="search" class="form-control date-picker-default"
                                                            placeholder="To" name="due_date_to"
                                                            value="{{ request()->get('due_date_to') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="mb-3">
                                                <label class="form-label d-block">Deposit Date</label>
                                                <div class="d-flex gap-2">
                                                    <div>
                                                        <input type="search" class="form-control date-picker-default"
                                                            placeholder="From" name="deposit_from"
                                                            value="{{ request()->get('deposit_from') }}">
                                                    </div>
                                                    <div>
                                                        <input type="search" class="form-control date-picker-default"
                                                            placeholder="To" name="deposit_to"
                                                            value="{{ request()->get('deposit_to') }}">
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="mb-3">
                                                <div class="btn-group mb-1 w-100" role="group">
                                                    <input type="checkbox" name="bills[]" value="overDue"
                                                        class="btn-check reverse" id="btn-over-due-outlined"
                                                        @if (is_array(request()->get('bills')) && in_array('overDue', request()->get('bills'))) checked @endif autocomplete="off">
                                                    <label class="btn btn-outline-danger w-50"
                                                        for="btn-over-due-outlined">Over
                                                        Due</label>
                                                    <input type="checkbox" name="bills[]" value="dueToday"
                                                        class="btn-check reverse" id="btn-due-today-outlined"
                                                        @if (is_array(request()->get('bills')) && in_array('dueToday', request()->get('bills'))) checked @endif autocomplete="off">
                                                    <label class="btn btn-outline-primary w-50"
                                                        for="btn-due-today-outlined">Due
                                                        Today</label>
                                                </div>
                                                <div class="btn-group w-100" role="group">
                                                    <input type="checkbox" name="bills[]" value="upcoming"
                                                        class="btn-check reverse" id="btn-upcoming-outlined"
                                                        @if (is_array(request()->get('bills')) && in_array('upcoming', request()->get('bills'))) checked @endif autocomplete="off">
                                                    <label class="btn btn-outline-dark w-50"
                                                        for="btn-upcoming-outlined">Upcoming</label>
                                                    <input type="checkbox" name="bills[]" value="paid"
                                                        class="btn-check reverse" id="btn-paid-outlined"
                                                        @if (is_array(request()->get('bills')) && in_array('paid', request()->get('bills'))) checked @endif autocomplete="off">
                                                    <label class="btn btn-outline-success w-50"
                                                        for="btn-paid-outlined">Paid</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit" aria-expanded="false"><i
                                        class="bi bi-search"></i> Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </caption>
        </table>
        <div id="billingsTable">
            @include('billing.partials.billings-table')
        </div>
        {{ $bills->appends(request()->query())->links() }}
    </div>
    <x-billing.edit-billing-modal />
    <x-billing.delete-billing-modal :refresh="true" />

    <x-billing-deposit.view-billing-deposits-modal />
    <x-billing-deposit.add-billing-deposit-modal />
    <x-billing-deposit.edit-billing-deposit-modal :refresh="true" />
    <x-billing-deposit.delete-billing-deposit-modal :refresh="true" />
    <script>
        $(document).on('click', '#billingDepositsTable > .id-con .pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var billingId = $(this).closest('.id-con').data('billing-id');
            window.fetchAjaxTable('billing-deposits', '#billingDepositsTable', page, false,
                billingId);
        });
    </script>
@endsection
