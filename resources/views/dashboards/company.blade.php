@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

    <div>
        <div class="text-uppercase text-primary fw-semibold small mb-2">Workspace overview</div>
        <h1 class="h2 fw-bold mb-1">{{ $company?->name ?? 'Your workspace' }}</h1>
        <p class="text-secondary mb-0">Here is what's happening with your links today.</p>
    </div>

    @if($user->can('create', App\Models\ShortUrl::class))
        <a class="btn btn-primary btn-lg" href="{{ route('short-urls.create') }}"><i class="bi bi-plus-lg me-2"></i>Create short URL</a>
    @endif
</div>


<div class="row g-3 mb-4">

    <div class="col-sm-6 col-xl-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary fw-semibold small">{{ $user->isAdmin() ? 'TEAM MEMBERS' : 'YOUR SHORT LINKS' }}</span>
                    <span class="rounded-3 bg-primary-subtle text-primary p-2"><i class="bi bi-people fs-5"></i></span>
                </div>

                <div class="display-5 fw-bold mt-3">{{ number_format($user->isAdmin() ? $usersCount : $urlsCount) }}</div>

                <div class="small text-secondary mt-1">{{ $user->isAdmin() ? 'People in your company' : 'Links created in your workspace' }}</div>

            </div>

        </div>

    </div>

    <div class="col-sm-6 col-xl-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary fw-semibold small">SHORT URLS</span>
                    <span class="rounded-3 bg-info-subtle text-info p-2"><i class="bi bi-link-45deg fs-5"></i></span>
                </div>

                <div class="display-5 fw-bold mt-3">{{ number_format($urlsCount) }}</div>

                <div class="small text-secondary mt-1">{{ $user->isMember() ? 'Links created by you' : 'Links across your company' }}</div>

            </div>

        </div>

    </div>


    <div class="col-sm-6 col-xl-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary fw-semibold small">TOTAL CLICKS</span>
                    <span class="rounded-3 bg-success-subtle text-success p-2"><i class="bi bi-graph-up-arrow fs-5"></i></span>
                </div>

                <div class="display-5 fw-bold mt-3">{{ number_format($clicks) }}</div>

                <div class="small text-secondary mt-1">Tracked visits to your short links</div>

            </div>

        </div>

    </div>

</div>


<div class="row g-3">

    <div class="col-xl-7">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body p-4">

                <div class="d-flex align-items-center gap-3 mb-3">

                    <span class="rounded-3 bg-primary-subtle text-primary p-3"><i class="bi bi-stars fs-4"></i></span>

                    <div>
                        <h2 class="h5 fw-bold mb-1">Get started with {{ config('app.name') }}</h2><p class="text-secondary small mb-0">Create, share and track short links from one workspace.</p>
                    </div>

                </div>

                <ol class="list-group list-group-numbered list-group-flush">

                    <li class="list-group-item px-0 py-3">Create a short URL from any valid HTTP or HTTPS destination.</li>

                    <li class="list-group-item px-0 py-3">Share the generated link with your audience.</li>

                    <li class="list-group-item px-0 py-3">Monitor click counts from your URL list.</li>

                </ol>

            </div>

        </div>

    </div>

    <div class="col-xl-5">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body p-4">

                <h2 class="h5 fw-bold mb-3">Quick actions</h2>

                <div class="d-grid gap-2">

                    <a class="btn btn-outline-primary text-start py-3" href="{{ route('short-urls.index') }}">
                        <i class="bi bi-link-45deg me-2"></i>Browse short URLs <i class="bi bi-arrow-right float-end"></i>
                    </a>

                    @if($user->isAdmin())

                        <a class="btn btn-outline-primary text-start py-3" href="{{ route('team.index') }}">
                            <i class="bi bi-people me-2"></i>Manage company team <i class="bi bi-arrow-right float-end"></i>
                        </a>

                        <a class="btn btn-outline-primary text-start py-3" href="{{ route('invitations.index') }}">
                            <i class="bi bi-envelope-plus me-2"></i>Invite a teammate <i class="bi bi-arrow-right float-end"></i>
                        </a>

                    @endif

                </div>

            </div>

        </div>

    </div>


</div>

@endsection
