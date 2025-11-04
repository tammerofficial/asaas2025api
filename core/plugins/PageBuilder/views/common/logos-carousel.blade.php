<div class="logos-carousel-section common-logos-carousel-section" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="logos-carousel-wrapper" 
             data-autoplay="{{$data['autoplay'] ? 'true' : 'false'}}"
             data-speed="{{$data['speed']}}">
            <div class="logos-slider">
                @if(!empty($data['logo_ids']) && count($data['logo_ids']) > 0)
                    @foreach($data['logo_ids'] as $logo_id)
                        <div class="logo-item">
                            {!! render_image_markup_by_attachment_id(trim($logo_id), 'medium', false, false, false) !!}
                        </div>
                    @endforeach
                    {{-- Duplicate logos for seamless loop --}}
                    @foreach($data['logo_ids'] as $logo_id)
                        <div class="logo-item">
                            {!! render_image_markup_by_attachment_id(trim($logo_id), 'medium', false, false, false) !!}
                        </div>
                    @endforeach
                @else
                    <p class="text-center text-muted">{{__('No logos uploaded yet')}}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.logos-carousel-wrapper {
    overflow: hidden;
}
.logos-slider {
    display: flex;
    gap: 40px;
    animation: slide 30s linear infinite;
}
.logos-slider:hover {
    animation-play-state: paused;
}
.logo-item {
    flex-shrink: 0;
    opacity: 0.6;
    transition: opacity 0.3s;
}
.logo-item:hover {
    opacity: 1;
}
.logo-item img {
    max-height: 60px;
    width: auto;
    object-fit: contain;
}
@keyframes slide {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}
</style>

