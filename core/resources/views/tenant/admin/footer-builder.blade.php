@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Footer Builder')}} @endsection
@section('style')
    <x-media-upload.css/>
    <x-summernote.css/>
    <x-pagebuilder::css/>
    <link rel="stylesheet" href="{{global_asset('assets/common/css/fontawesome-iconpicker.min.css')}}">
    <link href="{{ global_asset('assets/landlord/admin/css/nice-select.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-4">{{__('Footer Builder')}} <br> <small class="text-small">{{__('Build your footer using PageBuilder widgets')}}</small></h4>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <x-link-with-popover url="{{route(route_prefix().'admin.dashboard')}}" extraclass="ml-3">
                            {{__('Dashboard')}}
                        </x-link-with-popover>
                        <x-link-with-popover target="_blank" url="{{url('/')}}" popover="{{__('View site in frontend')}}">
                            <i class="mdi mdi-eye"></i>
                        </x-link-with-popover>
                    </x-slot>
                </x-admin.header-wrapper>
            </div>
        </div>
    </div>
   <div class="row g-4">
       <div class="col-lg-8">
           <div class="card">
               <div class="card-body">
                   <div class="page-builder-area-wrapper extra-title">
                       <h4 class="main-title">{{__('Footer Area')}}</h4>
                       <ul id="footer"
                           class="sortable available-form-field main-fields sortable_widget_location">
                           {!! \Plugins\PageBuilder\PageBuilderSetup::get_saved_addons_by_location('footer') !!}
                       </ul>
                   </div>
               </div>
           </div>
       </div>
       <div class="col-lg-4">
           <div class="card">
               <div class="card-body">
                   <x-pagebuilder::widgets type="tenant"/>
               </div>
           </div>
       </div>
   </div>
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <script src="{{global_asset('assets/common/js/fontawesome-iconpicker.min.js')}}"></script>
    <script src="{{global_asset('assets/common/js/jquery.nice-select.min.js')}}"></script>
    <x-media-upload.js/>
    <x-summernote.js/>
    <x-pagebuilder::js/>
    @include('tenant.admin.footer-builder-script')
@endsection
