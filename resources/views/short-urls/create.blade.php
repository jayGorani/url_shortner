@extends('layouts.app')

@section('title', 'Create short URL')

@section('content')

<div class="mb-4">

    <a href="{{ route('short-urls.index') }}" class="text-decoration-none small text-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to short URLs
    </a>

    <div class="text-uppercase text-primary fw-semibold small mt-3 mb-2">Link builder</div>

    <h1 class="h2 fw-bold mb-1">Create a short URL</h1>

    <p class="text-secondary mb-0">Turn a long destination into a clean, shareable link.</p>

</div>

<div class="row g-4">

    <div class="col-xl-8">

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4 p-lg-5">

                <div class="d-flex align-items-center gap-3 mb-4">

                    <span class="rounded-3 bg-primary-subtle text-primary p-3">
                        <i class="bi bi-link-45deg fs-3"></i>
                    </span>

                    <div>
                        <h2 class="h5 fw-bold mb-1">Destination details</h2>
                        <p class="text-secondary small mb-0">Enter the full URL you want people to visit.</p>
                    </div>

                </div>

                <form method="POST" action="{{ route('short-urls.store') }}">

                    @csrf

                    <label class="form-label fw-semibold" for="original_url">Original destination URL</label>

                    <div class="input-group input-group-lg">

                        <span class="input-group-text bg-body-tertiary">
                            <i class="bi bi-globe2 text-secondary"></i>
                        </span>

                        <input id="original_url" type="url" name="original_url" class="form-control @error('original_url') is-invalid @enderror" placeholder="https://example.com/your-long-page" value="{{ old('original_url') }}" required >

                    </div>

                    @error('original_url')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-text mt-2">
                        <i class="bi bi-info-circle me-1"></i>Only valid HTTP and HTTPS URLs are accepted.
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">

                        <button class="btn btn-primary btn-lg">
                            <i class="bi bi-magic me-2"></i>Generate short URL
                        </button>

                        <a href="{{ route('short-urls.index') }}" class="btn btn-light btn-lg">Cancel</a>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <div class="col-xl-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <h2 class="h6 fw-bold mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>How it works
                </h2>

                <div class="d-flex gap-3 mb-3">

                    <span class="badge rounded-pill text-bg-primary align-self-start">1</span>

                    <div>
                        <div class="fw-semibold">Paste your URL</div>
                        <div class="small text-secondary">Use the complete destination address.</div>
                    </div>

                </div>

                <div class="d-flex gap-3 mb-3">

                    <span class="badge rounded-pill text-bg-primary align-self-start">2</span>

                    <div>
                        <div class="fw-semibold">Generate a short link</div>
                        <div class="small text-secondary">{{ config('app.name') }} creates a unique short code.</div>
                    </div>

                </div>

                <div class="d-flex gap-3">

                    <span class="badge rounded-pill text-bg-primary align-self-start">3</span>

                    <div>
                        <div class="fw-semibold">Share and track</div>
                        <div class="small text-secondary">Copy the link and monitor its clicks.</div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection