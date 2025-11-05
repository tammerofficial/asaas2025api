<div class="card mb-4">
    <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h6 class="mb-1">{{ __('Header & Footer Builder') }}</h6>
            <p class="text-muted mb-0">{{ __('Build your header and footer using PageBuilder. Available for all plans.') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('tenant.admin.header.builder') }}" class="btn btn-primary">
                {{ __('Open Header Builder') }}
            </a>
            <a href="{{ route('tenant.admin.footer.builder') }}" class="btn btn-outline-primary">
                {{ __('Open Footer Builder') }}
            </a>
        </div>
    </div>
</div>


