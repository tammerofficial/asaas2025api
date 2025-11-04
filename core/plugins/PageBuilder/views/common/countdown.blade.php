<div class="countdown-section common-countdown-section countdown-{{$data['style']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="countdown-wrapper">
            @if(!empty($data['label']))
                <h3 class="countdown-label">{{$data['label']}}</h3>
            @endif
            <div class="countdown-timer" 
                 data-target="{{$data['target_timestamp']}}"
                 data-format="{{$data['format']}}">
                <div class="countdown-item">
                    <span class="countdown-value" data-type="days">00</span>
                    <span class="countdown-label-unit">{{__('Days')}}</span>
                </div>
                <div class="countdown-separator">:</div>
                <div class="countdown-item">
                    <span class="countdown-value" data-type="hours">00</span>
                    <span class="countdown-label-unit">{{__('Hours')}}</span>
                </div>
                <div class="countdown-separator">:</div>
                <div class="countdown-item">
                    <span class="countdown-value" data-type="minutes">00</span>
                    <span class="countdown-label-unit">{{__('Minutes')}}</span>
                </div>
                <div class="countdown-separator">:</div>
                <div class="countdown-item">
                    <span class="countdown-value" data-type="seconds">00</span>
                    <span class="countdown-label-unit">{{__('Seconds')}}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.countdown-section {
    padding: 60px 0;
    text-align: center;
}

.countdown-label {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 30px;
    color: var(--heading-color, #333);
}

.countdown-timer {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.countdown-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 80px;
}

.countdown-value {
    font-size: 3rem;
    font-weight: 700;
    color: var(--main-color-one, #007bff);
    line-height: 1;
    margin-bottom: 5px;
}

.countdown-label-unit {
    font-size: 0.9rem;
    color: var(--light-color, #666);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.countdown-separator {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--main-color-one, #007bff);
    margin: 0 5px;
}

/* Compact Style */
.countdown-compact .countdown-value {
    font-size: 2.5rem;
}

.countdown-compact .countdown-label-unit {
    font-size: 0.8rem;
}

/* Minimal Style */
.countdown-minimal .countdown-value {
    font-size: 2rem;
    color: var(--heading-color, #333);
}

.countdown-minimal .countdown-separator {
    font-size: 1.5rem;
    color: var(--light-color, #666);
}

.countdown-minimal .countdown-label-unit {
    font-size: 0.75rem;
}

/* Format: no-days */
.countdown-timer[data-format="no-days"] .countdown-item[data-type="days"],
.countdown-timer[data-format="no-days"] .countdown-separator:first-of-type {
    display: none;
}

/* Format: no-seconds */
.countdown-timer[data-format="no-seconds"] .countdown-item[data-type="seconds"],
.countdown-timer[data-format="no-seconds"] .countdown-separator:last-of-type {
    display: none;
}

/* Format: compact */
.countdown-timer[data-format="compact"] .countdown-label-unit {
    display: none;
}

.countdown-timer[data-format="compact"] .countdown-item {
    min-width: 60px;
}

@media (max-width: 768px) {
    .countdown-value {
        font-size: 2rem;
    }
    
    .countdown-separator {
        font-size: 1.5rem;
    }
    
    .countdown-label {
        font-size: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownTimers = document.querySelectorAll('.countdown-timer');
    
    countdownTimers.forEach(timer => {
        const targetTimestamp = parseInt(timer.getAttribute('data-target'));
        const format = timer.getAttribute('data-format');
        
        function updateCountdown() {
            const now = Math.floor(Date.now() / 1000);
            const distance = targetTimestamp - now;
            
            if (distance < 0) {
                timer.innerHTML = '<div class="countdown-expired">' + '{{__('Expired')}}' + '</div>';
                return;
            }
            
            const days = Math.floor(distance / (60 * 60 * 24));
            const hours = Math.floor((distance % (60 * 60 * 24)) / (60 * 60));
            const minutes = Math.floor((distance % (60 * 60)) / 60);
            const seconds = Math.floor(distance % 60);
            
            const daysEl = timer.querySelector('[data-type="days"]');
            const hoursEl = timer.querySelector('[data-type="hours"]');
            const minutesEl = timer.querySelector('[data-type="minutes"]');
            const secondsEl = timer.querySelector('[data-type="seconds"]');
            
            if (daysEl) daysEl.textContent = String(days).padStart(2, '0');
            if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
            if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
            if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
});
</script>

