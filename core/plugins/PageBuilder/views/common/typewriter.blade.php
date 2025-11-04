<div class="typewriter-section common-typewriter-section" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}"
     style="text-align: {{$data['text_align']}};">
    <div class="container">
        <div class="typewriter-wrapper">
            <h2 class="typewriter-text">
                <span class="typewriter-static-before">{{$data['static_text_before']}}</span>
                <span class="typewriter-animated" 
                      data-texts='@json($data['animated_texts'])'
                      data-typing-speed="{{$data['typing_speed']}}"
                      data-deleting-speed="{{$data['deleting_speed']}}"
                      data-cursor="{{$data['cursor_char']}}"
                      data-show-cursor="{{$data['show_cursor'] ? 'true' : 'false'}}"
                      data-loop="{{$data['loop'] ? 'true' : 'false'}}">
                </span>
                <span class="typewriter-static-after">{{$data['static_text_after']}}</span>
                @if($data['show_cursor'])
                    <span class="typewriter-cursor">{{$data['cursor_char']}}</span>
                @endif
            </h2>
        </div>
    </div>
</div>

<style>
.typewriter-section {
    padding: 60px 0;
}

.typewriter-wrapper {
    width: 100%;
}

.typewriter-text {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1.4;
    color: var(--heading-color, #333);
    margin: 0;
}

.typewriter-static-before,
.typewriter-static-after {
    display: inline;
}

.typewriter-animated {
    display: inline;
    color: var(--main-color-one, #007bff);
    min-width: 2ch;
}

.typewriter-cursor {
    display: inline-block;
    animation: blink 1s infinite;
    color: var(--main-color-one, #007bff);
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0; }
}

@media (max-width: 768px) {
    .typewriter-text {
        font-size: 1.8rem;
    }
}

@media (max-width: 480px) {
    .typewriter-text {
        font-size: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typewriterElements = document.querySelectorAll('.typewriter-animated');
    
    typewriterElements.forEach(element => {
        const texts = JSON.parse(element.getAttribute('data-texts'));
        const typingSpeed = parseInt(element.getAttribute('data-typing-speed'));
        const deletingSpeed = parseInt(element.getAttribute('data-deleting-speed'));
        const cursor = element.getAttribute('data-cursor');
        const showCursor = element.getAttribute('data-show-cursor') === 'true';
        const loop = element.getAttribute('data-loop') === 'true';
        
        if (texts.length === 0) return;
        
        let textIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let currentText = '';
        
        function type() {
            const currentFullText = texts[textIndex];
            
            if (isDeleting) {
                currentText = currentFullText.substring(0, currentText.length - 1);
                element.textContent = currentText;
                
                setTimeout(type, deletingSpeed);
                
                if (currentText === '') {
                    isDeleting = false;
                    textIndex = (textIndex + 1) % texts.length;
                }
            } else {
                currentText = currentFullText.substring(0, charIndex + 1);
                element.textContent = currentText;
                charIndex++;
                
                if (charIndex === currentFullText.length) {
                    isDeleting = true;
                    setTimeout(type, 2000); // Wait before deleting
                    return;
                }
                
                setTimeout(type, typingSpeed);
            }
            
            // Stop if not looping and finished all texts
            if (!loop && textIndex === texts.length - 1 && isDeleting && currentText === '') {
                element.textContent = texts[texts.length - 1];
                return;
            }
        }
        
        // Start typing when element is visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    type();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(element);
    });
});
</script>

