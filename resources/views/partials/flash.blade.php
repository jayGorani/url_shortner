@if(session('success'))
    <!-- success messages -->
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">

        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <div>{{ session('success') }}</div>

        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>

    </div>
@endif

@if(session('error'))
    <!-- Error messages -->
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

@endif

@if($errors->any())
    <!-- other error as fallback -->
    <div class="alert alert-danger border-0 shadow-sm" role="alert">
        <div class="fw-semibold mb-2">
            <i class="bi bi-exclamation-circle-fill me-2"></i>Please correct the following:
        </div>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
