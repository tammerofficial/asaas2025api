@extends(route_prefix()."admin.admin-master")
@section('title') {{__('Siteways Payment Gateway Settings')}}@endsection

@section('style')
    <x-media-upload.css/>
@endsection

@section("content")
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{__('Siteways Payment Gateway Settings')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>

                <form class="forms-sample" method="post" action="{{route('sitepaymentgateway.'.route_prefix().'admin.settings')}}">
                    @csrf
                    <x-fields.input type="text" value="{{get_static_option('sitesway_api_key')}}" name="sitesway_api_key" label="{{__('Api Key')}}"/>
                    <x-fields.input type="text" value="{{get_static_option('sitesway_brand_id')}}" name="sitesway_brand_id" label="{{__('Brand Id')}}"/>

                    @php
                        $label_text = is_null(tenant()) ? 'Siteways Enable/Disable Landlord and Tenant Both' : 'Siteways Enable/Disable';
                        $status = is_null(tenant()) ? $sitesways->status : get_static_option('sitesway_status');
                    @endphp
                    <x-fields.switcher label="{{__($label_text)}}" name="sitesway_status" value="{{$status}}"/>

                    @if(is_null(tenant()))
                    <x-fields.switcher label="{{__('Siteways Enable/Disable Landlord Websites')}}" name="sitesway_landlord_status" value="{{$sitesways->landlord}}"/>
                    <x-fields.switcher label="{{__('Siteways Enable/Disable Tenant Websites')}}" name="sitesway_tenant_status" value="{{$sitesways->admin_settings->show_admin_tenant}}"/>
                    @endif

                    <x-fields.media-upload name="sitesway_logo" title="Siteways Logo" value="{{get_static_option('sitesway_logo')}}"/>

                    <button type="submit" class="btn btn-gradient-primary mt-5 me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>

    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-media-upload.js/>
@endsection
