@extends('layouts.app')

@section('title', 'Platform overview')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

    <div>

        <div class="text-uppercase text-primary fw-semibold small mb-2">Platform administration</div>

        <h1 class="h2 fw-bold mb-1">Platform overview</h1>

        <p class="text-secondary mb-0">Monitor companies, users and link activity across {{ config('app.name') }}.</p>

    </div>

    <a class="btn btn-primary" href="{{ route('super-admin.companies') }}">
        <i class="bi bi-buildings me-2"></i>View companies
    </a>

</div>

<div class="row g-3 mb-4">

    @foreach([['Companies',$companies,'bi-buildings','primary'],['Users',$users,'bi-people','info'],['Admins',$admins,'bi-person-gear','warning'],['Members',$members,'bi-person','secondary'],['Short URLs',$urls,'bi-link-45deg','success'],['Total clicks',$clicks,'bi-graph-up-arrow','danger']] as [$label,$value,$icon,$color])

        <div class="col-sm-6 col-xl-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <span class="text-secondary fw-semibold small">{{ strtoupper($label) }}</span>

                        <span class="rounded-3 bg-{{ $color }}-subtle text-{{ $color }} p-2"><i class="bi {{ $icon }} fs-5"></i></span>

                    </div>

                    <div class="display-5 fw-bold mt-3">{{ number_format($value) }}</div>

                    <div class="small text-secondary mt-1">Platform-wide total</div>
                </div>

            </div>

        </div>

    @endforeach

</div>


<div class="card border-0 shadow-sm">

    <div class="card-body p-4">

        <h2 class="h5 fw-bold mb-1">Administration shortcuts</h2>
        <p class="text-secondary small mb-3">Manage the core resources of your platform.</p>

        <div class="d-flex flex-wrap gap-2">

            <a class="btn btn-outline-primary" href="{{ route('super-admin.companies') }}"><i class="bi bi-buildings me-2"></i>Companies</a>

            <a class="btn btn-outline-primary" href="{{ route('super-admin.users') }}"><i class="bi bi-people me-2"></i>All users</a>

            <a class="btn btn-outline-primary" href="{{ route('invitations.index') }}"><i class="bi bi-envelope-paper me-2"></i>Invitations</a>

            <a class="btn btn-outline-primary" href="{{ route('short-urls.index') }}"><i class="bi bi-link-45deg me-2"></i>All short URLs</a>

        </div>

    </div>

</div>

@endsection
