<div class="background-overlay-section common-background-overlay-section" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}"
     style="position: relative;">
    @php
        $opacity = $data['opacity'] / 100;
        $bgStyle = '';
        
        if ($data['gradient_type'] === 'linear') {
            $bgStyle = "background: linear-gradient(135deg, {$data['overlay_color']} 0%, {$data['gradient_color']} 100%); opacity: {$opacity};";
        } elseif ($data['gradient_type'] === 'radial') {
            $bgStyle = "background: radial-gradient(circle, {$data['overlay_color']} 0%, {$data['gradient_color']} 100%); opacity: {$opacity};";
        } else {
            $bgStyle = "background-color: {$data['overlay_color']}; opacity: {$opacity};";
        }
    @endphp
    
    <div class="overlay-layer" style="{{$bgStyle}} position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></div>
    <div class="overlay-content" style="position: relative; z-index: 2;">
        <!-- Content can be added here or used with other sections -->
    </div>
</div>

