@php
    $route_name ='landlord';
@endphp

@extends($route_name.'.admin.admin-master')

@section('title')
    {{__('User Details')}}
@endsection

@section('style')
    <style>
        .user_details ul li {
            list-style-type: none;
            margin-top: 15px;
        }
    </style>
    <x-datatable.css/>
    <x-summernote.css/>
@endsection

@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4 class="user_details_flex">{{__('User Details :') . $user->name ?? ''}}
                    <a href="{{route('landlord.admin.tenant')}}" class="btn btn-primary btn-sm my-2"
                       style="float: right">{{__('Go Back')}}</a>
                </h4>
            </div>
            <div class="card-body user_details">
                <div class="row">
                    <div class="col-lg-3">
                        <ul>
                            <li><strong>{{__('Name :')}}</strong> {{$user->name}}</li>
                            <li><strong>{{__('Email :')}}</strong> {{$user->email}}</li>
                            <li><strong>{{__('Username :')}}</strong> {{$user->username}}</li>
                            <li>
                                @php
                                    $markup = '';
                                    $li = '';
                                    $i = 0;
                                    foreach($user->tenant_details ?? [] as $tenant)
                                        {
                                            $li .= '<li class="mb-2">';
                                            $li .= '<span>'.++$i.'. </span>';
                                            $li .= '<a href="'.tenant_url_with_protocol(optional($tenant->domain)->domain).'">'.$tenant->id . '.'. env('CENTRAL_DOMAIN').'</a>';
                                            $li .= '</li>';
                                        }
                                    $markup = '<ul>'.$li.'</ul>';
                                @endphp

                                <strong>{{__('Subdomains :')}}</strong>
                                <a href="#" data-bs-target="#all-site-domain" data-bs-toggle="modal" id="view-button"
                                   data-markup="{{$markup}}"><small>{{__('(Click to view all site)') }}</small></a>

                                <x-modal.markup :target="'all-site-domain'" :title="'User Site List'"/>
                            </li>
                            </span>
                            <li><strong>{{__('Mobile :').' '}}</strong> {{$user->mobile}}</li>
                            <li><strong>{{__('Company :').' '}}</strong> {{$user->company}}</li>
                            <li><strong>{{__('Address :').' '}}</strong> {{$user->address}}</li>
                            <li><strong>{{__('City :').' '}}</strong> {{$user->city}}</li>
                            <li><strong>{{__('State :').' '}}</strong> {{$user->state}}</li>
                            <li><strong>{{__('Country :').' '}}</strong> {{$user->country}}</li>
                            <li><strong>{{__('Registered:').' '}}</strong> {{$user->created_at->format('d M Y')}}
                                <small>({{$user->created_at->diffForHumans()}})</small></li>
                        </ul>
                    </div>
                    <div class="col-lg-9">
                        <h3 class="title my-3">{{__('Package Information')}}</h3>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <th>{{__('Id')}}</th>
                                <th>{{__('Package Info')}}</th>
                                <th>{{__('Subscription Period')}}</th>
                                <th>{{__('Subdomain')}}</th>
                                <th>{{__('Renew')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>

                                <tbody>
                                @foreach($user->tenant_details ?? [] as $key => $tenant)
{{--                                    @dd($tenant);--}}
                                    <tr>
                                        <td>{{ $tenant->renewal_payment_log_id ?? optional($tenant->payment_log)->id}}</td>
                                        <td>
                                            @php
                                                $colors = ['info','success','primary','dark','danger'];
                                                $tenant_payment_log = \App\Models\PaymentLogs::with('package')
                                                    ->find($tenant->renewal_payment_log_id);
                                            @endphp

                                            <span class="d-block mb-2">
                                            <span>{{ __('Package Name:') }}</span>
                                            <span class="badge badge-{{ $colors[$key % count($colors)] }}">
                                                {{ $tenant_payment_log?->package?->title  }}
                                            </span>

                                            @if(optional($tenant_payment_log)->status == 'trial')
                                                                                        <span class="badge badge-danger">
                                                    {{ __('Trial') }}
                                                </span>
                                                                                    @endif
                                        </span>
                                            <span class="d-block mb-2"><span>{{__('Package Type:')}}</span>
                                                {{ \App\Enums\PricePlanTypEnums::getText(optional(optional($tenant_payment_log?->package))?->type ?? 5) }}
                                            </span>
                                            <span class="d-block mb-2"><span>{{__('Package Price:')}}</span> {{amount_with_currency_symbol(optional(optional($tenant_payment_log?->package))?->price)}}</span>
                                            @php
                                                $custom_theme_name = get_static_option_central($tenant?->theme_slug."_theme_name") ?? $tenant?->theme_slug;
                                            @endphp
                                            <span class="d-block mb-2 text-capitalize"><span>{{__('Theme:')}}</span> {{$custom_theme_name}}</span>

                                            @if(optional($tenant->payment_log)->payment_status == 'pending' && optional($tenant->payment_log)->status != 'trial')
                                                <span
                                                    class="text-danger">{{__('*Last payment requires an action')}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                            $log= $tenant;
//                                            dd($tenant);
                                                $infos = $tenant_info;
//                                                dd($infos);

//                                                $start_date = $log['start_date'];
//                                                $expire_date = $log['expire_date'];

                                                $payment = \App\Models\PaymentLogs::where(['tenant_id' => $tenant->id, 'status' => 'trial'])->latest()->first();
                                               $start_date = date('d-m-Y',strtotime($tenant->created_at));
                                               $renewal_at = date('d-m-Y',strtotime($tenant->start_date));
                                                $expire_date = $tenant?->expire_date != null ? date('d-m-Y',strtotime($tenant->expire_date)) : __('Lifetime');
                                                if (!empty($payment))
                                                {
                                                    $start_date = date('d-m-Y',strtotime($payment->start_date));
                                                    $expire_date = date('d-m-Y',strtotime($payment->expire_date));;
                                                }

//                                                $status = $log['status'];
//                                                $payment_log = $log['payment_logs'];
//                                                $payment_log = \App\Models\PaymentLogs::with('package')
//                                                    ->find($tenant->renewal_payment_log_id);
                                            @endphp

                                            <span class="d-block">
                                                @if(($tenant->renew_status != 0) || ($tenant->is_renew != 0))
                                                    <span>{{ __('Order Date:') }}</span> {{ $start_date }}
                                                    <br>
                                                    <span>{{ __('Renewal Date:') }}</span> {{ $renewal_at }}
                                                @else
                                                    <span>{{ __('Order Date:') }}</span> {{ $start_date }}
                                                @endif
                                            </span>


                                            <span class="d-block mt-2">
                                                <span>{{ __('Expire Date: '. $expire_date) }}</span>
                                            </span>
{{--                                                                                        <span class="d-block mb-2">--}}
{{--                                                <span class="{{ $class[$status] }} text-capitalize">--}}
{{--                                                    <span>{{ __('Status:') }}</span> {{ __($status) }}--}}
{{--                                                </span>--}}
{{--                                            </span>--}}

                                        </td>
                                        <td class="text-center">
                                            @php
                                                $url = '';
                                                $central = '.'.env('CENTRAL_DOMAIN');

                                                if(!empty($tenant->custom_domain?->custom_domain) && $tenant->custom_domain?->custom_domain_status == 'connected'){
                                                    $custom_url = $tenant->custom_domain?->custom_domain ;
                                                    $url = tenant_url_with_protocol($custom_url);
                                                }else{
                                                    $local_url = $tenant->id .$central ;
                                                    $url = tenant_url_with_protocol($local_url);
                                                }

                                                $hash_token = hash_hmac('sha512',$user->username.'_'.$tenant->id, $tenant->unique_key);
                                            @endphp

                                            <a class="d-block"
                                               href="{{tenant_url_with_protocol(optional($tenant->domain)->domain)}}"
                                               target="_blank">{{$tenant->id . '.'. env('CENTRAL_DOMAIN')}}</a>

                                            @can('users-direct-login')
                                                <a class="badge rounded bg-danger px-4 mt-3"
                                                   href="{{$url.'/token-login/'.$hash_token}}"
                                                   target="_blank">{{__('Login as Super Admin')}}</a>
                                            @endcan
                                        </td>
                                        <td>{{  $tenant_payment_log->renew_status ?? 0 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center p-0 gap-2">
                                                {{--                                                <form action="{{ route('landlord.admin.tenant.migrate') }}" method="POST" class="mb-3 migrate-form">--}}
                                                {{--                                                    @csrf--}}
                                                {{--                                                    <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">--}}
                                                {{--                                                    <button type="button" class="btn btn-warning btn-sm migrate-btn" data-tenant="{{ $tenant->id }}">--}}
                                                {{--                                                        <i class="mdi mdi-database-import"></i>--}}
                                                {{--                                                    </button>--}}
                                                {{--                                                </form>--}}

                                                <x-delete-popover permissions="domain-delete"
                                                                  url="{{route(route_prefix().'admin.tenant.domain.delete', $tenant->id)}}"/>
                                                <form action="{{ route('landlord.admin.tenant.seed') }}" method="POST" class=" migrate-form">
                                                    @csrf
                                                    <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                                                    <button type="button" class="btn btn-primary btn-sm migrate-btn mb-3" data-tenant="{{ $tenant->id }}">
                                                        <i class="mdi mdi-database-import"></i>
{{--                                                        {{__('Tenant seed')}}--}}
                                                    </button>
                                                </form>

                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <x-modal.markup :target="'account_manage_modal'" :title="'Change Tenant Account Status'">
        <form action="{{route('landlord.admin.tenant.account.status')}}" method="POST">
            @csrf
            <input type="hidden" name="payment_log_id" value="">
            <div class="form-group">
                <label for="change_account_status">{{__('Change Account Status')}}</label>
                <select class="form-control" name="account_status" id="change_account_status">
                    <option value="pending">{{__('Pending')}}</option>
                    <option value="complete">{{__('Complete')}}</option>
                    <option value="cancel">{{__("Cancel")}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="change_account_status">{{__('Change Payment Status')}}</label>
                <select class="form-control" name="payment_status" id="change_payment_status">
                    <option value="pending">{{__('Pending')}}</option>
                    <option value="complete">{{__('Complete')}}</option>
                    <option value="cancel">{{__('Cancel')}}</option>
                </select>
            </div>

            <div class="form-group float-end">
                <button class="btn btn-success" type="submit">{{__('Update')}}</button>
            </div>
        </form>
    </x-modal.markup>
@endsection

@section('scripts')
    <x-datatable.js/>
    <x-summernote.js/>

    <script>
        $(function () {
            $(document).on('click', '#view-button', function (e) {
                let list = $(this).data('markup');

                $('#all-site-domain').find('.modal-body').html('');
                $('#all-site-domain').find('.modal-body').append(list);
            });

            $(document).on('click', '.account_manage_button', function (e) {
                let el = $(this);
                let id = el.data('id');
                let account = el.data('account');
                let payment = el.data('payment');

                let modal = $('#account_manage_modal').find('.modal-body');
                modal.find('input[name=payment_log_id]').val(id);

                modal.find('#change_account_status option[value=' + account + ']').prop('selected', true);
                modal.find('#change_payment_status option[value=' + payment + ']').prop('selected', true);
            });

            // tenant migration
            $(document).ready(function () {
                $(document).on('click', '.migrate-btn', function () {
                    let form = $(this).closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This will run migration and seeding for the selected tenant.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, run it!',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                    }).then((result) => {
                        if (result.isConfirmed === true) {
                            form.submit();
                        }
                    });
                });
                @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: @json(session('success')),
                    timer: 4000,
                    showConfirmButton: false
                });
                @endif

                @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: @json(session('error')),
                    timer: 4000,
                    showConfirmButton: true
                });
                @endif
            });
        });
    </script>
@endsection

