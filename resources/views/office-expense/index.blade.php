@extends('layout.app')
@section('title')
    Office Expenses
@endsection
@section('main')
    <div class="container h-100 py-4">


        <table class="table table-bordered">
            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex gap-4">
                        <h2 class="me-2">Office Expenses</h2>
                        <div>
                            <button class="btn btn-primary"
                                data-header="Office Expenses from {{ formattedDate($minExpenseDate) }} to {{ formattedDate($maxExpenseDate) }}"
                                data-route="{{ request()->fullUrlWithQuery(['pdf' => 'view']) }}"
                                data-file-name="Office-Expenses-{{ formattedDate($minExpenseDate) }}-{{ formattedDate($maxExpenseDate) }}.pdf"
                                data-bs-toggle="modal" data-bs-target="#pdfViewModal"><i class="bi bi-file-earmark-pdf"></i>
                                Export PDF
                            </button>
                        </div>
                    </div>
                    <a href="{{ route('officeExpense.create') }}" class="btn btn-success ">Create New Office Expenses</a>
                </div>
            </caption>

            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        @if (request()->get('type') || request()->get('description') || request()->get('date_from') || request()->get('date_to'))
                            <a href="{{ route('officeExpense') }}" class="btn btn-link text-danger p-0">
                                Clear Search
                            </a>
                        @endif
                    </div>
                    <div class=" w-75 ">
                        <form class="mb-0">
                            <div class="input-group dropstart">
                                <div class="form-control p-0 border-0">
                                    <input type="search" class="form-control custom-datalist-input"
                                        style="border-top-right-radius:0;border-bottom-right-radius:0" placeholder="Type"
                                        name="type" value="{{ request()->get('type') }}"
                                        data-datalist="{{ $types->toJson() }}">
                                </div>
                                <input type="search" class="form-control w-25" placeholder="Description" name="description"
                                    value="{{ request()->get('description') }}">
                                <button class="btn btn-outline-secondary dropdown-toggle dropstart z-3" type="button"
                                    data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false">
                                    @if (request()->get('date_from') || request()->get('date_to'))
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
                                                <label class="form-label d-block">Expense Date</label>
                                                <div class="d-flex gap-2">
                                                    <div>
                                                        <input type="text"
                                                            class="form-control date-picker-default flatpickr-input"
                                                            placeholder="From" name="date_from"
                                                            value="{{ request('date_from') }}">
                                                    </div>
                                                    <div>
                                                        <input type="text"
                                                            class="form-control date-picker-default flatpickr-input"
                                                            placeholder="To" name="date_to"
                                                            value="{{ request('date_to') }}">
                                                    </div>
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
        <div>
            @include('office-expense.partials.office-expense-table')
        </div>
        {{ $officeExpenses->appends(request()->query())->links() }}
    </div>
    @can('delete', getPermissionClass('Office Expenses'))
        <x-office-expense.delete-office-expense-modal />
    @endcan
@endsection
