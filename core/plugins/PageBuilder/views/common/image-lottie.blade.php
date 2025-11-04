<div class="image-lottie-section common-image-lottie-section" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if(!empty($data['lottie_json_url']))
                    <div class="lottie-container" 
                         data-animation-type="{{$data['animation_type']}}"
                         data-animation-speed="{{$data['animation_speed']}}">
                        <lottie-player 
                            src="{{$data['lottie_json_url']}}" 
                            background="transparent" 
                            speed="1" 
                            style="width: 100%; height: auto;" 
                            loop 
                            autoplay>
                        </lottie-player>
                    </div>
                @elseif(!empty($data['image']))
                    <div class="image-container" 
                         data-animation-type="{{$data['animation_type']}}"
                         data-animation-speed="{{$data['animation_speed']}}">
                        {!! render_image_markup_by_attachment_id($data['image'], 'full') !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(!empty($data['lottie_json_url']))
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
@endif

<style>
.image-container img,
.lottie-container {
    width: 100%;
    height: auto;
    transition: all {{$data['animation_speed']}}ms ease;
}

@if($data['animation_type'] === 'fade')
    .image-container,
    .lottie-container {
        animation: fadeIn {{$data['animation_speed']}}ms ease;
    }
@elseif($data['animation_type'] === 'slide')
    .image-container,
    .lottie-container {
        animation: slideIn {{$data['animation_speed']}}ms ease;
    }
@elseif($data['animation_type'] === 'zoom')
    .image-container,
    .lottie-container {
        animation: zoomIn {{$data['animation_speed']}}ms ease;
    }
@elseif($data['animation_type'] === 'bounce')
    .image-container,
    .lottie-container {
        animation: bounceIn {{$data['animation_speed']}}ms ease;
    }
@endif

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@keyframes zoomIn {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

@keyframes bounceIn {
    0% { transform: scale(0.3); opacity: 0; }
    50% { transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { transform: scale(1); opacity: 1; }
}
</style>

