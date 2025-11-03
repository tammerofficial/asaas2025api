@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Forbidden Subdomains')}}
@endsection
@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
@endsection
    @section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__('Forbidden Subdomains')}}</h4>
                        <x-error-msg/>
                        <x-flash-msg/>
                        <form action="{{ route('landlord.admin.store.forbidden.subdomain') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mt-3">
                                <label for="forbidden_subdomains">{{ __('Forbidden Subdomains') }}</label>
                                <input type="text"
                                       class="form-control"
                                       data-role="tagsinput"
                                       id="forbidden_subdomains"
                                       name="forbidden_subdomains"
                                       value="{{ get_static_option('forbidden_subdomains')  }}">
                                <small class="text-muted d-block">
                                    {{ __('Add words without space, you can use dash (-) only. Example: admin,www,super-user') }}
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add Subdomain')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
@endsection
