@extends('layout.app')
@section('title')
    Court Branch
@endsection
@section('main')
    <div class="container p-4">
        <form action="{{ route('courtBranch') }}" method="POST" class="needs-validation card  mx-auto" novalidate>
            @csrf
            <div class="card-header text-bg-primary">
                <h5 class="mb-0">Court Branch</h5>
            </div>
            <div class="container card-body">
                <div class="row mb-4">

                    <div class="col-4">
                        <label class="form-label">Region/Province <span class="text-danger">*</span></label>
                        <div class="custom-datalist-container">
                            <input type="text"
                                class="form-control custom-datalist-input @error('region') is-invalid @enderror"
                                value="{{ old('region') }}" name="region" required autocomplete="off"
                                data-datalist="{{ $regions->toJson() }}">
                            <div class="invalid-feedback ">
                                @if ($errors->has('region'))
                                    @error('region')
                                        {{ $message }}
                                    @enderror
                                @else
                                    Region/Province is Required.
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="form-label">City/Municpality <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                            value="{{ old('city') }}" name="city" required autocomplete="off">
                        <div class="invalid-feedback ">
                            @if ($errors->has('city'))
                                @error('city')
                                    {{ $message }}
                                @enderror
                            @else
                                City/Municipality is Required.
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Court Type <span class="text-danger">*</span></label>
                        <div class="custom-datalist-container">
                            <input type="text"
                                class="form-control custom-datalist-input @error('type') is-invalid @enderror"
                                value="{{ old('type') }}" name="type" required autocomplete="off"
                                data-datalist="{{ $courtTypes->toJson() }}">
                            <div class="invalid-feedback ">
                                @if ($errors->has('type'))
                                    @error('type')
                                        {{ $message }}
                                    @enderror
                                @else
                                    Court Type is Required.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-2">
                        <label class="form-label ">Branch</label>
                        <input type="text" class="form-control @error('branch') is-invalid @enderror"
                            value="{{ old('branch') }}" name="branch" autocomplete="off">
                        <div class="invalid-feedback ">
                            @if ($errors->has('branch'))
                                @error('branch')
                                    {{ $message }}
                                @enderror
                            @endif
                        </div>
                    </div>
                    <div class="col-10">
                        <label class="form-label">Judge</label>
                        <input type="text" class="form-control @error('judge') is-invalid @enderror"
                            value="{{ old('judge') }}" name="judge" autocomplete="off">
                        <div class="invalid-feedback ">
                            @if ($errors->has('judge'))
                                @error('branch')
                                    {{ $message }}
                                @enderror
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
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" autocomplete="off">{{ old('address') }}</textarea>
                        <div class="invalid-feedback ">
                            @if ($errors->has('address'))
                                @error('address')
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
