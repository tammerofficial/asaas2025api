@php
    if(!isset($product)){
        $product = null;
    }
@endphp

<div class="general-info-wrapper">
    <h4 class="dashboard-common-title-two"> {{ __("General Information") }} </h4>
    {{-- Add this to your general-info.blade.php and other components --}}
    <div class="general-info-form">
        <form action="#">
            <div class="dashboard-input mt-4">
                <label class="dashboard-label color-light mb-2"> {{ __("Name") }}<x-fields.mandatory-indicator/></label>
                <input type="text" class="form--control radius-10" id="product-name" value="{!! $product?->name ?? "" !!}" name="name" placeholder="{{ __("Write product Name...") }}" required>
                <span class="d-block pt-2 text-danger name-error"></span>
            </div>
{{--            <div class="dashboard-input mt-4">--}}
{{--                <label for="product-slug" class="form-label">--}}
{{--                    {{ __("Slug") }}<x-fields.mandatory-indicator/>--}}
{{--                    <i class="las la-info-circle" data-bs-toggle="tooltip" title="URL-friendly version of the name"></i>--}}
{{--                </label>--}}

{{--                <div class="slug-field-wrapper">--}}
{{--                    <!-- Display Mode -->--}}
{{--                    <div class="slug-display" id="slug-display">--}}
{{--                        {{ $product->slug ?? '' }}--}}
{{--                    </div>--}}

{{--                    <!-- Edit Mode (Hidden by default) -->--}}
{{--                    <input type="text"--}}
{{--                           class="form-control slug-input"--}}
{{--                           id="product-slug"--}}
{{--                           name="slug"--}}
{{--                           value="{{ $product->slug ?? '' }}"--}}
{{--                           style="display: none;"--}}
{{--                           pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$"--}}
{{--                           title="Slug can only contain lowercase letters, numbers, and hyphens">--}}

{{--                    <!-- Edit Icon -->--}}
{{--                    <button type="button" class="slug-edit-icon" id="slug-edit-btn" title="Edit slug">--}}
{{--                        <i class="las la-edit"></i>--}}
{{--                    </button>--}}

{{--                    <!-- Save/Cancel Actions (Hidden by default) -->--}}
{{--                    <div class="slug-actions" style="display:none;">--}}
{{--                        <button type="button" class="slug-action-btn save" id="slug-save-btn" title="Save">--}}
{{--                            <i class="las la-check"></i>--}}
{{--                        </button>--}}
{{--                        <button type="button" class="slug-action-btn cancel" id="slug-cancel-btn" title="Cancel">--}}
{{--                            <i class="las la-times"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Slug Preview -->--}}
{{--                <div class="slug-preview">--}}
{{--                    {{__('URL will be: ')}}<strong id="slug-url-preview">{{ url('/product/' . ($product->slug ?? '')) }}</strong>--}}
{{--                </div>--}}
{{--            </div>--}}

            {{-- FIX 1: Updated general-info.blade.php slug section --}}
            <div class="dashboard-input mt-4">
                <label for="product-slug" class="form-label">
                    {{ __("Slug") }}<x-fields.mandatory-indicator/>
                    <i class="las la-info-circle" data-bs-toggle="tooltip" title="URL-friendly version of the name"></i>
                </label>

                <div class="slug-field-wrapper">
                    <!-- Display Mode -->
                    <div class="slug-display" id="slug-display">
                        {{ $product->slug ?? '' }}
                    </div>

                    <!-- Edit Mode (Hidden by default) -->
{{--                    <input type="text"--}}
{{--                           class="form-control slug-input"--}}
{{--                           id="product-slug"--}}
{{--                           name="slug"--}}
{{--                           value="{{ $product->slug ?? '' }}"--}}
{{--                           style="display: none;"--}}
{{--                           pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$"--}}
{{--                           title="Slug can only contain lowercase letters, numbers, and hyphens">--}}
                    <input type="text"
                           class="form-control slug-input"
                           id="product-slug"
                           name="slug"
                           value="{{ $product->slug ?? '' }}"
                           style="display: none;"
                           pattern="^[\p{L}\p{N}]+(?:-[\p{L}\p{N}]+)*$"
                           title="Slug can only contain letters (any language), numbers, and hyphens"
                           lang="{{ app()->getLocale() }}">

                    <!-- Edit Icon -->
                    <button type="button" class="slug-edit-icon" id="slug-edit-btn" title="Edit slug">
                        <i class="las la-edit"></i>
                    </button>

                    <!-- Save/Cancel Actions (Hidden by default) -->
                    <div class="slug-actions" style="display:none;">
                        <button type="button" class="slug-action-btn save" id="slug-save-btn" title="Save">
                            <i class="las la-check"></i>
                        </button>
                        <button type="button" class="slug-action-btn cancel" id="slug-cancel-btn" title="Cancel">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Fixed Slug Preview with proper URL generation -->
                <div class="slug-preview">
                    {{ __('URL will be: ') }}<strong id="slug-url-preview">{{ request()->getSchemeAndHttpHost() }}/product/{{ $product->slug ?? 'your-product-slug' }}</strong>
                </div>
            </div>


            <div class="dashboard-input mt-4">
                <label class="dashboard-label color-light mb-2"> {{ __("Summary") }} </label>
                <textarea style="height: 120px" class="form--control form--message  radius-10"  name="summery" placeholder="{{ __("Write product Summary...") }}">{!! $product?->summary ?? "" !!} </textarea>
                <span class="d-block pt-2 text-danger summery-error"></span>

            </div>
            <div class="dashboard-input mt-4">
                <label class="dashboard-label color-light mb-2"> {{ __("Description") }}<x-fields.mandatory-indicator/> </label>
                <textarea class="form--control summernote radius-10" name="description" required>{!! $product?->description ?? "" !!}</textarea>
                <div class=" text-secondary mt-1 small">{{ __('Description must be at least 10 characters long.') }}</div>
                <span class="d-block pt-2 text-danger description-error"></span>
            </div>

            <div class="dashboard-input mt-4">
                <label class="dashboard-label color-light mb-2"> {{ __("Brand") }}</label>
                <div class="nice-select-two">
                    <select name="brand" class="form-control" id="brand_id">
                        <option value="">{{ __("Select brand") }}</option>
                        @foreach($brands as $item)
                            <option {{ $item->id == $product?->brand_id ? "selected" : "" }} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            {{-- FIX 4: Updated navigation buttons with proper validation states --}}
            <div class="navigation-buttons mt-4 d-flex justify-content-between">
                <button type="button" class="btn prev-step btn-info btn-sm">
                    <i class="las la-arrow-left"></i> {{ __('Previous') }}
                </button>

                <button type="button" class="btn btn-gradient-primary next-step">
                    {{__(' Next')}} <i class="las la-arrow-right"></i>
                </button>

                <button type="submit" class="btn btn-success submit-form" style="display: none;">
                    <i class="las la-check"></i> {{ isset($product) ? __('Update Product') : __('Create Product') }}
                </button>
            </div>
        </form>

    </div>
</div>
