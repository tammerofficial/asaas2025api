{{--<div class="image-product-wrapper">--}}
{{--    @if(isset($product))--}}
{{--        <x-fields.media-upload :id="$product->image_id" :title="__('Feature Image')" :name="'image_id'"--}}
{{--                               :dimentions="'200x200'"/>--}}
{{--        <span class="d-block pt-2 text-danger image_id-error"></span>--}}


{{--        @php--}}
{{--            if (!is_null($product->gallery_images))--}}
{{--            {--}}
{{--                $image_arr = optional($product->gallery_images)->toArray();--}}
{{--            $gallery = '';--}}
{{--            foreach ($image_arr as $key => $arr)--}}
{{--                {--}}
{{--                    $gallery .= $arr['id'];--}}
{{--                    if ($key != count($image_arr)-1)--}}
{{--                        {--}}
{{--                            $gallery .= '|';--}}
{{--                        }--}}
{{--                }--}}
{{--            }--}}
{{--        @endphp--}}
{{--        <x-landlord-others.edit-media-upload-gallery :label="'Image Gallery'" :name="'product_gallery'"--}}
{{--                                                     :value="$gallery ?? ''"/>--}}
{{--    @else--}}
{{--        <x-fields.media-upload :title="__('Feature Image')" :name="'image_id'" :dimentions="'200x200'"/>--}}
{{--        <span class="text-danger image_id-error"></span>--}}

{{--        <x-landlord-others.edit-media-upload-gallery :label="'Image Gallery'" :name="'product_gallery'"--}}
{{--                                                     :value="$gallery ?? ''"/>--}}
{{--    @endif--}}
{{--</div>--}}

{{-- FIX 3: Updated product-image.blade.php with better validation --}}
<div class="image-product-wrapper">
    <div class="form-section">
{{--        <h5 class="form-section-title">{{ __('Product Images') }}</h5>--}}

        @if(isset($product))
            <div id="image_id_section">
                <x-fields.media-upload
                    :id="$product->image_id"
                    :title="__('Feature Image')"
                    :name="'image_id'"
                    :dimentions="'200x200'"
                />
            </div>
            <span class="d-block pt-2 text-danger image_id-error"></span>

            @php
                if (!is_null($product->gallery_images)) {
                    $image_arr = optional($product->gallery_images)->toArray();
                    $gallery = '';
                    foreach ($image_arr as $key => $arr) {
                        $gallery .= $arr['id'];
                        if ($key != count($image_arr)-1) {
                            $gallery .= '|';
                        }
                    }
                }
            @endphp
            <x-landlord-others.edit-media-upload-gallery
                :label="'Image Gallery'"
                :name="'product_gallery'"
                :value="$gallery ?? ''"
            />
        @else
            <div id="image_id_section">
                <x-fields.media-upload
                    :title="__('Feature Image')"
                    :name="'image_id'"
                    :dimentions="'200x200'"
                />
            </div>
            <span class="text-danger image_id-error"></span>

            <x-landlord-others.edit-media-upload-gallery
                :label="'Image Gallery'"
                :name="'product_gallery'"
                :value="''"
            />
        @endif
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
