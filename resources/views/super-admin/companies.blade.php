@extends('layouts.app')

@section('title', 'Companies')

@section('content')

<div class="mb-4">
    <div class="text-uppercase text-primary fw-semibold small mb-2">
        Platform directory
    </div>
    <h1 class="h2 fw-bold mb-1">Companies</h1>
    <p class="text-secondary mb-0">A consolidated view of all registered company workspaces.</p>
</div>

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 p-4">
        <h2 class="h5 fw-bold mb-1">Registered companies</h2>
        <p class="text-secondary small mb-0">{{ $companies->total() }} {{ Str::plural('company', $companies->total()) }} on the platform</p>
    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>
                    <th class="ps-4">Company</th>
                    <th>Status</th>
                    <th>Users</th>
                    <th>Short URLs</th>
                    <th>Created</th>
                </tr>

            </thead>

            <tbody>

                @forelse($companies as $company)
                    <tr>

                        <td class="ps-4">

                            <div class="d-flex align-items-center gap-3">

                                <span class="rounded-3 bg-primary-subtle text-primary p-2">
                                    <i class="bi bi-buildings fs-5"></i>
                                </span>

                                <span class="fw-semibold">{{ $company->name }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="badge rounded-pill {{ strtolower($company->status) === 'active' ? 'text-bg-success-subtle text-success' : 'text-bg-secondary-subtle text-secondary' }}">
                                {{ ucfirst($company->status) }}
                            </span>
                        </td>

                        <td>
                            {{ number_format($company->users_count) }}
                        </td>

                        <td>{{ number_format($company->short_urls_count) }}</td>

                        <td class="text-secondary small">{{ $company->created_at->format('d M Y') }}</td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-secondary">No companies found.</td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-3">{{ $companies->links('pagination::bootstrap-5') }}</div>

@endsection
