<div class="hotspots-section common-hotspots-section hotspots-{{$data['tooltip_style']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="hotspots-wrapper">
            <div class="hotspots-image-container">
                <img src="{{$data['background_image']}}" 
                     alt="Interactive Image" 
                     class="hotspots-background-image">
                @if(array_key_exists('repeater_tooltip_title_', $data['repeater_data'] ?? []))
                    @foreach($data['repeater_data']['repeater_tooltip_title_'] as $key => $title)
                        @php
                            $x_position = (int)($data['repeater_data']['repeater_x_position_'][$key] ?? 50);
                            $y_position = (int)($data['repeater_data']['repeater_y_position_'][$key] ?? 50);
                            $tooltip_content = $data['repeater_data']['repeater_tooltip_content_'][$key] ?? '';
                            $icon = $data['repeater_data']['repeater_icon_'][$key] ?? '';
                            $link = $data['repeater_data']['repeater_link_'][$key] ?? '';
                            $unique_id = 'hotspot-' . $data['section_id'] . '-' . $key;
                        @endphp
                        <div class="hotspot-item" 
                             style="left: {{$x_position}}%; top: {{$y_position}}%;"
                             data-tooltip-id="{{$unique_id}}">
                            @if(!empty($icon))
                                <div class="hotspot-icon">
                                    <i class="{{$icon}}"></i>
                                </div>
                            @else
                                <div class="hotspot-pulse"></div>
                            @endif
                            <div class="hotspot-tooltip" 
                                 id="{{$unique_id}}"
                                 data-style="{{$data['tooltip_style']}}">
                                <div class="tooltip-content">
                                    <h5 class="tooltip-title">{{$title}}</h5>
                                    @if(!empty($tooltip_content))
                                        <p class="tooltip-text">{{$tooltip_content}}</p>
                                    @endif
                                    @if(!empty($link))
                                        <a href="{{$link}}" class="tooltip-link">{{__('Learn More')}} →</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.hotspots-section {
    padding: 40px 0;
}

.hotspots-wrapper {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}

.hotspots-image-container {
    position: relative;
    width: 100%;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.hotspots-background-image {
    width: 100%;
    height: auto;
    display: block;
}

.hotspot-item {
    position: absolute;
    transform: translate(-50%, -50%);
    cursor: pointer;
    z-index: 10;
}

.hotspot-icon {
    width: 40px;
    height: 40px;
    background: var(--main-color-one, #007bff);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    transition: all 0.3s;
}

.hotspot-icon:hover {
    transform: scale(1.2);
    background: var(--main-color-two, #0056b3);
}

.hotspot-pulse {
    width: 20px;
    height: 20px;
    background: var(--main-color-one, #007bff);
    border-radius: 50%;
    position: relative;
    animation: pulse 2s infinite;
}

.hotspot-pulse::before {
    content: '';
    position: absolute;
    top: -10px;
    left: -10px;
    right: -10px;
    bottom: -10px;
    border: 2px solid var(--main-color-one, #007bff);
    border-radius: 50%;
    animation: pulse-ring 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

@keyframes pulse-ring {
    0% {
        transform: scale(0.8);
        opacity: 1;
    }
    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

.hotspot-tooltip {
    position: absolute;
    bottom: calc(100% + 15px);
    left: 50%;
    transform: translateX(-50%);
    background: #fff;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    min-width: 200px;
    max-width: 300px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
    z-index: 20;
}

.hotspot-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 8px solid transparent;
    border-top-color: #fff;
}

.hotspot-item:hover .hotspot-tooltip,
.hotspot-item.active .hotspot-tooltip {
    opacity: 1;
    visibility: visible;
}

.tooltip-title {
    margin: 0 0 8px 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--heading-color, #333);
}

.tooltip-text {
    margin: 0 0 10px 0;
    font-size: 0.9rem;
    color: var(--light-color, #666);
    line-height: 1.5;
}

.tooltip-link {
    color: var(--main-color-one, #007bff);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
}

.tooltip-link:hover {
    text-decoration: underline;
}

/* Popup Style */
.hotspots-popup .hotspot-tooltip {
    min-width: 250px;
    max-width: 400px;
}

/* Always Visible Style */
.hotspots-always-visible .hotspot-tooltip {
    opacity: 1;
    visibility: visible;
}

@media (max-width: 768px) {
    .hotspot-tooltip {
        min-width: 150px;
        max-width: 200px;
        font-size: 0.85rem;
    }
    
    .hotspot-icon {
        width: 35px;
        height: 35px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hotspotItems = document.querySelectorAll('.hotspot-item');
    
    hotspotItems.forEach(item => {
        item.addEventListener('click', function() {
            const tooltip = this.querySelector('.hotspot-tooltip');
            const tooltipStyle = tooltip.getAttribute('data-style');
            
            if (tooltipStyle === 'popup' || tooltipStyle === 'default') {
                // Toggle tooltip
                this.classList.toggle('active');
                
                // Close other tooltips
                hotspotItems.forEach(otherItem => {
                    if (otherItem !== this) {
                        otherItem.classList.remove('active');
                    }
                });
            }
        });
        
        // Close on outside click
        document.addEventListener('click', function(e) {
            if (!item.contains(e.target)) {
                item.classList.remove('active');
            }
        });
    });
});
</script>

