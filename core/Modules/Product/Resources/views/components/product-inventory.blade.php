@php
    if(!isset($inventory)){
        $inventory = null;
    }

    if(!isset($uom)){
        $uom = null;
    }
@endphp

<h4 class="dashboard-common-title-two"> {{ __("Product Inventory") }} </h4>


<div class="dashboard-input mt-4">
    @if(!$inventory)
        <p>{{__('A barcode will be generated after creating a SKU')}}</p>
    @endif
    @if($inventory)
        <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($inventory?->sku, 'C39+')}}" alt="">
    @endif
</div>

<div class="dashboard-input mt-4">
    <label class="dashboard-label color-light mb-2"> {{ __("SKU") }}<x-fields.mandatory-indicator/> </label>
    <input type="text" class="form--control radius-10" name="sku" value="{{ $inventory?->sku }}">
    <small>{{ __("Custom Unique Code for this product.") }}</small>
    <span class="d-block pt-2 text-danger sku-error"></span>

</div>

<div class="dashboard-input mt-4">
    <label class="dashboard-label color-light mb-2"> {{ __("Quantity") }} </label>
    <input type="number" class="form--control radius-10" name="quantity" value="{{ $inventory?->stock_count }}">
    <small>{{ __("This will be replaced with the sum of inventory items. if any inventory  item is registered.") }}</small>
    <span class="d-block pt-2 text-danger quantity-error"></span>

</div>

<div class="dashboard-input mt-4">
    <label class="dashboard-label color-light mb-2"> {{ __("Unit") }}<x-fields.mandatory-indicator/> </label>

    <div class="nice-select-two">
        <select name="unit_id" class="form--control">
            <option value="">{{ __("Select Unit") }}</option>
            @foreach($units as $unit)
                <option
                    value="{{ $unit->id }}"
                    {{ old('unit_id', $uom?->unit_id) == $unit->id ? 'selected' : '' }}>
                    {{ $unit->name }}
                </option>
            @endforeach
        </select>
        <small>{{ __("Select Unit") }}</small>
    </div>
    <span class="d-block pt-2 text-danger unit_id-error"></span>

</div>

<div class="dashboard-input mt-4">
    <label class="dashboard-label color-light mb-2"> {{ __("Unit Of Measurement") }}<x-fields.mandatory-indicator/> </label>
    <input type="number" name="uom" class="form--control radius-10" value="{{ $uom?->quantity }}"
           placeholder="{{ __("Enter Unit Of Measurement") }}">
    <small>{{ __("Enter the number here") }}</small>
    <span class="d-block pt-2 text-danger uom-error"></span>

</div>

