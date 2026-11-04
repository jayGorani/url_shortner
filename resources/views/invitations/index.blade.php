@extends('layouts.app')

@section('title', 'Invitations')

@section('content')


<div class="mb-4">

    <div class="text-uppercase text-primary fw-semibold small mb-2">Access management</div>

    <h1 class="h2 fw-bold mb-1">Invitations</h1>
    <p class="text-secondary mb-0">Invite people to your workspace and manage outstanding invitations.</p>

</div>

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body p-4">

        <div class="d-flex align-items-center gap-3 mb-4">

            <span class="rounded-3 bg-primary-subtle text-primary p-3"><i class="bi bi-envelope-plus fs-4"></i></span>

            <div>
                <h2 class="h5 fw-bold mb-1">Send an invitation</h2>
                <p class="text-secondary small mb-0">The recipient will receive an email with a secure acceptance link.</p>
            </div>

        </div>

        <form method="POST" action="{{ route('invitations.store') }}" class="row g-3">

            @csrf

            <div class="col-md-4">

                <label class="form-label fw-semibold" for="invite_name">Full name</label>

                <input id="invite_name" name="name" class="form-control" placeholder="e.g. Alex Morgan" value="{{ old('name') }}" required>

            </div>

            <div class="col-md-4">

                <label class="form-label fw-semibold" for="invite_email">Email address</label>

                <input id="invite_email" type="email" name="email" class="form-control" placeholder="alex@company.com" value="{{ old('email') }}" required>

            </div>

            <div class="col-md-2">

                <label class="form-label fw-semibold" for="invite_role">Role</label>

                <select id="invite_role" name="role" class="form-select"><option value="admin" @selected(old('role') === 'admin')>Admin</option>

                    @if(auth()->user()->isAdmin())
                        <option value="member" @selected(old('role') === 'member')>Member</option>
                    @endif
                </select>

            </div>

            @if(auth()->user()->isSuperAdmin())

                <div class="col-md-2">

                    <label class="form-label fw-semibold" for="company_name">Company</label>

                    <input id="company_name" name="company_name" class="form-control" placeholder="Company name" value="{{ old('company_name') }}" required>

                </div>

            @endif


            <div class="col-12">
                <button class="btn btn-primary">
                    <i class="bi bi-send me-2"></i>Send invitation
                </button>
            </div>

        </form>

    </div>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 p-4">

        <h2 class="h5 fw-bold mb-1">Invitation history</h2>

        <p class="text-secondary small mb-0">Review status, resend pending invitations or revoke access links.</p>

    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>
                    <th class="ps-4">Recipient</th>
                    <th>Role</th>
                    <th>Expires</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>

            </thead>

            <tbody>

                @forelse($invitations as $invitation)
                    @php($status = $invitation->accepted_at ? 'accepted' : ($invitation->expires_at->isPast() ? 'expired' : 'pending'))
                        <tr>

                            <td class="ps-4">
                                <div class="fw-semibold">{{ $invitation->name }}</div>
                                <div class="small text-secondary">{{ $invitation->email }}</div>
                            </td>

                            <td>
                                <span class="badge text-bg-light border text-secondary">{{ ucfirst($invitation->role) }}</span>
                            </td>


                            <td class="small text-secondary text-nowrap">
                                {{ $invitation->expires_at->format('d M Y, H:i') }}
                            </td>

                            <td>
                                @if($status === 'accepted')
                                    <span class="badge rounded-pill text-bg-success-subtle text-success">
                                        <i class="bi bi-check-circle me-1"></i>Accepted
                                    </span>
                                @elseif($status === 'expired')
                                    <span class="badge rounded-pill text-bg-warning-subtle text-warning-emphasis">
                                        <i class="bi bi-clock me-1"></i>Expired
                                    </span>
                                @else
                                    <span class="badge rounded-pill text-bg-primary-subtle text-primary">
                                        <i class="bi bi-hourglass-split me-1"></i>Pending
                                    </span>
                                @endif
                            </td>

                            <td class="text-end pe-4">

                                @if(!$invitation->accepted_at)

                                    <div class="d-inline-flex gap-2">

                                        <form method="POST" action="{{ route('invitations.resend',$invitation) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-repeat me-1"></i>Resend
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('invitations.revoke',$invitation) }}" onsubmit="return confirm('Revoke this invitation?')">
                                            @csrf @method('DELETE')

                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle me-1"></i>Revoke </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-secondary small">No actions available</span>
                                @endif

                            </td>

                        </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-envelope-open display-5 text-secondary"></i>
                                    <div class="fw-semibold mt-2">No invitations yet</div>
                                    <div class="small text-secondary">Invitations you send will appear here.</div>
                                </td>
                            </tr>
                        @endforelse

                </tbody>

            </table>

        </div>
    </div>

    <div class="mt-3">{{ $invitations->links('pagination::bootstrap-5') }}</div>

@endsection
