<div class="divider-section common-divider-section" 
     id="{{$data['section_id']}}"
     style="margin-top: {{$data['margin_top']}}px; margin-bottom: {{$data['margin_bottom']}}px;">
    <div class="divider-container" style="text-align: {{$data['alignment']}};">
        <div class="divider-line divider-{{$data['style']}}" 
             style="width: {{$data['width']}}; 
                    height: {{$data['height']}}px; 
                    background-color: {{$data['color']}}; 
                    border-color: {{$data['color']}};">
        </div>
    </div>
</div>

<style>
.divider-section {
    display: block;
    width: 100%;
}

.divider-container {
    width: 100%;
}

.divider-line {
    display: inline-block;
    margin: 0 auto;
}

.divider-solid {
    border: none;
}

.divider-dashed {
    border: none;
    border-top: {{$data['height']}}px dashed {{$data['color']}};
    background: transparent;
}

.divider-dotted {
    border: none;
    border-top: {{$data['height']}}px dotted {{$data['color']}};
    background: transparent;
}

.divider-double {
    border: none;
    border-top: {{$data['height'] * 2}}px double {{$data['color']}};
    background: transparent;
    height: {{$data['height'] * 2}}px !important;
}

.divider-wavy {
    border: none;
    background: transparent;
    height: {{$data['height'] * 2}}px;
    position: relative;
    overflow: hidden;
}

.divider-wavy::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: repeating-linear-gradient(
        0deg,
        transparent,
        transparent {{$data['height']}}px,
        {{$data['color']}} {{$data['height']}}px,
        {{$data['color']}} {{$data['height'] * 2}}px
    );
}

@media (max-width: 768px) {
    .divider-line {
        width: 100% !important;
    }
}
</style>

