@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Header Builder')}} @endsection
@section('style')
    <x-media-upload.css/>
    <x-summernote.css/>
    <x-pagebuilder::css/>
    <link rel="stylesheet" href="{{global_asset('assets/common/css/fontawesome-iconpicker.min.css')}}">
    <link href="{{ global_asset('assets/landlord/admin/css/nice-select.css') }}" rel="stylesheet">
    <style>
        .header-settings-wrapper {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e9ecef;
        }
        
        .header-settings-wrapper h5 {
            color: #333;
            font-weight: 600;
            font-size: 16px;
        }
        
        .settings-option-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 16px;
            transition: all 0.2s ease;
            height: 100%;
        }
        
        .settings-option-card:hover {
            border-color: #007bff;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
        }
        
        .settings-option-card .form-check-input {
            width: 48px;
            height: 24px;
            margin-top: 2px;
            cursor: pointer;
        }
        
        .settings-option-card .form-check-label {
            cursor: pointer;
            font-size: 14px;
        }
        
        .settings-option-card label.fw-semibold {
            color: #212529;
            font-size: 14px;
            line-height: 1.4;
        }
        
        .settings-option-card small {
            font-size: 12px;
            line-height: 1.5;
            color: #6c757d;
        }
        
        .header-settings-form .btn-primary {
            min-width: 150px;
        }
        
        @media (max-width: 768px) {
            .header-settings-wrapper {
                padding: 15px;
            }
            
            .settings-option-card {
                padding: 12px;
            }
        }
    </style>
@endsection
@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-4">{{__('Header Builder')}} <br> <small class="text-small">{{__('Build your header using PageBuilder widgets')}}</small></h4>
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
                   {{-- Header Settings --}}
                   <div class="header-settings-wrapper mb-4">
                       <div class="d-flex align-items-center justify-content-between mb-3">
                           <h5 class="mb-0">{{__('Header Settings')}}</h5>
                       </div>
                       <form id="header-settings-form" class="header-settings-form">
                           @csrf
                           <div class="row g-3">
                               <div class="col-md-6">
                                   <div class="settings-option-card">
                                       <div class="d-flex align-items-start">
                                           <div class="form-check form-switch me-3 flex-shrink-0">
                                               <input class="form-check-input" type="checkbox" id="header_sticky" 
                                                      name="header_sticky" value="yes"
                                                      {{ get_static_option('header_builder_sticky') === 'yes' ? 'checked' : '' }}>
                                           </div>
                                           <div class="flex-grow-1">
                                               <label class="form-check-label fw-semibold d-block mb-1" for="header_sticky">
                                                   {{__('Sticky Header')}}
                                               </label>
                                               <small class="text-muted d-block">{{__('Header stays on top when scrolling')}}</small>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                               <div class="col-md-6">
                                   <div class="settings-option-card">
                                       <div class="d-flex align-items-start">
                                           <div class="form-check form-switch me-3 flex-shrink-0">
                                               <input class="form-check-input" type="checkbox" id="header_transparent" 
                                                      name="header_transparent" value="yes"
                                                      {{ get_static_option('header_builder_transparent') === 'yes' ? 'checked' : '' }}>
                                           </div>
                                           <div class="flex-grow-1">
                                               <label class="form-check-label fw-semibold d-block mb-1" for="header_transparent">
                                                   {{__('Transparent Background')}}
                                               </label>
                                               <small class="text-muted d-block">{{__('Header overlays content without background')}}</small>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <div class="mt-3">
                               <button type="submit" class="btn btn-primary">
                                   <i class="mdi mdi-content-save me-1"></i>
                                   {{__('Save Header Settings')}}
                               </button>
                           </div>
                       </form>
                   </div>
                   <hr class="my-4">
                   
                   <div class="page-builder-area-wrapper extra-title">
                       <h4 class="main-title">{{__('Header Area')}}</h4>
                       <ul id="header"
                           class="sortable available-form-field main-fields sortable_widget_location">
                           {!! \Plugins\PageBuilder\PageBuilderSetup::get_saved_addons_by_location('header') !!}
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
    @include('tenant.admin.header-builder-script')
@endsection
