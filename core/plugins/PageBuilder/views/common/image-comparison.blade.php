<div class="image-comparison-section common-image-comparison-section comparison-{{$data['orientation']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="image-comparison-wrapper">
            <div class="comparison-container" 
                 data-position="{{$data['slider_position']}}"
                 data-orientation="{{$data['orientation']}}">
                <div class="comparison-image-wrapper">
                    <img src="{{$data['before_image']}}" 
                         alt="{{$data['label_before']}}" 
                         class="comparison-image comparison-before">
                    <img src="{{$data['after_image']}}" 
                         alt="{{$data['label_after']}}" 
                         class="comparison-image comparison-after">
                    <div class="comparison-slider" 
                         style="{{$data['orientation'] === 'horizontal' ? 'left' : 'top'}}: {{$data['slider_position']}}%">
                        <div class="slider-handle"></div>
                        <div class="slider-line"></div>
                    </div>
                    @if($data['show_labels'] === 'yes')
                        <div class="comparison-label label-before">
                            {{$data['label_before']}}
                        </div>
                        <div class="comparison-label label-after">
                            {{$data['label_after']}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.image-comparison-section {
    padding: 40px 0;
}

.image-comparison-wrapper {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}

.comparison-container {
    position: relative;
    width: 100%;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.comparison-image-wrapper {
    position: relative;
    width: 100%;
    padding-bottom: 60%; /* 16:9 aspect ratio */
    overflow: hidden;
}

.comparison-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.comparison-before {
    z-index: 1;
}

.comparison-after {
    z-index: 2;
    clip-path: inset(0 0 0 {{100 - $data['slider_position']}}%);
}

.comparison-vertical .comparison-after {
    clip-path: inset({{100 - $data['slider_position']}}% 0 0 0);
}

.comparison-slider {
    position: absolute;
    z-index: 10;
    cursor: grab;
    user-select: none;
}

.comparison-horizontal .comparison-slider {
    top: 0;
    bottom: 0;
    width: 4px;
    transform: translateX(-50%);
}

.comparison-vertical .comparison-slider {
    left: 0;
    right: 0;
    height: 4px;
    transform: translateY(-50%);
}

.slider-handle {
    position: absolute;
    width: 50px;
    height: 50px;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.slider-handle::before,
.slider-handle::after {
    content: '';
    position: absolute;
    width: 8px;
    height: 2px;
    background: #333;
}

.slider-handle::before {
    transform: rotate(45deg);
    left: 12px;
}

.slider-handle::after {
    transform: rotate(-45deg);
    right: 12px;
}

.slider-line {
    position: absolute;
    background: rgba(255,255,255,0.8);
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
}

.comparison-horizontal .slider-line {
    top: 0;
    bottom: 0;
    width: 2px;
    left: 50%;
    transform: translateX(-50%);
}

.comparison-vertical .slider-line {
    left: 0;
    right: 0;
    height: 2px;
    top: 50%;
    transform: translateY(-50%);
}

.comparison-label {
    position: absolute;
    background: rgba(0,0,0,0.7);
    color: #fff;
    padding: 8px 15px;
    border-radius: 5px;
    font-weight: 600;
    z-index: 5;
}

.comparison-horizontal .label-before {
    top: 20px;
    left: 20px;
}

.comparison-horizontal .label-after {
    top: 20px;
    right: 20px;
}

.comparison-vertical .label-before {
    top: 20px;
    left: 20px;
}

.comparison-vertical .label-after {
    bottom: 20px;
    left: 20px;
}

.comparison-slider:active {
    cursor: grabbing;
}

@media (max-width: 768px) {
    .comparison-image-wrapper {
        padding-bottom: 75%;
    }
    
    .slider-handle {
        width: 40px;
        height: 40px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const comparisonContainers = document.querySelectorAll('.comparison-container');
    
    comparisonContainers.forEach(container => {
        const slider = container.querySelector('.comparison-slider');
        const afterImage = container.querySelector('.comparison-after');
        const orientation = container.getAttribute('data-orientation');
        let isDragging = false;
        
        function updatePosition(clientX, clientY) {
            const rect = container.getBoundingClientRect();
            let position;
            
            if (orientation === 'horizontal') {
                position = ((clientX - rect.left) / rect.width) * 100;
            } else {
                position = ((clientY - rect.top) / rect.height) * 100;
            }
            
            position = Math.max(0, Math.min(100, position));
            
            if (orientation === 'horizontal') {
                slider.style.left = position + '%';
                afterImage.style.clipPath = `inset(0 0 0 ${100 - position}%)`;
            } else {
                slider.style.top = position + '%';
                afterImage.style.clipPath = `inset(${100 - position}% 0 0 0)`;
            }
        }
        
        slider.addEventListener('mousedown', (e) => {
            isDragging = true;
            e.preventDefault();
        });
        
        document.addEventListener('mousemove', (e) => {
            if (isDragging) {
                updatePosition(e.clientX, e.clientY);
            }
        });
        
        document.addEventListener('mouseup', () => {
            isDragging = false;
        });
        
        // Touch support
        slider.addEventListener('touchstart', (e) => {
            isDragging = true;
            e.preventDefault();
        });
        
        document.addEventListener('touchmove', (e) => {
            if (isDragging && e.touches.length > 0) {
                updatePosition(e.touches[0].clientX, e.touches[0].clientY);
            }
        });
        
        document.addEventListener('touchend', () => {
            isDragging = false;
        });
    });
});
</script>

