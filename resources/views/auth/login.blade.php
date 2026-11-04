@extends('layouts.app')

@section('title', 'Sign in')

@section('content')

<div class="row justify-content-center align-items-center">

    <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">

        <div class="text-center mb-4">
            <span class="badge rounded-pill text-bg-primary-subtle text-primary px-3 py-2 mb-3"><i class="bi bi-lightning-charge-fill me-1"></i>LINK MANAGEMENT PLATFORM</span>
            <h1 class="h2 fw-bold mb-2">Welcome back</h1>
            <p class="text-secondary">Sign in to manage your links and workspace.</p>
        </div>

        <div class="card border-0 shadow-lg">

            <div class="card-body p-4 p-lg-5">

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div class="mb-3">

                        <label class="form-label fw-semibold" for="email">Email address</label>

                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-envelope text-secondary"></i></span>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required autofocus autocomplete="username">
                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="password">Password</label>

                        <div class="input-group input-group-lg">

                            <span class="input-group-text bg-body-tertiary"><i class="bi bi-lock text-secondary"></i></span>

                            <input id="password" class="form-control" type="password" name="password" placeholder="Enter your password" required autocomplete="current-password">

                        </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div class="form-check"><input class="form-check-input" type="checkbox" name="remember" value="1" id="remember">
                            <label class="form-check-label small" for="remember">Keep me signed in</label>
                        </div>

                        <span class="small text-secondary"><i class="bi bi-shield-lock me-1"></i>Secure sign in</span>

                    </div>

                    <button class="btn btn-primary btn-lg w-100"><i class="bi bi-box-arrow-in-right me-2"></i>Sign in to {{ config('app.name') }}</button>

                </form>

            </div>

        </div>

        <p class="text-center text-secondary small mt-4">Access is provided by your workspace administrator.</p>
    </div>

</div>

@endsection
