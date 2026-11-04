<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

</head>

<body class="bg-body-tertiary">

@auth

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">

        <div class="container-fluid px-3 px-lg-4">

            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <span class="bg-primary rounded-3 px-2 py-1"><i class="bi bi-link-45deg"></i></span> {{ config('app.name') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavigation" aria-controls="topNavigation" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="topNavigation">

                <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                    <span class="navbar-text text-white-50 small me-lg-2"><i class="bi bi-building me-1"></i>{{ auth()->user()->company?->name ?? 'Platform administration' }}</span>

                    <div class="dropdown">

                        <button class="btn btn-dark dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            <span class="text-start"><span class="d-block small fw-semibold text-white">{{ auth()->user()->name }}</span><span class="d-block text-white-50 small">{{ str_replace('_', ' ', ucfirst(auth()->user()->role->value)) }}</span></span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <span class="dropdown-item-text small text-secondary">{{ auth()->user()->email }}</span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Sign out</button>
                                </form>
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </nav>

    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="row g-4">

            <aside class="col-lg-2 col-xl-2">

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-3">
                        <div class="text-uppercase text-secondary fw-semibold small px-2 mb-2">Workspace</div>

                        <nav class="nav nav-pills flex-column gap-1">

                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : 'text-body' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2 me-2"></i>Overview</a>

                            <a class="nav-link {{ request()->routeIs('short-urls.*') ? 'active' : 'text-body' }}" href="{{ route('short-urls.index') }}"><i class="bi bi-link-45deg me-2"></i>Short URLs</a>

                            @if(auth()->user()->can('create',App\Models\ShortUrl::class))

                                <a class="nav-link {{ request()->routeIs('short-urls.create') ? 'active' : 'text-body' }}" href="{{ route('short-urls.create') }}"><i class="bi bi-plus-circle me-2"></i>Create link</a>

                            @endif

                            @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())

                                <div class="text-uppercase text-secondary fw-semibold small px-2 mt-4 mb-2">Management</div>
                                <a class="nav-link {{ request()->routeIs('invitations.*') ? 'active' : 'text-body' }}" href="{{ route('invitations.index') }}"><i class="bi bi-envelope-paper me-2"></i>Invitations
                                </a>
                            @endif

                            @if(auth()->user()->isAdmin())
                                <a class="nav-link {{ request()->routeIs('team.*') ? 'active' : 'text-body' }}" href="{{ route('team.index') }}"><i class="bi bi-people me-2"></i>Team</a>
                            @endif

                            @if(auth()->user()->isSuperAdmin())

                                <div class="text-uppercase text-secondary fw-semibold small px-2 mt-4 mb-2">Platform</div>

                                <a class="nav-link {{ request()->routeIs('super-admin.companies') ? 'active' : 'text-body' }}" href="{{ route('super-admin.companies') }}"><i class="bi bi-buildings me-2"></i>Companies</a>

                                <a class="nav-link {{ request()->routeIs('super-admin.users') ? 'active' : 'text-body' }}" href="{{ route('super-admin.users') }}"><i class="bi bi-person-lines-fill me-2"></i>All users</a>

                            @endif
                        </nav>

                        <hr class="my-4">

                        <div class="rounded-3 bg-body-tertiary p-3">

                            <div class="small fw-semibold mb-1"><i class="bi bi-shield-check text-success me-1"></i>Workspace secure
                            </div>

                            <div class="small text-secondary">Your links and team are managed in one place.</div>

                        </div>
                    </div>

                </div>

            </aside>

            <main class="col-lg-10 col-xl-10">

                @include('partials.flash')

                @yield('content')

                <footer class="border-top mt-5 pt-3 text-secondary small d-flex justify-content-between flex-wrap gap-2">
                    <span>© {{ date('Y') }} {{ config('app.name') }}</span><span>Simple, secure link management</span>

                </footer>
            </main>

        </div>

    </div>

@else

    <main class="container py-5">
        <div class="text-center mb-4">
            <a class="text-decoration-none text-dark fw-bold fs-3" href="{{ route('login') }}"><i class="bi bi-link-45deg text-primary me-1"></i>{{ config('app.name') }}</a>
            <p class="text-secondary small mb-0">Simple, secure link management</p>
        </div>

        @include('partials.flash')
        @yield('content')

    </main>

@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

document.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-copy]');
    if (!button) return;
    try {
        await navigator.clipboard.writeText(button.dataset.copy);
        const original = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check2 me-1"></i>Copied';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');
        window.setTimeout(() => {
            button.innerHTML = original;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 1400);
    } catch (error) {
        button.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>Copy failed';
    }
});

</script>
@stack('scripts')

</body>
</html>
