<div class="counter-section common-counter-section counter-{{$data['style']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="counter-wrapper">
            @if(!empty($data['icon']))
                <div class="counter-icon">
                    <i class="{{$data['icon']}}"></i>
                </div>
            @endif
            
            <div class="counter-number-wrapper">
                <span class="counter-prefix">{{$data['prefix']}}</span>
                <span class="counter-number" 
                      data-target="{{$data['number']}}"
                      data-start="{{$data['starting_number']}}"
                      data-speed="{{$data['animation_speed']}}">
                    {{$data['starting_number']}}
                </span>
                <span class="counter-suffix">{{$data['suffix']}}</span>
            </div>
            
            @if(!empty($data['title']))
                <h4 class="counter-title">{{$data['title']}}</h4>
            @endif
            
            @if(!empty($data['description']))
                <p class="counter-description">{{$data['description']}}</p>
            @endif
        </div>
    </div>
</div>

<style>
.counter-section {
    padding: 40px 0;
    text-align: center;
}

.counter-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.counter-icon {
    font-size: 3rem;
    color: var(--main-color-one, #007bff);
    margin-bottom: 10px;
}

.counter-number-wrapper {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 5px;
}

.counter-prefix,
.counter-suffix {
    font-size: 2rem;
    font-weight: 600;
    color: var(--main-color-one, #007bff);
}

.counter-number {
    font-size: 3.5rem;
    font-weight: 700;
    color: var(--heading-color, #333);
    line-height: 1;
}

.counter-title {
    margin: 10px 0 0 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--heading-color, #333);
}

.counter-description {
    margin: 5px 0 0 0;
    color: var(--light-color, #666);
    max-width: 600px;
}

/* Boxed Style */
.counter-boxed .counter-wrapper {
    background: #f8f9fa;
    padding: 40px 30px;
    border-radius: 10px;
    border: 2px solid var(--main-color-one, #007bff);
}

/* Minimal Style */
.counter-minimal .counter-number {
    font-size: 2.5rem;
}

.counter-minimal .counter-icon {
    font-size: 2rem;
}

@media (max-width: 768px) {
    .counter-number {
        font-size: 2.5rem;
    }
    
    .counter-prefix,
    .counter-suffix {
        font-size: 1.5rem;
    }
    
    .counter-title {
        font-size: 1.2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const start = parseInt(counter.getAttribute('data-start'));
        const speed = parseInt(counter.getAttribute('data-speed'));
        const duration = speed;
        const increment = (target - start) / (duration / 16);
        let current = start;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                if (current > target) current = target;
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };
        
        // Start animation when element is visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCounter();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(counter);
    });
});
</script>

