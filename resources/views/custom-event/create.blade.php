@extends('layout.app')
@section('title')
    Custom Event
@endsection
@section('main')
    <div class="container p-4">
        <form action="{{ route('customEvent') }}" method="POST" class="needs-validation card  mx-auto" novalidate>
            @csrf
            <div class="card-header text-bg-primary">
                <h5 class="mb-0">Custom Event</h5>
            </div>
            <div class="container card-body">
                <div class="row mb-4">
                    <div class="col-8">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control   @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" name="title" required autocomplete="off">
                        <div class="invalid-feedback ">
                            @if ($errors->has('title'))
                                @error('title')
                                    {{ $message }}
                                @enderror
                            @else
                                Title is Required.
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <div class="custom-datalist-container">
                            <input type="text" name="type"
                                class="form-control  custom-datalist-input @error('type') is-invalid @enderror"
                                data-datalist="{{ $types->toJson() }}" value="{{ old('type') }}" required
                                autocomplete="off">
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
                </div>
                <div class="row mb-4">
                    <div class="col-4">
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label">From <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control date-time-picker-default  @error('start_time') is-invalid @enderror"
                                    value="{{ old('start_time') }}" name="start_time" required autocomplete="off">
                                <div class="invalid-feedback ">
                                    @if ($errors->has('start_time'))
                                        @error('start_time')
                                            {{ $message }}
                                        @enderror
                                    @else
                                        Date is Required.
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">To <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control date-time-picker-default  @error('end_time') is-invalid @enderror"
                                    value="{{ old('end_time') }}" name="end_time" required autocomplete="off">
                                <div class="invalid-feedback ">
                                    @if ($errors->has('end_time'))
                                        @error('end_time')
                                            {{ $message }}
                                        @enderror
                                    @else
                                        Date is Required.
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control   @error('location') is-invalid @enderror"
                                    value="{{ old('location') }}" name="location" autocomplete="off">
                                <div class="invalid-feedback ">
                                    @if ($errors->has('location'))
                                        @error('location')
                                            {{ $message }}
                                        @enderror
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <label class="form-label">Description</label>
                        <textarea class="form-control  @error('description') is-invalid @enderror" name="description" autocomplete="off"
                            style="height:130px">{{ old('description') }}</textarea>
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
