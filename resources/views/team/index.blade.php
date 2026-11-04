@extends('layouts.app')

@section('title', 'Company team')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

    <div>
        <div class="text-uppercase text-primary fw-semibold small mb-2">Workspace management</div>
        <h1 class="h2 fw-bold mb-1">Company team</h1>
        <p class="text-secondary mb-0">People who currently belong to your company workspace.</p>
    </div>

    <a href="{{ route('invitations.index') }}" class="btn btn-primary"><i class="bi bi-person-plus me-2"></i>Invite teammate</a>

</div>
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 p-4">
        <h2 class="h5 fw-bold mb-1">Team members</h2>
        <p class="text-secondary small mb-0">{{ $users->total() }} {{ Str::plural('member', $users->total()) }} in this workspace</p>
    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

           <thead class="table-light">
                <tr>
                    <th class="ps-4">Member</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)

                    <tr>

                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-circle bg-primary-subtle text-primary fw-bold d-inline-flex align-items-center justify-content-center">
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </span>
                                <span class="fw-semibold">{{ $user->name }}</span>
                            </div>
                        </td>

                        <td class="text-secondary">{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->role->value === 'admin' ? 'text-bg-primary' : 'text-bg-light border text-secondary' }}">{{ ucfirst($user->role->value) }}</span>
                        </td>
                        <td class="text-secondary small">{{ $user->created_at->format('d M Y') }}</td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-secondary">No team members found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $users->links('pagination::bootstrap-5') }}</div>
@endsection
