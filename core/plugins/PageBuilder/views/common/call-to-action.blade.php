<section class="cta-section common-cta-section" 
         data-padding-top="{{$data['padding_top']}}" 
         data-padding-bottom="{{$data['padding_bottom']}}" 
         id="{{$data['section_id']}}"
         @if(!empty($data['background_image']))
            {!! render_background_image_markup_by_attachment_id($data['background_image'], 'full') !!}
         @endif
         style="position: relative; background-color: var(--main-color-one, #007bff);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-{{$data['style'] === 'split' ? '6' : '8'}} text-center">
                @if(!empty($data['title']))
                    <h2 class="cta-title text-white mb-3">{{$data['title']}}</h2>
                @endif
                
                @if(!empty($data['subtitle']))
                    <p class="cta-subtitle text-white mb-4">{{$data['subtitle']}}</p>
                @endif
                
                <div class="cta-buttons">
                    @if(!empty($data['primary_button_text']) && !empty($data['primary_button_url']))
                        <a href="{{$data['primary_button_url']}}" class="btn btn-light btn-lg me-2">
                            {{$data['primary_button_text']}}
                        </a>
                    @endif
                    
                    @if(!empty($data['secondary_button_text']) && !empty($data['secondary_button_url']))
                        <a href="{{$data['secondary_button_url']}}" class="btn btn-outline-light btn-lg">
                            {{$data['secondary_button_text']}}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.cta-section {
    min-height: 300px;
    display: flex;
    align-items: center;
    background-size: cover;
    background-position: center;
}
.cta-title {
    font-size: 2.5rem;
    font-weight: 700;
}
.cta-subtitle {
    font-size: 1.2rem;
}
@media (max-width: 768px) {
    .cta-title {
        font-size: 2rem;
    }
    .cta-buttons .btn {
        display: block;
        width: 100%;
        margin: 10px 0 !important;
    }
}
</style>

