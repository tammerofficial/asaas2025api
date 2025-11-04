<!-- Salons Gallery Slider - Before/After -->
<section class="salons-gallery-section" id="gallery">
    <div class="container">
        <div class="salons-section-title">
            <h2>{{ __('معرض أعمالنا') }}</h2>
            <p>{{ __('شاهد تحولات عملائنا الجميلة') }}</p>
        </div>
        
        <div class="row">
            @php
                $gallery = [
                    [
                        'image' => 'gallery/before-after/image-1.jpg',
                        'category' => __('قص وتلوين'),
                    ],
                    [
                        'image' => 'gallery/before-after/image-2.jpg',
                        'category' => __('مكياج'),
                    ],
                    [
                        'image' => 'gallery/before-after/image-3.jpg',
                        'category' => __('فايسيال'),
                    ],
                    [
                        'image' => 'gallery/before-after/image-4.jpg',
                        'category' => __('تلوين شعر'),
                    ],
                    [
                        'image' => 'gallery/before-after/image-5.jpg',
                        'category' => __('مناكير'),
                    ],
                    [
                        'image' => 'gallery/before-after/image-6.jpg',
                        'category' => __('سبا'),
                    ],
                ];
            @endphp
            
            @foreach($gallery as $item)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="salons-gallery-item" onclick="openLightbox('{{ theme_assets('img/' . $item['image'], 'salons') }}')">
                        <img src="{{ theme_assets('img/' . $item['image'], 'salons') }}" 
                             alt="{{ $item['category'] }}"
                             onerror="this.src='https://via.placeholder.com/1200x800/D4AF37/E91E63?text={{ $item['category'] }}'">
                        <div class="salons-gallery-overlay">
                            <span class="salons-gallery-category">{{ $item['category'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="modal fade" id="gallery-lightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <img src="" id="lightbox-image" class="img-fluid" alt="Gallery Image">
            </div>
        </div>
    </div>
</div>

<script>
function openLightbox(imageSrc) {
    document.getElementById('lightbox-image').src = imageSrc;
    const lightbox = new bootstrap.Modal(document.getElementById('gallery-lightbox'));
    lightbox.show();
}
</script>

