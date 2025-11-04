@if($data['background_video'])
    {{-- Section Background Video with Overlay --}}
    <section class="video-box-section common-video-box-section video-section-background" 
         data-padding-top="{{$data['padding_top']}}" 
         data-padding-bottom="{{$data['padding_bottom']}}" 
         id="{{$data['section_id']}}">
        <div class="video-background-wrapper">
            <div class="video-iframe-background">
                <iframe src="{{$data['video_url']}}" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
            </div>
            
            @if($data['gradient_overlay'])
                <div class="video-gradient-overlay" style="opacity: {{$data['overlay_opacity'] / 100}};"></div>
            @endif
            
            {{-- Content can be added here if needed --}}
            <div class="video-content-wrapper" style="position: relative; z-index: 3;">
                <!-- Content goes here -->
            </div>
        </div>
    </section>
@else
    {{-- Regular Video Box --}}
    <div class="video-box-section common-video-box-section" 
         data-padding-top="{{$data['padding_top']}}" 
         data-padding-bottom="{{$data['padding_bottom']}}" 
         id="{{$data['section_id']}}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="video-wrapper">
                        @if(!empty($data['thumbnail']) && !$data['autoplay'])
                            <div class="video-thumbnail" onclick="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                {!! render_image_markup_by_attachment_id($data['thumbnail'], 'full') !!}
                                <div class="play-button">
                                    <i class="las la-play"></i>
                                </div>
                            </div>
                        @endif
                        
                        <div class="video-iframe" style="{{!empty($data['thumbnail']) && !$data['autoplay'] ? 'display:none;' : ''}}">
                            <iframe src="{{$data['video_url']}}{{$data['autoplay'] ? '?autoplay=1' : ''}}{{!$data['controls'] ? '&controls=0' : ''}}" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<style>
/* Regular Video Box Styles */
.video-wrapper {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
    border-radius: 10px;
}
.video-iframe iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.video-thumbnail {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}
.video-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--main-color-one, #007bff);
    transition: all 0.3s;
}
.play-button:hover {
    transform: translate(-50%, -50%) scale(1.1);
}

/* Section Background Video Styles */
.video-section-background {
    position: relative;
    width: 100%;
    min-height: 500px;
    overflow: hidden;
}

.video-background-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    min-height: 500px;
}

.video-iframe-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    pointer-events: none;
}

.video-iframe-background iframe {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100vw;
    height: 56.25vw; /* 16:9 aspect ratio */
    min-height: 100%;
    min-width: 177.77vh; /* 16:9 aspect ratio */
    pointer-events: none;
}

/* Gradient Overlay */
.video-gradient-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 1) 0%,
        rgba(0, 0, 0, 0) 20%,
        rgba(0, 0, 0, 0) 80%,
        rgba(0, 0, 0, 1) 100%
    );
    z-index: 2;
    pointer-events: none;
}

/* Content wrapper for elements above video */
.video-content-wrapper {
    position: relative;
    z-index: 3;
    min-height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

