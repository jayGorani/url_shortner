@extends('layouts.app')

@section('title', 'Accept invitation')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-8 col-lg-6 col-xl-5">

        <div class="text-center mb-4">

            <span class="rounded-circle bg-primary-subtle text-primary d-inline-flex p-3 mb-3"><i class="bi bi-envelope-check fs-2"></i></span>

            <h1 class="h2 fw-bold mb-2">You're invited!</h1>

            <p class="text-secondary">Complete your account setup to join the workspace.</p>

        </div>

        <div class="card border-0 shadow-lg">

            <div class="card-body p-4 p-lg-5">

                <div class="rounded-3 bg-body-tertiary p-3 mb-4">

                    <div class="small text-secondary mb-1">Workspace invitation</div>

                    <div class="fw-bold fs-5">{{ $invitation->company_name ?: $invitation->company?->name }}</div>

                    <div class="small text-secondary mt-2">
                        You have been invited with the
                        <span class="badge text-bg-primary">{{ ucfirst($invitation->role) }}</span> role.
                    </div>

                </div>

                <form method="POST" action="{{ route('invitations.accept') }}">

                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">

                        <label class="form-label fw-semibold" for="invite_accept_name">Full name</label>

                        <input id="invite_accept_name" class="form-control" value="{{ $invitation->name }}" disabled>

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-semibold" for="invite_accept_email">Email address</label>

                        <input id="invite_accept_email" class="form-control" value="{{ $invitation->email }}" disabled>

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-semibold" for="password">Create password</label>

                        <input id="password" type="password" name="password" class="form-control" required minlength="10" autocomplete="new-password">

                        <div class="form-text">Use at least 10 characters.</div>

                    </div>

                    <div class="mb-4">

                        <label class="form-label fw-semibold" for="password_confirmation">Confirm password</label>

                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required minlength="10" autocomplete="new-password">

                    </div>

                    <button class="btn btn-primary btn-lg w-100"><i class="bi bi-person-check me-2"></i>Accept invitation & create account</button>

                </form>

            </div>

        </div>

        <p class="text-center text-secondary small mt-3"><i class="bi bi-shield-lock me-1"></i>This invitation link is private and can only be used once.</p>

    </div>

</div>

@endsection
