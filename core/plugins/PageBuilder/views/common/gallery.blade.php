<div class="gallery-section common-gallery-section gallery-{{$data['layout']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}"
     style="--gallery-spacing: {{$data['spacing']}}px;">
    <div class="container">
        @if(array_key_exists('repeater_image_', $data['repeater_data'] ?? []))
            <div class="gallery-wrapper gallery-{{$data['layout']}}">
                @foreach($data['repeater_data']['repeater_image_'] as $key => $image)
                    @php
                        $title = $data['repeater_data']['repeater_title_'][$key] ?? '';
                        $description = $data['repeater_data']['repeater_description_'][$key] ?? '';
                        $link = $data['repeater_data']['repeater_link_'][$key] ?? '';
                        $unique_id = 'gallery-item-' . $data['section_id'] . '-' . $key;
                    @endphp
                    <div class="gallery-item gallery-col-{{$data['columns']}}">
                        <div class="gallery-item-inner">
                            @if($data['lightbox'] === 'yes')
                                <a href="{{$image}}" 
                                   class="gallery-link" 
                                   data-lightbox="gallery-{{$data['section_id']}}"
                                   @if(!empty($title)) data-title="{{$title}}" @endif>
                                    <img src="{{$image}}" 
                                         alt="{{$title}}" 
                                         class="gallery-image">
                                    <div class="gallery-overlay">
                                        @if(!empty($title))
                                            <h5 class="gallery-title">{{$title}}</h5>
                                        @endif
                                        @if(!empty($description))
                                            <p class="gallery-description">{{$description}}</p>
                                        @endif
                                        <i class="las la-search-plus gallery-icon"></i>
                                    </div>
                                </a>
                            @elseif(!empty($link))
                                <a href="{{$link}}" class="gallery-link">
                                    <img src="{{$image}}" 
                                         alt="{{$title}}" 
                                         class="gallery-image">
                                    <div class="gallery-overlay">
                                        @if(!empty($title))
                                            <h5 class="gallery-title">{{$title}}</h5>
                                        @endif
                                    </div>
                                </a>
                            @else
                                <div class="gallery-image-wrapper">
                                    <img src="{{$image}}" 
                                         alt="{{$title}}" 
                                         class="gallery-image">
                                    @if(!empty($title) || !empty($description))
                                        <div class="gallery-overlay">
                                            @if(!empty($title))
                                                <h5 class="gallery-title">{{$title}}</h5>
                                            @endif
                                            @if(!empty($description))
                                                <p class="gallery-description">{{$description}}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
.gallery-section {
    padding: 40px 0;
}

.gallery-wrapper {
    display: grid;
    gap: var(--gallery-spacing);
    width: 100%;
}

/* Grid Layout */
.gallery-grid {
    grid-template-columns: repeat({{$data['columns']}}, 1fr);
}

/* Masonry Layout */
.gallery-masonry {
    column-count: {{$data['columns']}};
    column-gap: var(--gallery-spacing);
}

.gallery-masonry .gallery-item {
    break-inside: avoid;
    margin-bottom: var(--gallery-spacing);
}

/* Carousel Layout */
.gallery-carousel {
    display: flex;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
}

.gallery-carousel .gallery-item {
    flex: 0 0 calc(100% / {{$data['columns']}} - var(--gallery-spacing));
    scroll-snap-align: start;
}

.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
}

.gallery-item-inner {
    position: relative;
    width: 100%;
    height: 100%;
}

.gallery-image-wrapper,
.gallery-link {
    display: block;
    width: 100%;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
    display: block;
}

.gallery-item:hover .gallery-image {
    transform: scale(1.1);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    padding: 20px;
    text-align: center;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-title {
    color: #fff;
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0 0 10px 0;
}

.gallery-description {
    color: #fff;
    font-size: 0.9rem;
    margin: 0;
    line-height: 1.5;
}

.gallery-icon {
    font-size: 2rem;
    color: #fff;
    margin-top: 10px;
}

@media (max-width: 991px) {
    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .gallery-masonry {
        column-count: 2;
    }
}

@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: 1fr;
    }
    
    .gallery-masonry {
        column-count: 1;
    }
    
    .gallery-carousel .gallery-item {
        flex: 0 0 100%;
    }
}
</style>

<script>
// Simple lightbox functionality
document.addEventListener('DOMContentLoaded', function() {
    const galleryLinks = document.querySelectorAll('[data-lightbox]');
    
    galleryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const imageSrc = this.getAttribute('href');
            const imageTitle = this.getAttribute('data-title') || '';
            
            // Create lightbox modal
            const lightbox = document.createElement('div');
            lightbox.className = 'gallery-lightbox';
            lightbox.innerHTML = `
                <div class="lightbox-overlay" onclick="this.closest('.gallery-lightbox').remove()"></div>
                <div class="lightbox-content">
                    <button class="lightbox-close" onclick="this.closest('.gallery-lightbox').remove()">&times;</button>
                    <img src="${imageSrc}" alt="${imageTitle}">
                    ${imageTitle ? `<p class="lightbox-title">${imageTitle}</p>` : ''}
                </div>
            `;
            
            document.body.appendChild(lightbox);
            document.body.style.overflow = 'hidden';
            
            lightbox.querySelector('.lightbox-close').addEventListener('click', function() {
                lightbox.remove();
                document.body.style.overflow = '';
            });
        });
    });
});
</script>

<style>
.gallery-lightbox {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.lightbox-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.9);
}

.lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
    z-index: 10000;
}

.lightbox-content img {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 8px;
}

.lightbox-close {
    position: absolute;
    top: -40px;
    right: 0;
    background: none;
    border: none;
    color: #fff;
    font-size: 2rem;
    cursor: pointer;
    padding: 0;
    width: 40px;
    height: 40px;
    line-height: 40px;
}

.lightbox-title {
    color: #fff;
    text-align: center;
    margin-top: 15px;
    font-size: 1.2rem;
}
</style>

