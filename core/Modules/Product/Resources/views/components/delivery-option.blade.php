@php
    if(!isset($selectedDeliveryOption)){
        $selectedDeliveryOption = [];
    }
@endphp

<div class="general-info-wrapper mt-4">
    <h4 class="dashboard-common-title-two mb-4">{{ __("Delivery Options") }}</h4>
    <div class="general-info-form mt-0 mt-lg-4">
        <div class="d-flex flex-wrap gap-2">
            <input type="hidden" value="{{ implode(" , ", $selectedDeliveryOption) }}" name="delivery_option" class="delivery-option-input" />

            @foreach($deliveryOptions as $deliveryOption)
                <div class="delivery-item flex-wrap d-flex {{ in_array($deliveryOption->id, $selectedDeliveryOption) ? "active" : "" }}" data-delivery-option-id="{{ $deliveryOption->id }}">
                    <div class="icon">
                        <i class="{{ $deliveryOption->icon }}"></i>
                    </div>
                    <div class="content">
                        <h6 class="title">{{ $deliveryOption->title }}</h6>
                        <p>{{ $deliveryOption->sub_title }}</p>
                    </div>
                </div>
            @endforeach
        </div>
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
