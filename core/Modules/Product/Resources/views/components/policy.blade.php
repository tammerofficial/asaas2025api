<div class="general-info-wrapper mt-4">
    <h4 class="dashboard-common-title-two">{{ __("Product Shipping and Return Policy") }}</h4>
    <div class="dashboard-input mt-4">
        <label class="dashboard-label color-light mb-2"> {{ __("Policy Description") }} </label>
        <textarea class="form--control summernote radius-10" name="policy_description">{!! isset($product) ? purify_html($product?->return_policy?->shipping_return_description) : "" !!}</textarea>
    </div>
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
