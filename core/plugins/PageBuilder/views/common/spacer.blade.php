<div class="spacer-section common-spacer-section spacer-{{$data['visibility']}}" 
     id="{{$data['section_id']}}"
     style="height: {{$data['height']}}px; 
            @if(!empty($data['background_color'])) background-color: {{$data['background_color']}}; @endif">
</div>

<style>
.spacer-section {
    display: block;
    width: 100%;
    min-height: 10px;
}

.spacer-desktop {
    display: block;
}

.spacer-mobile {
    display: none;
}

@media (max-width: 991px) {
    .spacer-section {
        @if(!empty($data['height_tablet'])) 
            height: {{$data['height_tablet']}}px !important;
        @endif
    }
    
    .spacer-desktop {
        display: none;
    }
    
    .spacer-mobile {
        display: block;
    }
}

@media (max-width: 768px) {
    .spacer-section {
        @if(!empty($data['height_mobile'])) 
            height: {{$data['height_mobile']}}px !important;
        @endif
    }
}
</style>

