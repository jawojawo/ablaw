@extends('layout.app')
@section('title')
    Bill Deposits
@endsection
@section('main')
    <div class="container h-100 py-4">
        <table class="table table-bordered mb-0">
            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex gap-4">
                        <h2 class="me-2">Bill Deposits</h2>
                        <div>
                            <button class="btn btn-primary"
                                data-header="Deposits from {{ formattedDate($minDepositDate) }} to {{ formattedDate($maxDepositDate) }}"
                                data-route="{{ request()->fullUrlWithQuery(['pdf' => 'view']) }}"
                                data-file-name="deposits-{{ formattedDate($minDepositDate) }}-{{ formattedDate($maxDepositDate) }}.pdf"
                                data-bs-toggle="modal" data-bs-target="#pdfViewModal"><i class="bi bi-file-earmark-pdf"></i>
                                Export PDF
                            </button>
                        </div>
                    </div>

                </div>
            </caption>
            <caption class="caption-top">
                <div class="d-flex justify-content-between align-items-end ">
                    <div>
                        @if (request()->get('deposits_from') || request()->get('deposits_to'))
                            <a href="{{ route('billing-deposit') }}" class="btn btn-link text-danger p-0">
                                Clear Search
                            </a>
                        @endif
                    </div>
                    <div class=" w-50">
                        <form class="mb-0">
                            <div class="input-group dropstart ">
                                <input type="search" class="form-control date-picker-default" placeholder="Deposits from"
                                    name="deposits_from" value="{{ request()->get('deposits_from') }}">
                                <input type="search" class="form-control date-picker-default" placeholder="Deposits to"
                                    name="deposits_to" value="{{ request()->get('deposits_to') }}">

                                <button class="btn btn-primary" type="submit" aria-expanded="false"><i
                                        class="bi bi-search"></i> Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </caption>
        </table>
        @include('billing-deposit.partials.billing-deposits-table')
        {{ $deposits->appends(request()->query())->links() }}

    </div>
@endsection
