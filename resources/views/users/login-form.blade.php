@extends('layouts.auth_app')
@section('content')
<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="card">

                    <div class="card-body p-4">

                        <div class="text-center w-75 m-auto">
                            <div class="auth-logo">
                                <span class="logo-lg">
                                    <img src="assets/images/logo-dark.png" alt="" height="22">
                                </span>
                            </div>
                        </div>

                        <form role="form" action="{{ route('login.store') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="mb-2">
                                <label for="email" class="form-label">Email address</label>
                                <input class="form-control" type="email" id="email" name="email" required="" placeholder="Enter your email">
                            </div>

                            <div class="mb-2">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" placeholder="Enter your password" name="password">
                                    
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkbox-signin" checked name="remember">
                                    <label class="form-check-label" for="checkbox-signin">
                                        Remember me
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid mb-0 text-center">
                                <button class="btn btn-primary" type="submit"> Log In </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection