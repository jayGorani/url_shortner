@extends('layouts.app')

@section('title', 'All users')

@section('content')

<div class="mb-4">
    <div class="text-uppercase text-primary fw-semibold small mb-2">Platform directory</div>
    <h1 class="h2 fw-bold mb-1">All users</h1>
    <p class="text-secondary mb-0">Search and review user accounts across every company.</p>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('super-admin.users') }}" class="row g-2">

            <div class="col-md-7 col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-body-tertiary">
                        <i class="bi bi-search text-secondary"></i>\
                    </span>
                    <input name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by name or email">
                </div>
            </div>

            <div class="col-auto">
                <button class="btn btn-outline-primary">
                    <i class="bi bi-search me-1"></i>Search
                </button>
            </div>

            @if(request('search'))
                <div class="col-auto">
                    <a href="{{ route('super-admin.users') }}" class="btn btn-light">Clear</a>
                </div>
            @endif
        </form>

    </div>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 p-4">
        <h2 class="h5 fw-bold mb-1">Platform users</h2>
        <p class="text-secondary small mb-0">
            {{ $users->total() }} {{ Str::plural('account', $users->total()) }} found
        </p>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Company</th>
                    <th>Created</th>
                </tr>
            </thead>

            <tbody>

                @forelse($users as $user)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $user->name }}</td>
                        <td class="text-secondary">{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->role->value === 'super_admin' ? 'text-bg-dark' : ($user->role->value === 'admin' ? 'text-bg-primary' : 'text-bg-light border text-secondary') }}">{{ ucfirst(str_replace('_',' ',$user->role->value)) }}</span>
                        </td>
                        <td>{{ $user->company?->name ?? '—' }}</td>
                        <td class="text-secondary small">{{ $user->created_at->format('d M Y') }}</td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center py-5 text-secondary">No users found.</td>
                    </tr>

                @endforelse

            </tbody>
        </table>

    </div>

</div>

<div class="mt-3">{{ $users->links('pagination::bootstrap-5') }}</div>

@endsection
