@extends('layout.app')
@section('title')
    Associate
@endsection
@section('main')
    <div class="container p-4">
        <form action="{{ route('associate') }}" method="POST" class="needs-validation card  mx-auto" id="caseForm" novalidate>
            @csrf
            <div class="card-header text-bg-primary">
                <h5 class="mb-0">Associate</h5>
            </div>
            <div class="container card-body">
                <div class="row mb-4">
                    <div class="col-5">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            autocomplete="off" value="{{ old('name') }}" required>
                        <div class="invalid-feedback ">
                            @if ($errors->has('name'))
                                @error('name')
                                    {{ $message }}
                                @enderror
                            @else
                                Name is Required.
                            @endif
                        </div>
                    </div>

                </div>
                <div class="row mb-4">
                    <div class="col-4">
                        <x-contact.create />
                    </div>
                    <div class="col-8">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control  @error('address') is-invalid @enderror" autocomplete="off">{{ old('address') }}</textarea>
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
@endsection
