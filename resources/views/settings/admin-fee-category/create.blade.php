@extends('layout.app')
@section('title')
    case
@endsection
@section('main')
    <div class="container ">


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('settings.adminFeeCategory') }}" method="POST" class="needs-validation card  " id="caseForm"
            novalidate>
            @csrf

            <div class="card-header">
                Admin Fee Category
            </div>
            <div class="container card-body">
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <label for="nameInp" class="form-label">Name</label>
                        <input type="text" class="form-control" id="nameInp" name="name" required>
                    </div>

                </div>
                <div class="row mb-4">

                    <div class="col-lg-12">
                        <label for="nameInp" class="form-label">Amount</label>
                        <x-input-money name="amount" value="0" />
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="justify-content-end d-flex">
                        <button class="btn btn-success px-4">Save</button>
                    </div>
                </div>
            </div>

        </form>
    </div>

@endsection
