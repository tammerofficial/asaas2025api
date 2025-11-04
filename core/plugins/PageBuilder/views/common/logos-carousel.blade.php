<div class="logos-carousel-section common-logos-carousel-section" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="logos-carousel-wrapper" 
             data-autoplay="{{$data['autoplay'] ? 'true' : 'false'}}"
             data-speed="{{$data['speed']}}">
            <div class="logos-slider">
                @if(array_key_exists('repeater_logo_image_', $data['repeater_data'] ?? []))
                    @foreach($data['repeater_data']['repeater_logo_image_'] as $key => $logo_image)
                        <div class="logo-item">
                            @if(!empty($data['repeater_data']['repeater_link_'][$key] ?? ''))
                                <a href="{{$data['repeater_data']['repeater_link_'][$key]}}" target="_blank">
                            @endif
                            
                            {!! render_image_markup_by_attachment_id($logo_image, 'medium', false, false, false) !!}
                            
                            @if(!empty($data['repeater_data']['repeater_link_'][$key] ?? ''))
                                </a>
                            @endif
                        </div>
                    @endforeach
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

