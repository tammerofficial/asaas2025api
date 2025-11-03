<?php
    if(!isset($product)){
        $product = null;
    }
?>

<div class="general-info-wrapper">
    <h4 class="dashboard-common-title-two"> {{__('Price Manage')}} </h4>
    <div class="general-info-form mt-0 mt-lg-4">
        <div class="dashboard-input mt-4">
            <label class="dashboard-label color-light mb-2"> {{ __("Base Cost") }} </label>
            <input type="number" class="form--control radius-10" value="{{ $product?->cost }}" name="cost" placeholder="{{ __("Base Cost...") }}" step="0.01">
            <p>{{ __("Purchase price of this product.") }}</p>
        </div>

        <div class="dashboard-input mt-4">
            <label class="dashboard-label color-light mb-2"> {{ __("Regular Price") }}</label>
                <input type="number" class=" form--control radius-10" value="{{ $product?->price }}" name="price" placeholder="{{ __("Enter Regular Price...") }}" step="0.01">
            <small>{{ __("This price will display like this") }} <del>( {{ site_currency_symbol() }} 10)</del></small>
            <br>
            <span class="d-block pt-2 text-danger price-error"></span>

        </div>

        <div class="dashboard-input mt-4">
            <label class="dashboard-label color-light mb-2"> {{ __("Sale Price") }}<x-fields.mandatory-indicator/></label>
            <input type="number" class="form--control radius-10" value="{{ $product?->sale_price }}" name="sale_price" placeholder="{{ __("Enter Sale Price...") }}" step="0.01">
            <small>{{ __("This will be your product selling price") }}</small>
        </div>

        @if($product)
            <div class="dashboard-input mt-4">
                <div class="row">
                    <div class="col-6 is_taxable_wrapper">
                        <label class="dashboard-label color-light mb-2"> {{ __("Is Taxable?") }}</label>
                        <select name="is_taxable" class="form--control radius-10">
                            <option @selected($product->is_taxable == 0) value="no">{{__('No')}}</option>
                            <option @selected($product->is_taxable == 1) value="yes">{{__('Yes')}}</option>
                        </select>
                    </div>

                    <div class="col-6 tax_classes_wrapper" @style(['display: none' => !$product->is_taxable])>
                        <label class="dashboard-label color-light mb-2"> {{ __("Tax classes") }}</label>
                        <select name="tax_class" class="form--control radius-10">
                            <option value="">{{__('Select an option')}}</option>
                            @foreach($taxClasses ?? [] as $class)
                                <option @selected($product->tax_class_id == $class->id) value="{{$class->id}}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @else
            <div class="dashboard-input mt-4">
                <div class="row">
                    <div class="col-6 is_taxable_wrapper">
                        <label class="dashboard-label color-light mb-2"> {{ __("Is Taxable?") }}</label>
                        <select name="is_taxable" class="form--control radius-10">
                            <option value="no">{{__('No')}}</option>
                            <option value="yes">{{__('Yes')}}</option>
                        </select>
                    </div>

                    <div class="col-6 tax_classes_wrapper" style="display:none">
                        <label class="dashboard-label color-light mb-2"> {{ __("Tax classes") }}<x-fields.mandatory-indicator/></label>
                        <select name="tax_class" class="form--control radius-10">
                            <option value="">{{__('Select an option')}}</option>
                            @foreach($taxClasses ?? [] as $class)
                                <option value="{{$class->id}}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        <span class="d-block pt-2 text-danger tax_class-error"></span>

                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="navigation-buttons mt-4 d-flex justify-content-between">
        <button type="button" class="btn prev-step btn-info btn-sm">
            <i class="las la-arrow-left"></i> {{ __('Previous') }}
        </button>

        <button type="button" class="btn btn-gradient-primary next-step">
           {{__(' Next')}} <i class="las la-arrow-right"></i>
        </button>

        <button type="submit" class="btn btn-success submit-form" style="display: none;">
            <i class="las la-check"></i> Create Product
        </button>
    </div>
</div>
