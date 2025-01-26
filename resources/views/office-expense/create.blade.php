@extends('layout.app')
@section('title')
    Office Expense
@endsection
@section('main')
    <div class="container p-4">
        <form action="{{ route('officeExpense') }}" method="POST" class="needs-validation card  mx-auto" novalidate>
            @csrf
            <div class="card-header text-bg-primary">
                <h5 class="mb-0">Office Expense</h5>
            </div>
            <div class="container card-body">
                <div class="row mb-4">

                    <div class="col-4">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <div class="custom-datalist-container">
                            <input type="text"
                                class="form-control custom-datalist-input  @error('type') is-invalid @enderror"
                                value="{{ old('type') }}" name="type" required autocomplete="off"
                                data-datalist="{{ $types->toJson() }}">
                            <div class="invalid-feedback ">
                                @if ($errors->has('type'))
                                    @error('type')
                                        {{ $message }}
                                    @enderror
                                @else
                                    Type is Required.
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <div class="input-group money-group-con">
                            <span class="input-group-text">₱</span>
                            <input type="text" name="amount"
                                class="form-control input-money @error('amount') is-invalid @enderror"
                                value="{{ old('amount') }}" required autocomplete="off">
                            <div class="invalid-feedback ">
                                @if ($errors->has('amount'))
                                    @error('amount')
                                        {{ $message }}
                                    @enderror
                                @else
                                    Amount is Required.
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control date-picker-default  @error('expense_date') is-invalid @enderror"
                            value="{{ old('expense_date') }}" name="expense_date" required autocomplete="off">
                        <div class="invalid-feedback ">
                            @if ($errors->has('expense_date'))
                                @error('expense_date')
                                    {{ $message }}
                                @enderror
                            @else
                                Date is Required.
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control  @error('description') is-invalid @enderror" name="description" autocomplete="off">{{ old('description') }}</textarea>
                        <div class="invalid-feedback ">
                            @if ($errors->has('description'))
                                @error('description')
                                    {{ $message }}
                                @enderror
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="justify-content-end d-flex">
                        <button class="btn btn-success px-4">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script></script>
@endsection
