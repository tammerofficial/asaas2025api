@php
    if(!isset($inventorydetails)){
        $inventorydetails = [];
    }
@endphp

<div class="variant_info_container">
    <h5 class="dashboard-common-title-two mb-3">{{ __('Custom Inventory variant') }}</h5>
    <p class="mb-4">
        {{ __('Inventory will be variant of this product.') }} <br>
        {{ __('All inventory stock count will be merged and replace to main stock of this product.') }}<br>
        {{ __('Stock count filed is required.') }}
    </p>
    <div class="inventory_items_container">
        @forelse($inventorydetails as $key => $detail)
            <x-product::variant-info.repeater
                :detail="$detail"
                :is-first='false' :colors="$colors" :sizes="$sizes"
                :all-available-attributes="$allAttributes" :key="$key" />
        @empty
            <x-product::variant-info.repeater
                    :is-first="true" :colors="$colors" :sizes="$sizes"
                    :all-available-attributes="$allAttributes" />
        @endforelse
    </div>
</div>
{{-- Custom Specifications Component --}}
<div class="form-section">
    <div class="form-section-title">
        <i class="las la-tags me-2"></i>{{ __('Custom Product Specifications') }}
        <small class="text-muted">({{ __('Optional') }})</small>
    </div>

    <div class="custom_specifications_container">

        @php
            $specifications = collect();

            if (old('custom_spec_title')) {
                $specifications = collect(old('custom_spec_title'))->map(function ($title, $i) {
                    return [
                        'title' => $title,
                        'value' => old('custom_spec_value')[$i] ?? ''
                    ];
                });
            } elseif (isset($product) && $product->custom_specifications) {
                $specifications = $product->custom_specifications->map(function($spec) {
                    return [
                        'title' => $spec->title,
                        'value' => $spec->value,
                    ];
                });
            }
        @endphp

        {{--        @dd($specifications)--}}

        @forelse($specifications as $index => $spec)
            <div class="custom_specification_item shadow-sm mb-3 rounded p-3"
                 style="border: 1px solid rgba(255,128,93,0.26) !important;"
                 data-id="{{ $index }}">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Specification Title') }}</label>
                            <input type="text"
                                   name="custom_spec_title[]"
                                   class="form-control"
                                   value="{{ $spec['title'] ?? '' }}"
                                   placeholder="{{ __('e.g., Fabric Type, Care Instructions') }}"
                                   maxlength="100">
                            <small class="text-muted">{{ __('Max 100 characters') }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('Specification Value') }}</label>
                            <input name="custom_spec_value[]"
                                      class="form-control"
                                      placeholder="{{ __('e.g., 65% Cotton, 33% Polyester, 2% Elastane') }}"
                                   value="{{ $spec['value'] ?? '' }}"
                                      maxlength="500">
                            <small class="text-muted">{{ __('Max 500 characters') }}</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success btn-sm add_custom_spec">
                                    <i class="las la-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm remove_custom_spec" {{ $loop->first ? 'style=display:none;' : '' }}>
                                    <i class="las la-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="custom_specification_item shadow-sm mb-3 rounded p-3"
                 style="border: 1px solid rgba(255,128,93,0.26) !important;"
                 data-id="0">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Specification Title') }}</label>
                            <input type="text"
                                   name="custom_spec_title[]"
                                   class="form-control"
                                   placeholder="{{ __('e.g., Fabric Type, Care Instructions') }}"
                                   maxlength="100">
                            <small class="text-muted">{{ __('Max 100 characters') }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('Specification Value') }}</label>
                            <input name="custom_spec_value[]"
                                      class="form-control"
                                      placeholder="{{ __('e.g., 65% Cotton, 33% Polyester, 2% Elastane') }}"
                                      maxlength="500">
                            <small class="text-muted">{{ __('Max 500 characters') }}</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success btn-sm add_custom_spec">
                                    <i class="las la-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm remove_custom_spec" style="display: none;">
                                    <i class="las la-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="alert alert-success mt-3">
        <i class="las la-info-circle me-2"></i>
        <strong>{{ __('Tip:') }}</strong>
        {{ __('Add custom specifications like fabric type, care instructions, origin, closure type, etc. These will be displayed on the product detail page.') }}
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
