@extends('layouts.app')

@section('title', 'Short URLs')

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <div class="text-uppercase text-primary fw-semibold small mb-2">
                Link management
            </div>

            <h1 class="h2 fw-bold mb-1">Short URLs</h1>

            <p class="text-secondary mb-0">
                Manage your shortened links and monitor their performance.
            </p>
        </div>

        @if (auth()->user()->can('create', App\Models\ShortUrl::class))
            <a class="btn btn-primary" href="{{ route('short-urls.create') }}">
                <i class="bi bi-plus-lg me-2"></i>
                Create URL
            </a>
        @endif
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('short-urls.index') }}" class="row g-2">
                <div class="col-md-7 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-body-tertiary">
                            <i class="bi bi-search text-secondary"></i>
                        </span>

                        <input
                            type="text"
                            class="form-control"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by destination or short code"
                        >
                    </div>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search me-1"></i>
                        Search
                    </button>
                </div>

                @if (request('search'))
                    <div class="col-auto">
                        <a href="{{ route('short-urls.index') }}" class="btn btn-light">
                            Clear
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h5 fw-bold mb-1">Your links</h2>

                <p class="text-secondary small mb-0">
                    {{ $urls->total() }} {{ Str::plural('link', $urls->total()) }} found
                </p>
            </div>

            <span class="badge rounded-pill text-bg-light border text-secondary">
                <i class="bi bi-activity me-1"></i>
                Click tracking
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Destination</th>
                        <th>Short link</th>

                        @if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                            <th>Created by</th>
                        @endif

                        @if (auth()->user()->isSuperAdmin())
                            <th>Company</th>
                        @endif

                        <th>Clicks</th>
                        <th>Created</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($urls as $url)
                        <tr>
                            <td class="ps-4 text-secondary">
                                {{ $urls->firstItem() + $loop->index }}
                            </td>

                            {{-- Destination --}}
                            <td class="text-break">
                                <a
                                    class="text-decoration-none fw-medium"
                                    href="{{ $url->original_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    {{ Str::limit($url->original_url, 58) }}
                                </a>

                                <div class="small text-secondary">
                                    Original destination
                                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </div>
                            </td>

                            {{-- Short Link --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div>
                                        <a
                                            class="text-decoration-none fw-semibold"
                                            href="{{ route('short.redirect', $url->short_code) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            {{ route('short.redirect', $url->short_code) }}
                                        </a>

                                        <div class="small text-secondary">
                                            /{{ $url->short_code }}
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-secondary text-nowrap"
                                        data-copy="{{ route('short.redirect', $url->short_code) }}"
                                    >
                                        <i class="bi bi-copy me-1"></i>
                                        Copy
                                    </button>
                                </div>
                            </td>

                            {{-- Created By --}}
                            @if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                                <td>
                                    {{ $url->user->name }}
                                </td>
                            @endif

                            {{-- Company --}}
                            @if (auth()->user()->isSuperAdmin())
                                <td>
                                    {{ $url->company->name }}
                                </td>
                            @endif

                            {{-- Clicks --}}
                            <td>
                                <span class="badge text-bg-success-subtle text-success rounded-pill">
                                    <i class="bi bi-cursor me-1"></i>
                                    {{ number_format($url->click_count) }}
                                </span>
                            </td>

                            {{-- Created Date --}}
                            <td class="text-secondary small text-nowrap">
                                {{ $url->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-secondary mb-2">
                                    <i class="bi bi-link-45deg display-5"></i>
                                </div>

                                <div class="fw-semibold">
                                    No short URLs found
                                </div>

                                <div class="small text-secondary mb-3">
                                    Create your first short link to get started.
                                </div>

                                @if (auth()->user()->can('create', App\Models\ShortUrl::class))
                                    <a
                                        class="btn btn-primary btn-sm"
                                        href="{{ route('short-urls.create') }}"
                                    >
                                        Create your first URL
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $urls->links('pagination::bootstrap-5') }}
    </div>

@endsection