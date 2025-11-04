<div class="progress-bar-section common-progress-bar-section progress-{{$data['style']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        @if(array_key_exists('repeater_label_', $data['repeater_data'] ?? []))
            <div class="progress-items-wrapper">
                @foreach($data['repeater_data']['repeater_label_'] as $key => $label)
                    @php
                        $percentage = (int)($data['repeater_data']['repeater_percentage_'][$key] ?? 0);
                        $color = $data['repeater_data']['repeater_color_'][$key] ?? '#007bff';
                        $animated = ($data['repeater_data']['repeater_animated_'][$key] ?? 'yes') === 'yes';
                        $unique_id = 'progress-' . $data['section_id'] . '-' . $key;
                    @endphp
                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-label">{{$label}}</span>
                            @if($data['show_percentage'] === 'yes')
                                <span class="progress-percentage" id="{{$unique_id}}-text">0%</span>
                            @endif
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" 
                                 id="{{$unique_id}}"
                                 data-percentage="{{$percentage}}"
                                 data-color="{{$color}}"
                                 data-animated="{{$animated ? 'true' : 'false'}}"
                                 style="width: 0%; background-color: {{$color}};">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
.progress-bar-section {
    padding: 40px 0;
}

.progress-items-wrapper {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.progress-item {
    width: 100%;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.progress-label {
    font-weight: 600;
    color: var(--heading-color, #333);
    font-size: 1rem;
}

.progress-percentage {
    font-weight: 600;
    color: var(--main-color-one, #007bff);
    font-size: 0.9rem;
}

.progress-bar-container {
    width: 100%;
    height: 30px;
    background-color: #e9ecef;
    border-radius: 15px;
    overflow: hidden;
    position: relative;
}

.progress-bar-fill {
    height: 100%;
    border-radius: 15px;
    transition: width 1.5s ease-in-out;
    position: relative;
}

.progress-bar-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Vertical Style */
.progress-vertical .progress-items-wrapper {
    flex-direction: row;
    gap: 20px;
    align-items: flex-end;
}

.progress-vertical .progress-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.progress-vertical .progress-bar-container {
    width: 30px;
    height: 200px;
    transform: rotate(180deg);
}

.progress-vertical .progress-bar-fill {
    width: 100%;
    height: 0%;
    transition: height 1.5s ease-in-out;
}

/* Circular Style */
.progress-circular .progress-items-wrapper {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 30px;
}

.progress-circular .progress-item {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.progress-circular .progress-bar-container {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: conic-gradient(#e9ecef 0deg, #e9ecef 360deg);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.progress-circular .progress-bar-fill {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: conic-gradient(var(--progress-color, #007bff) 0deg, var(--progress-color, #007bff) 0deg, #e9ecef 0deg);
    transition: background 1.5s ease-in-out;
}

@media (max-width: 768px) {
    .progress-vertical .progress-items-wrapper {
        flex-direction: column;
    }
    
    .progress-circular .progress-items-wrapper {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-bar-fill');
    
    progressBars.forEach(bar => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const percentage = parseInt(entry.target.getAttribute('data-percentage'));
                    const color = entry.target.getAttribute('data-color');
                    const animated = entry.target.getAttribute('data-animated') === 'true';
                    const isCircular = entry.target.closest('.progress-circular');
                    const isVertical = entry.target.closest('.progress-vertical');
                    const textElement = document.getElementById(entry.target.id + '-text');
                    
                    if (isCircular) {
                        const degrees = (percentage / 100) * 360;
                        entry.target.style.background = `conic-gradient(${color} 0deg, ${color} ${degrees}deg, #e9ecef ${degrees}deg)`;
                        
                        if (textElement) {
                            let current = 0;
                            const increment = percentage / 50;
                            const timer = setInterval(() => {
                                current += increment;
                                if (current >= percentage) {
                                    current = percentage;
                                    clearInterval(timer);
                                }
                                textElement.textContent = Math.floor(current) + '%';
                            }, 30);
                        }
                    } else if (isVertical) {
                        entry.target.style.height = percentage + '%';
                        
                        if (textElement) {
                            let current = 0;
                            const increment = percentage / 50;
                            const timer = setInterval(() => {
                                current += increment;
                                if (current >= percentage) {
                                    current = percentage;
                                    clearInterval(timer);
                                }
                                textElement.textContent = Math.floor(current) + '%';
                            }, 30);
                        }
                    } else {
                        entry.target.style.width = percentage + '%';
                        
                        if (textElement) {
                            let current = 0;
                            const increment = percentage / 50;
                            const timer = setInterval(() => {
                                current += increment;
                                if (current >= percentage) {
                                    current = percentage;
                                    clearInterval(timer);
                                }
                                textElement.textContent = Math.floor(current) + '%';
                            }, 30);
                        }
                    }
                    
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });
        
        observer.observe(bar);
    });
});
</script>

