@extends('layout.app')
@section('title')
    login
@endsection
@section('main')
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center ">
            <div style="width:40rem">
                <div class="card login-card p-0">

                    <div class="card-header text-bg-dark text-center">
                        <img class="w-50" src="{{ asset('img/invoice_logo.png') }}">

                    </div>
                    <div class="card-body">
                        @if (session('loginError'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('loginError') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        <form action="{{ route('login') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="usernameInp" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" id="usernameInp" required>
                                <div class="invalid-feedback">
                                    Username is required.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="passwordInp" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="passwordInp" required>
                                <div class="invalid-feedback">
                                    Password is required.
                                </div>
                            </div>

                            <div class="mb-3 d-grid d-md-flex justify-content-md-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rememberLogin" name="remember">
                                    <label class="form-check-label" for="rememberLogin">
                                        Remember Me
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary px-3">Login</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
