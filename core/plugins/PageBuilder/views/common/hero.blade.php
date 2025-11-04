<section class="hero-section common-hero-section" 
         data-padding-top="{{$data['padding_top']}}" 
         data-padding-bottom="{{$data['padding_bottom']}}" 
         id="{{$data['section_id']}}"
         @if(!empty($data['background_image']))
            {!! render_background_image_markup_by_attachment_id($data['background_image'], 'full') !!}
         @endif
         style="position: relative;">
    @if(!empty($data['overlay_color']))
        <div class="hero-overlay" 
             style="background-color: {{$data['overlay_color']}}; 
                    opacity: {{($data['overlay_opacity'] ?? 50) / 100}}; 
                    position: absolute; 
                    top: 0; 
                    left: 0; 
                    width: 100%; 
                    height: 100%; 
                    z-index: 1;">
        </div>
    @endif
    <div class="container" style="position: relative; z-index: 2;">
        <div class="row justify-content-{{$data['content_align'] === 'left' ? 'start' : ($data['content_align'] === 'right' ? 'end' : 'center')}}">
            <div class="col-lg-{{$data['content_align'] === 'center' ? '8' : '12'}} text-{{$data['content_align']}}">
                @if(!empty($data['title']))
                    <h1 class="hero-title">{{$data['title']}}</h1>
                @endif
                
                @if(!empty($data['subtitle']))
                    <h2 class="hero-subtitle">{{$data['subtitle']}}</h2>
                @endif
                
                @if(!empty($data['description']))
                    <p class="hero-description">{{$data['description']}}</p>
                @endif
                
                <div class="hero-buttons mt-4">
                    @if(!empty($data['button_text']) && !empty($data['button_url']))
                        <a href="{{$data['button_url']}}" class="btn btn-primary hero-primary-btn">
                            {{$data['button_text']}}
                        </a>
                    @endif
                    
                    @if(!empty($data['secondary_button_text']) && !empty($data['secondary_button_url']))
                        <a href="{{$data['secondary_button_url']}}" class="btn btn-outline-primary hero-secondary-btn ms-2">
                            {{$data['secondary_button_text']}}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.hero-section {
    min-height: 500px;
    display: flex;
    align-items: center;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.hero-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #fff;
}
.hero-subtitle {
    font-size: 1.5rem;
    font-weight: 400;
    margin-bottom: 1rem;
    color: #fff;
}
.hero-description {
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    color: #fff;
}
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    .hero-subtitle {
        font-size: 1.2rem;
    }
    .hero-description {
        font-size: 1rem;
    }
}
</style>

