@extends('tenant.admin.admin-master')
@section('title')
    {{__('Product Coupon')}}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/nice-select.css')}}">
    <style>
        #form_category, #edit_#form_category,
        #form_subcategory, #edit_#form_subcategory,
        #form_childcategory, #edit_#form_childcategory,
        #form_products, #edit_#form_products {
            display: none;
        }

        .lds-ellipsis {
            position: fixed;
            width: 80px;
            height: 80px;
            left: 50vw;
            top: 40vh;
            z-index: 50;
            display: none;
        }
        .lds-ellipsis div {
            position: absolute;
            top: 33px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: {{ get_static_option('site_color') }};
            animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }
        .lds-ellipsis div:nth-child(1) {
            left: 8px;
            animation: lds-ellipsis1 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(2) {
            left: 8px;
            animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(3) {
            left: 32px;
            animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(4) {
            left: 56px;
            animation: lds-ellipsis3 0.6s infinite;
        }
        @keyframes lds-ellipsis1 {
            0% {
                transform: scale(0);
            }
            100% {
                transform: scale(1);
            }
        }
        @keyframes lds-ellipsis3 {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(0);
            }
        }
        @keyframes lds-ellipsis2 {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(24px, 0);
            }
        }

        /*.select2-dropdown ,*/
        .select2-container
        {
            z-index: 1072;
        }
        /* Validation Styles */
        .form-control.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .form-control.is-valid {
            border-color: #28a745;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .error-message {
            font-size: 0.75rem;
            margin-top: 0.25rem;
            animation: slideDown 0.3s ease-out;
            font-weight: 400;
        }

        /*.error_message .text-danger{*/
        /*    font-weight: 400 !important;*/
        /*}*/

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-control:focus.is-invalid {
            border-color: #dc3545;
            box-shadow: none;
            /*box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);*/
        }

        .form-control:focus.is-valid {
            border-color: #28a745;
            box-shadow: none;

            /*box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);*/
        }

        /* Status text styling */
        #status_text {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Loading state for buttons */
        button[type="submit"]:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }


        .toast-error {
            background-color: #bd362f;
        }

        .toast-success {
            background-color: #51a351;
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-xl-7 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__('All Product Coupon')}}</h4>
                        @can('product-coupon-delete')
                            <x-bulk-action.dropdown />
                        @endcan
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <x-bulk-action.th />
                                <th>{{__('ID')}}</th>
                                <th>{{__('Code')}}</th>
                                <th>{{__('Discount')}}</th>
                                <th>{{__('Expire Date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_product_coupon as $data)
                                    <tr>
                                        <x-bulk-action.td :id="$data->id" />
                                        <td>{{$data->id}}</td>
                                        <td>{{$data->code}}</td>
                                        <td>@if($data->discount_type == 'percentage') {{$data->discount}}% @else {{amount_with_currency_symbol($data->discount)}} @endif</td>
                                        <td>{{ date('d M Y', strtotime($data->expire_date)) }}</td>
                                        <td>
                                            <x-status-span :status="$data->status"/>
                                        </td>
                                        <td>
                                            @can('product-coupon-delete')
                                                <x-table.btn.swal.delete :route="route('tenant.admin.product.coupon.delete', $data->id)" />
                                            @endcan
                                            @can('product-coupon-edit')
                                                <a href="#"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#category_edit_modal"
                                                   class="btn btn-sm btn-primary btn-xs mb-3 mr-1 category_edit_btn"
                                                   data-id="{{$data->id}}"
                                                   data-title="{{$data->title}}"
                                                   data-code="{{$data->code}}"
                                                   data-discount_on="{{$data->discount_on}}"
                                                   data-discount_on_details="{{$data->discount_on_details}}"
                                                   data-discount="{{$data->discount}}"
                                                   data-discount_type="{{$data->discount_type}}"
                                                   data-expire_date="{{$data->expire_date}}"
                                                   data-status="{{$data->status}}"
                                                   data-minimum_quantity="{{ $data->minimum_quantity }}"
                                                   data-minimum_spend="{{ $data->minimum_spend }}"
                                                   data-maximum_spend="{{ $data->maximum_spend }}"
                                                   data-usage_limit_per_coupon="{{ $data->usage_limit_per_coupon }}"
                                                   data-usage_limit_per_user="{{ $data->usage_limit_per_user }}"
                                                >
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @can('product-coupon-create')
                <div class="col-xl-5 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-4">{{__('Add New Coupon')}}</h4>
                            <form action="{{route('tenant.admin.product.coupon.new')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="title">{{__('Coupon Title')}}</label>
                                    <input type="text" class="form-control"  id="title" name="title" placeholder="{{__('Title')}}" >
                                </div>
                                <div class="form-group">
                                    <label for="code">{{__('Coupon Code')}}</label>
                                    <input type="text" class="form-control"  id="code" name="code" placeholder="{{__('Code')}}" >
                                    <span id="status_text" class="text-danger" style="display: none"></span>
                                </div>
                                <div class="form-group">
                                    <label for="discount_on">{{__('Discount On')}}</label>
                                    <select name="discount_on" id="discount_on" class="form-control">
                                        <option value="">{{ __('Select an option') }}</option>
                                        @foreach ($coupon_apply_options as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="form_category">
                                    <label for="category">{{__('Category')}}</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">{{ __('Select a Category') }}</option>
                                        @foreach ($all_categories as $key => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="form_subcategory">
                                    <label for="subcategory">{{__('Subcategory')}}</label>
                                    <select name="subcategory" id="subcategory" class="form-control">
                                        <option value="">{{ __('Select a Subcategory') }}</option>
                                        @foreach ($all_subcategories as $key => $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="form_childcategory">
                                    <label for="childcategory">{{__('childcategory')}}</label>
                                    <select name="childcategory" id="childcategory" class="form-control">
                                        <option value="">{{ __('Select a Child category') }}</option>
                                        @foreach ($all_childcategories as $key => $childcategory)
                                            <option value="{{ $childcategory->id }}">{{ $childcategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="form_products">
                                    <label for="products">{{__('Products')}}</label>
                                    <select name="products[]" id="products" class="form-control nice-select wide" multiple>
                                    </select>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="discount">{{__('Discount')}}</label>
                                    <input type="number" class="form-control"  id="discount" name="discount" placeholder="{{__('Discount')}}" >
                                </div>
                                    {{--new fields --}}
                                {{-- 1. Set Minimum Quantity --}}
                                <div class="form-group">
                                    <label for="minimum_quantity">{{ __('Minimum Quantity of Items') }}</label>
                                    <input
                                        type="number"
                                        min="0"
                                        step="1"
                                        class="form-control"
                                        id="minimum_quantity"
                                        name="minimum_quantity"
                                        placeholder="{{ __('Enter minimum quantity') }}"
                                        value="{{ old('minimum_quantity', $coupon->minimum_quantity ?? '') }}"
                                    >
                                    <small class="form-text text-muted">
                                        {{ __('Min total items in cart. Leave empty or 0 for no limit.') }}
                                    </small>
                                </div>

                                {{-- 2. Cart Value Limitation for Coupons --}}
                                <div class="row g-5">
                                    <div class="col-6 form-group">
                                        <label for="minimum_spend">{{ __('Minimum Order Subtotal ($)') }}</label>
                                        <input
                                            type="number"
                                            min="0"
                                            step="0.01"
                                            class="form-control"
                                            id="minimum_spend"
                                            name="minimum_spend"
                                            placeholder="{{ __('Enter minimum order subtotal') }}"
                                            value="{{ old('minimum_spend', $coupon->minimum_spend ?? '') }}"
                                        >
                                        <small class="form-text text-muted">
                                            {{ __('Min cart subtotal.') }}
                                        </small>
                                    </div>

                                    <div class="col-6 form-group">
                                        <label for="maximum_spend">{{ __('Maximum Order Subtotal ($)') }}</label>
                                        <input
                                            type="number"
                                            min="0"
                                            step="0.01"
                                            class="form-control"
                                            id="maximum_spend"
                                            name="maximum_spend"
                                            placeholder="{{ __('Enter maximum order subtotal') }}"
                                            value="{{ old('maximum_spend', $coupon->maximum_spend ?? '') }}"
                                        >
                                        <small class="form-text text-muted">
                                            {{ __('Maximum cart subtotal.') }}
                                        </small>
                                    </div>
                                </div>

                                {{-- 4. Usage Limit Per Coupon --}}
                                <div class="form-group">
                                    <label for="usage_limit_per_coupon">{{ __('Total Usage Limit for Coupon') }}</label>
                                    <input
                                        type="number"
                                        min="0"
                                        step="1"
                                        class="form-control"
                                        id="usage_limit_per_coupon"
                                        name="usage_limit_per_coupon"
                                        placeholder="{{ __('Enter total maximum uses for this coupon') }}"
                                        value="{{ old('usage_limit_per_coupon', $coupon->usage_limit_per_coupon ?? '') }}"
                                    >
                                    <small class="form-text text-muted">
                                        {{ __('Maximum total number. Leave empty or 0 for unlimited.') }}
                                    </small>
                                </div>

                                {{-- 5. Usage Limit Per User --}}
                                <div class="form-group">
                                    <label for="usage_limit_per_user">{{ __('Usage Limit Per User') }}</label>
                                    <input
                                        type="number"
                                        min="0"
                                        step="1"
                                        class="form-control"
                                        id="usage_limit_per_user"
                                        name="usage_limit_per_user"
                                        placeholder="{{ __('Enter maximum uses per customer') }}"
                                        value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user ?? '') }}"
                                    >
                                    <small class="form-text text-muted">
                                        {{ __('Max uses per user. Leave empty or 0 for unlimited.') }}
                                    </small>
                                </div>

                                {{--  end new fields --}}
                                <div class="form-group">
                                    <label for="discount_type">{{__('Coupon Type')}}</label>
                                    <select name="discount_type" class="form-control" id="discount_type" >
                                        <option value="percentage">{{__("Percentage")}}</option>
                                        <option value="amount">{{__("Amount")}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="expire_date">{{__('Expire Date')}}</label>
                                    <input type="date" class="form-control flatpickr"  id="expire_date" name="expire_date" placeholder="{{__('Expire Date')}}" >
                                </div>
                                <div class="form-group">
                                    <label for="status">{{__('Status')}}</label>
                                    <select name="status" class="form-control" id="status" >
                                        <option value="publish">{{__("Publish")}}</option>
                                        <option value="draft">{{__("Draft")}}</option>
                                    </select>
                                </div>
                                <button type="submit" id="coupon_create_btn" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New Coupon')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    @can('product-coupon-edit')
        <div class="modal fade" id="category_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Update Coupon')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{route('tenant.admin.product.coupon.update')}}"  method="post">
                        <input type="hidden" name="id" id="coupon_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="title">{{__('Coupon Title')}}</label>
                                <input type="text" class="form-control"  id="edit_title" name="title" placeholder="{{__('Title')}}" >
                            </div>
                            <div class="form-group">
                                <label for="edit_code">{{__('Coupon Code')}}</label>
                                <input type="text" class="form-control"  id="edit_code" name="code" placeholder="{{__('Code')}}">
                                <span id="status_text" class="text-danger" style="display: none"></span>
                            </div>
                            <div class="form-group">
                                <label for="discount_on">{{__('Discount On')}}</label>
                                <select name="discount_on" id="edit_discount_on" class="form-control">
                                    <option value="">{{ __('Select an option') }}</option>
                                    @foreach ($coupon_apply_options as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="edit_form_category">
                                <label for="edit_category">{{__('Category')}}</label>
                                <select name="category" id="edit_category" class="form-control">
                                    <option value="">{{ __('Select a Category') }}</option>
                                    @foreach ($all_categories as $key => $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="edit_form_subcategory">
                                <label for="edit_subcategory">{{__('Subcategory')}}</label>
                                <select name="subcategory" id="edit_subcategory" class="form-control">
                                    <option value="">{{ __('Select a Subcategory') }}</option>
                                    @foreach ($all_subcategories as $key => $subcategory)
                                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="edit_form_childcategory">
                                <label for="edit_childcategory">{{__('Subcategory')}}</label>
                                <select name="childcategory" id="edit_childcategory" class="form-control">
                                    <option value="">{{ __('Select a Child category') }}</option>
                                    @foreach ($all_childcategories as $childcategory)
                                        <option value="{{ $childcategory->id }}">{{ $childcategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="edit_form_products">
                                <label for="products">{{__('Products')}}</label>
                                <select name="products[]" id="products" class="form-control nice-select wide" multiple>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_discount">{{__('Discount')}}</label>
                                <input type="number" class="form-control"  id="edit_discount" name="discount" placeholder="{{__('Discount')}}">
                            </div>
{{--                            new fields --}}
                            {{-- 1. Minimum Quantity --}}
                            <div class="form-group">
                                <label for="minimum_quantity">{{ __('Minimum Quantity') }}</label>
                                <input type="number" id="edit_minimum_quantity" name="minimum_quantity" class="form-control">
                                <small class="form-text text-muted">{{ __('Min items in cart') }}</small>
                            </div>

                            <div class="row g-5">
                                {{-- 2. Minimum Spend --}}
                                <div class="col-6 form-group">
                                    <label for="minimum_spend">{{ __('Min Order Subtotal') }}</label>
                                    <input type="number" id="edit_minimum_spend" name="minimum_spend" step="0.01" class="form-control">

                                    <small class="form-text text-muted">{{ __('Subtotal before discount') }}</small>
                                </div>

                                {{-- 3. Maximum Spend --}}
                                <div class="col-6 form-group">
                                    <label for="maximum_spend">{{ __('Max Order Subtotal') }}</label>
                                    <input type="number" id="edit_maximum_spend" name="maximum_spend" step="0.01" class="form-control">

                                    <small class="form-text text-muted">{{ __('Max subtotal allowed') }}</small>
                                </div>
                            </div>


                            {{-- 4. Usage Limit Per Coupon --}}
                            <div class="form-group">
                                <label for="usage_limit_per_coupon">{{ __('Total Uses Allowed') }}</label>
                                <input type="number" id="edit_usage_limit_per_coupon" name="usage_limit_per_coupon" class="form-control">

                                <small class="form-text text-muted">{{ __('Total usage limit') }}</small>
                            </div>

                            {{-- 5. Usage Limit Per User --}}
                            <div class="form-group">
                                <label for="usage_limit_per_user">{{ __('Uses Per User') }}</label>
                                <input type="number" id="edit_usage_limit_per_user" name="usage_limit_per_user" class="form-control">

                                <small class="form-text text-muted">{{ __('Limit per customer') }}</small>
                            </div>

                            {{--                            end fields--}}
                            <div class="form-group">
                                <label for="edit_discount_type">{{__('Coupon Type')}}</label>
                                <select name="discount_type" class="form-control" id="edit_discount_type">
                                    <option value="percentage">{{__("Percentage")}}</option>
                                    <option value="amount">{{__("Amount")}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_expire_date">{{__('Expire Date')}}</label>
                                <input type="date" class="form-control flatpickr"  id="edit_expire_date" name="expire_date" placeholder="{{__('Expire Date')}}">
                            </div>
                            <div class="form-group">
                                <label for="edit_status">{{__('Status')}}</label>
                                <select name="status" class="form-control" id="edit_status">
                                    <option value="draft">{{__("Draft")}}</option>
                                    <option value="publish">{{__("Publish")}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Save Change')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
@endsection
@section('scripts')
    <script src="{{global_asset('assets/tenant/frontend/js/jquery.nice-select.js')}}"></script>
    <x-datatable.js />
    <x-bulk-action.js :route="route('tenant.admin.product.coupon.bulk.action')" />
    <x-table.btn.swal.js />

    <script>
        $(document).ready(function (){
            $(document).ready(function () {
                flatpickr(".flatpickr", {
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                });

                // Validation Configuration
                const validationRules = {
                    title: {
                        required: true,
                        maxLength: 191,
                        message: 'Coupon title is required (max 191 characters)'
                    },
                    code: {
                        required: true,
                        maxLength: 191,
                        pattern: /^[A-Z0-9_-]+$/i,
                        message: 'Coupon code is required (letters, numbers, dash, underscore only)'
                    },
                    discount_on: {
                        required: true,
                        message: 'Please select what the discount applies to'
                    },
                    discount: {
                        required: true,
                        min: 0,
                        message: 'Discount amount is required and must be positive'
                    },
                    expire_date: {
                        required: true,
                        futureDate: true,
                        message: 'Please select a future expiration date'
                    },
                    minimum_quantity: {
                        min: 0,
                        integer: true,
                        message: 'Minimum quantity must be 0 or positive integer'
                    },
                    minimum_spend: {
                        min: 0,
                        decimal: true,
                        message: 'Minimum spend must be 0 or positive number'
                    },
                    maximum_spend: {
                        min: 0,
                        decimal: true,
                        message: 'Maximum spend must be 0 or positive number'
                    },
                    usage_limit_per_coupon: {
                        min: 0,
                        integer: true,
                        message: 'Usage limit must be 0 or positive integer'
                    },
                    usage_limit_per_user: {
                        min: 0,
                        integer: true,
                        message: 'Usage limit per user must be 0 or positive integer'
                    }
                };

                // Validation Helper Functions
                function showError(input, message) {
                    const formGroup = $(input).closest('.form-group');
                    formGroup.find('.error-message').remove();
                    $(input).addClass('is-invalid');
                    formGroup.append(`<small class="error-message text-danger d-block mt-1"><i class="fas fa-exclamation-circle"></i> ${message}</small>`);
                }

                function clearError(input) {
                    const formGroup = $(input).closest('.form-group');
                    $(input).removeClass('is-invalid').addClass('is-valid');
                    formGroup.find('.error-message').remove();
                }

                function validateField(input) {
                    const fieldName = $(input).attr('name');
                    const value = $(input).val();
                    const rules = validationRules[fieldName];

                    if (!rules) return true;

                    // Required validation
                    if (rules.required && !value) {
                        showError(input, rules.message);
                        return false;
                    }

                    // Skip other validations if field is empty and not required
                    if (!value && !rules.required) {
                        clearError(input);
                        return true;
                    }

                    // Max length validation
                    if (rules.maxLength && value.length > rules.maxLength) {
                        showError(input, `Maximum ${rules.maxLength} characters allowed`);
                        return false;
                    }

                    // Pattern validation
                    if (rules.pattern && !rules.pattern.test(value)) {
                        showError(input, rules.message);
                        return false;
                    }

                    // Min value validation
                    if (rules.min !== undefined && parseFloat(value) < rules.min) {
                        showError(input, rules.message);
                        return false;
                    }

                    // Integer validation
                    if (rules.integer && value && !Number.isInteger(parseFloat(value))) {
                        showError(input, 'Must be a whole number');
                        return false;
                    }

                    // Future date validation
                    if (rules.futureDate && value) {
                        const selectedDate = new Date(value);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        if (selectedDate <= today) {
                            showError(input, 'Expiration date must be in the future');
                            return false;
                        }
                    }

                    clearError(input);
                    return true;
                }

                // Cross-field validation
                function validateMinMaxSpend() {
                    const minSpend = parseFloat($('#minimum_spend').val()) || 0;
                    const maxSpend = parseFloat($('#maximum_spend').val()) || 0;

                    if (minSpend > 0 && maxSpend > 0 && minSpend > maxSpend) {
                        showError($('#maximum_spend'), 'Maximum spend must be greater than minimum spend');
                        return false;
                    }

                    if (maxSpend > 0) {
                        clearError($('#maximum_spend'));
                    }
                    return true;
                }

                function validateEditMinMaxSpend() {
                    const minSpend = parseFloat($('#edit_minimum_spend').val()) || 0;
                    const maxSpend = parseFloat($('#edit_maximum_spend').val()) || 0;

                    if (minSpend > 0 && maxSpend > 0 && minSpend > maxSpend) {
                        showError($('#edit_maximum_spend'), 'Maximum spend must be greater than minimum spend');
                        return false;
                    }

                    if (maxSpend > 0) {
                        clearError($('#edit_maximum_spend'));
                    }
                    return true;
                }

                function validateDiscountAmount() {
                    const discountType = $('#discount_type').val();
                    const discount = parseFloat($('#discount').val()) || 0;

                    if (discountType === 'percentage' && discount > 100) {
                        showError($('#discount'), 'Percentage discount cannot exceed 100%');
                        return false;
                    }

                    if (discount > 0) {
                        clearError($('#discount'));
                    }
                    return true;
                }

                function validateEditDiscountAmount() {
                    const discountType = $('#edit_discount_type').val();
                    const discount = parseFloat($('#edit_discount').val()) || 0;

                    if (discountType === 'percentage' && discount > 100) {
                        showError($('#edit_discount'), 'Percentage discount cannot exceed 100%');
                        return false;
                    }

                    if (discount > 0) {
                        clearError($('#edit_discount'));
                    }
                    return true;
                }

                // Real-time validation on input
                $('input[name], select[name]').on('input change blur', function() {
                    validateField(this);

                    // Special validations
                    if ($(this).attr('name') === 'minimum_spend' || $(this).attr('name') === 'maximum_spend') {
                        validateMinMaxSpend();
                    }

                    if ($(this).attr('name') === 'discount' || $(this).attr('name') === 'discount_type') {
                        validateDiscountAmount();
                    }
                });

                // Edit modal validations
                $('#edit_minimum_spend, #edit_maximum_spend').on('input change blur', function() {
                    validateField(this);
                    validateEditMinMaxSpend();
                });

                $('#edit_discount, #edit_discount_type').on('input change blur', function() {
                    validateField(this);
                    validateEditDiscountAmount();
                });

                // Form submission validation
                $('form').on('submit', function(e) {
                    let isValid = true;
                    const form = $(this);

                    // Validate all fields with rules
                    form.find('input[name], select[name]').each(function() {
                        if (!validateField(this)) {
                            isValid = false;
                        }
                    });

                    // Special validations based on form
                    if (form.find('#discount_type').length) {
                        if (!validateDiscountAmount()) isValid = false;
                        if (!validateMinMaxSpend()) isValid = false;
                    } else if (form.find('#edit_discount_type').length) {
                        if (!validateEditDiscountAmount()) isValid = false;
                        if (!validateEditMinMaxSpend()) isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();

                        // Focus on first error
                        const firstError = form.find('.is-invalid').first();
                        if (firstError.length) {
                            $('html, body').animate({
                                scrollTop: firstError.offset().top - 100
                            }, 500);
                            firstError.focus();
                        }

                        // Show toast notification
                        toastr.error('Please complete all required fields to continue', 'Form Incomplete');
                    }
                });

                // Edit Modal Handler
                $(document).on('click','.category_edit_btn',function(){
                    let el = $(this);
                    let id = el.data('id');
                    let status = el.data('status');
                    let modal = $('#category_edit_modal');
                    let discount_on = el.data('discount_on');
                    let discount_on_details = el.data('discount_on_details');

                    // Clear previous errors
                    modal.find('.is-invalid').removeClass('is-invalid');
                    modal.find('.is-valid').removeClass('is-valid');
                    modal.find('.error-message').remove();

                    modal.find('#coupon_id').val(id);
                    modal.find('#edit_status option[value="'+status+'"]').attr('selected',true);
                    modal.find('#edit_code').val(el.data('code'));
                    modal.find('#edit_discount').val(el.data('discount'));
                    modal.find('#edit_discount_type').val(el.data('discount_type'));
                    modal.find('#edit_expire_date').val(el.data('expire_date'));
                    modal.find('#edit_discount_type[value="'+el.data('discount_type')+'"]').attr('selected',true);
                    modal.find('#edit_title').val(el.data('title'));
                    modal.find('#edit_discount_on').val(el.data('discount_on'));

                    // New Fields
                    modal.find('#edit_minimum_quantity').val(el.data('minimum_quantity'));
                    modal.find('#edit_minimum_spend').val(el.data('minimum_spend'));
                    modal.find('#edit_maximum_spend').val(el.data('maximum_spend'));
                    modal.find('#edit_usage_limit_per_coupon').val(el.data('usage_limit_per_coupon'));
                    modal.find('#edit_usage_limit_per_user').val(el.data('usage_limit_per_user'));

                    $('#edit_form_category').hide();
                    $('#edit_form_subcategory').hide();
                    $("#edit_form_childcategory").hide();
                    $('#edit_form_products').hide();

                    if (discount_on == 'product') {
                        $('#edit_form_products').fadeOut();
                        loadProductDiscountHtml($('#edit_discount_on'), '#edit_form_products select', true, discount_on_details);
                    } else {
                        $('#edit_form_' + discount_on + ' option[value=' + discount_on_details + ']').attr('selected', true);
                        $('#edit_form_' + discount_on).fadeIn();
                    }
                });

                // Coupon Code Validation
                $('#code').on('keyup', function() {
                    validateCoupon(this);
                });

                $('#edit_code').on('keyup', function() {
                    validateCoupon(this);
                });

                $('#discount_on').on('change', function () {
                    loadProductDiscountHtml(this, '#form_products select', false, []);
                });

                $('#edit_discount_on').on('change', function () {
                    loadProductDiscountHtml(this, '#edit_form_products select', true, []);
                });
            });

            function loadProductDiscountHtml(context, target_selector, is_edit, values) {
                let product_select = $(target_selector);
                let selector_prefix = '';

                if (is_edit) {
                    selector_prefix = 'edit_';
                }

                $('#'+selector_prefix+'form_category').hide();
                $('#'+selector_prefix+'form_subcategory').hide();
                $('#'+selector_prefix+'form_childcategory').hide();
                $('#'+selector_prefix+'form_products').hide();

                if ($(context).val() == 'category') {
                    $('#'+selector_prefix+'form_category').fadeIn(500);
                } else if ($(context).val() == 'subcategory') {
                    $('#'+selector_prefix+'form_subcategory').fadeIn(500);
                }  else if ($(context).val() == 'childcategory') {
                    $('#'+selector_prefix+'form_childcategory').fadeIn(500);
                } else if ($(context).val() == 'product') {
                    $('.lds-ellipsis').fadeIn();
                    $.get('{{ route("tenant.admin.product.coupon.products") }}').then(function (data) {
                        $('.lds-ellipsis').fadeOut();

                        let options = '';
                        let discountd_products = [];

                        if (values.length) {
                            discountd_products = values;
                        }

                        if (data.length) {
                            data.forEach(function (product) {
                                let selected_class = '';

                                if (discountd_products.indexOf(product.id) > -1 || discountd_products.indexOf(String(product.id)) > -1) {
                                    selected_class = 'selected';
                                }
                                options += '<option value="'+product.id+'" '+selected_class+'>'+product.name+'</option>';
                            });

                            product_select.html('');
                            product_select.html(options);
                            product_select.parent().show(500);
                            product_select.addClass('nice-select')
                            $('#discount').parent().css('display', 'block');

                            if ($('.nice-select').length) {
                                if ($('.nice-select.form-control.wide.has-multiple').length) {
                                    $('.nice-select.form-control.wide.has-multiple').remove();
                                }
                                $('.nice-select').niceSelect();
                            }
                        }
                    }).catch(function (err) {
                        $('.lds-ellipsis').hide();
                        toastr.error('Failed to load products', 'Error');
                    });
                }
            }

            function validateCoupon(context) {
                let code = $(context).val();
                let submit_btn = $(context).closest('form').find('button[type=submit]');
                let status_text = $(context).siblings('#status_text');
                status_text.hide();

                if (code.length) {
                    submit_btn.prop("disabled", true);

                    $.get("{{ route('tenant.admin.product.coupon.check') }}", {code: code}).then(function (data) {
                        if (data > 0) {
                            let msg = "{{ __('This coupon is already taken') }}";
                            status_text.removeClass('text-success').addClass('text-danger').text(msg).show();
                            $(context).addClass('is-invalid');
                            submit_btn.prop("disabled", true);
                        } else {
                            let msg = "{{ __('This coupon is available') }}";
                            status_text.removeClass('text-danger').addClass('text-success').text(msg).show();
                            $(context).removeClass('is-invalid').addClass('is-valid');
                            submit_btn.prop("disabled", false);
                        }
                    }).catch(function(err) {
                        toastr.error('Failed to validate coupon code', 'Error');
                        submit_btn.prop("disabled", false);
                    });
                } else {
                    submit_btn.prop("disabled", false);
                }
            }
        })


        {{--$(document).ready(function () {--}}
        {{--    flatpickr(".flatpickr", {--}}
        {{--        altInput: true,--}}
        {{--        altFormat: "F j, Y",--}}
        {{--        dateFormat: "Y-m-d",--}}
        {{--    });--}}

        {{--    $(document).on('click','.category_edit_btn',function(){--}}
        {{--        let el = $(this);--}}
        {{--        let id = el.data('id');--}}
        {{--        let status = el.data('status');--}}
        {{--        let modal = $('#category_edit_modal');--}}
        {{--        let discount_on = el.data('discount_on');--}}
        {{--        let discount_on_details = el.data('discount_on_details');--}}


        {{--        modal.find('#coupon_id').val(id);--}}
        {{--        modal.find('#edit_status option[value="'+status+'"]').attr('selected',true);--}}
        {{--        modal.find('#edit_code').val(el.data('code'));--}}
        {{--        modal.find('#edit_discount').val(el.data('discount'));--}}
        {{--        modal.find('#edit_discount_type').val(el.data('discount_type'));--}}
        {{--        modal.find('#edit_expire_date').val(el.data('expire_date'));--}}
        {{--        modal.find('#edit_discount_type[value="'+el.data('discount_type')+'"]').attr('selected',true);--}}
        {{--        modal.find('#edit_title').val(el.data('title'));--}}
        {{--        modal.find('#edit_discount_on').val(el.data('discount_on'));--}}
        {{--        // New Fields--}}
        {{--        modal.find('#edit_minimum_quantity').val(el.data('minimum_quantity'));--}}
        {{--        modal.find('#edit_minimum_spend').val(el.data('minimum_spend'));--}}
        {{--        modal.find('#edit_maximum_spend').val(el.data('maximum_spend'));--}}
        {{--        modal.find('#edit_usage_limit_per_coupon').val(el.data('usage_limit_per_coupon'));--}}
        {{--        modal.find('#edit_usage_limit_per_user').val(el.data('usage_limit_per_user'));--}}


        {{--        $('#edit_form_category').hide();--}}
        {{--        $('#edit_form_subcategory').hide();--}}
        {{--        $("#edit_form_childcategory").hide();--}}
        {{--        $('#edit_form_products').hide();--}}

        {{--        if (discount_on == 'product') {--}}
        {{--            $('#edit_form_products').fadeOut();--}}
        {{--            loadProductDiscountHtml($('#edit_discount_on'), '#edit_form_products select', true, discount_on_details);--}}
        {{--        } else {--}}
        {{--            $('#edit_form_' + discount_on + ' option[value=' + discount_on_details + ']').attr('selected', true);--}}
        {{--            $('#edit_form_' + discount_on).fadeIn();--}}
        {{--        }--}}
        {{--    });--}}

        {{--    $('#code').on('keyup', function() {--}}
        {{--        validateCoupon(this);--}}
        {{--    });--}}

        {{--    $('#edit_code').on('keyup', function() {--}}
        {{--        validateCoupon(this);--}}
        {{--    });--}}

        {{--    $('#discount_on').on('change', function () {--}}
        {{--        loadProductDiscountHtml(this, '#form_products select', false, []);--}}
        {{--    });--}}

        {{--    $('#edit_discount_on').on('change', function () {--}}
        {{--        loadProductDiscountHtml(this, '#edit_form_products select', true, []);--}}
        {{--    });--}}
        {{--});--}}

        {{--function loadProductDiscountHtml(context, target_selector, is_edit, values) {--}}
        {{--    let product_select = $(target_selector);--}}

        {{--    let selector_prefix = '';--}}

        {{--    if (is_edit) {--}}
        {{--        selector_prefix = 'edit_';--}}
        {{--    }--}}

        {{--    $('#'+selector_prefix+'form_category').hide();--}}
        {{--    $('#'+selector_prefix+'form_subcategory').hide();--}}
        {{--    $('#'+selector_prefix+'form_childcategory').hide();--}}
        {{--    $('#'+selector_prefix+'form_products').hide();--}}

        {{--    if ($(context).val() == 'category') {--}}
        {{--        $('#'+selector_prefix+'form_category').fadeIn(500);--}}
        {{--    } else if ($(context).val() == 'subcategory') {--}}
        {{--        $('#'+selector_prefix+'form_subcategory').fadeIn(500);--}}
        {{--    }  else if ($(context).val() == 'childcategory') {--}}
        {{--        $('#'+selector_prefix+'form_childcategory').fadeIn(500);--}}
        {{--    } else if ($(context).val() == 'product') {--}}
        {{--        $('.lds-ellipsis').fadeIn();--}}
        {{--        $.get('{{ route("tenant.admin.product.coupon.products") }}').then(function (data) {--}}
        {{--            $('.lds-ellipsis').fadeOut();--}}

        {{--            let options = '';--}}
        {{--            let discountd_products = [];--}}

        {{--            if (values.length) {--}}
        {{--                discountd_products = values;--}}
        {{--            }--}}

        {{--            if (data.length) {--}}
        {{--                data.forEach(function (product) {--}}
        {{--                    let selected_class = '';--}}

        {{--                    if (discountd_products.indexOf(product.id) > -1 || discountd_products.indexOf(String(product.id)) > -1) {--}}
        {{--                        selected_class = 'selected';--}}
        {{--                    }--}}
        {{--                    options += '<option value="'+product.id+'" '+selected_class+'>'+product.name+'</option>';--}}
        {{--                });--}}

        {{--                product_select.html('');--}}
        {{--                product_select.html(options);--}}
        {{--                product_select.parent().show(500);--}}
        {{--                product_select.addClass('nice-select')--}}
        {{--                $('#discount').parent().css('display', 'block');--}}

        {{--                if ($('.nice-select').length) {--}}
        {{--                    if ($('.nice-select.form-control.wide.has-multiple').length) {--}}
        {{--                        $('.nice-select.form-control.wide.has-multiple').remove();--}}
        {{--                    }--}}
        {{--                    $('.nice-select').niceSelect();--}}
        {{--                }--}}
        {{--            }--}}
        {{--        }).catch(function (err) {--}}
        {{--            $('.lds-ellipsis').hide();--}}
        {{--        });--}}
        {{--    }--}}
        {{--}--}}

        {{--function validateCoupon(context) {--}}
        {{--    let code = $(context).val();--}}
        {{--    let submit_btn = $(context).closest('form').find('button[type=submit]');--}}
        {{--    let status_text = $(context).siblings('#status_text');--}}
        {{--    status_text.hide();--}}

        {{--    if (code.length) {--}}
        {{--        submit_btn.prop("disabled", true);--}}

        {{--        $.get("{{ route('tenant.admin.product.coupon.check') }}", {code: code}).then(function (data) {--}}
        {{--            if (data > 0) {--}}
        {{--                let msg = "{{ __('This coupon is already taken') }}";--}}
        {{--                status_text.removeClass('text-success').addClass('text-danger').text(msg).show();--}}
        {{--                submit_btn.prop("disabled", true);--}}
        {{--            } else {--}}
        {{--                let msg = "{{ __('This coupon is available') }}";--}}
        {{--                status_text.removeClass('text-danger').addClass('text-success').text(msg).show();--}}
        {{--                submit_btn.prop("disabled", false);--}}
        {{--            }--}}
        {{--        });--}}
        {{--    }--}}
        {{--}--}}
    </script>
@endsection
