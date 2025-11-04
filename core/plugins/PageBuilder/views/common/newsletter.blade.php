<div class="newsletter-section common-newsletter-section newsletter-{{$data['style']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="newsletter-wrapper">
            @if(!empty($data['title']))
                <h3 class="newsletter-title">{{$data['title']}}</h3>
            @endif
            @if(!empty($data['description']))
                <p class="newsletter-description">{{$data['description']}}</p>
            @endif
            <form class="newsletter-form" 
                  id="{{$data['unique_id']}}-form"
                  onsubmit="handleNewsletterSubmit(event, '{{$data['unique_id']}}')">
                <div class="newsletter-input-group">
                    <input type="email" 
                           name="email" 
                           class="newsletter-input" 
                           placeholder="{{$data['placeholder_text']}}"
                           required>
                    <button type="submit" class="newsletter-button">
                        {{$data['button_text']}}
                    </button>
                </div>
                <div class="newsletter-message" id="{{$data['unique_id']}}-message"></div>
            </form>
        </div>
    </div>
</div>

<style>
.newsletter-section {
    padding: 60px 0;
}

.newsletter-wrapper {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.newsletter-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--heading-color, #333);
}

.newsletter-description {
    font-size: 1.1rem;
    color: var(--light-color, #666);
    margin-bottom: 30px;
    line-height: 1.6;
}

.newsletter-form {
    width: 100%;
}

.newsletter-input-group {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.newsletter-input {
    flex: 1;
    padding: 15px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 5px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.newsletter-input:focus {
    outline: none;
    border-color: var(--main-color-one, #007bff);
}

.newsletter-button {
    padding: 15px 30px;
    background: var(--main-color-one, #007bff);
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
}

.newsletter-button:hover {
    background: var(--main-color-two, #0056b3);
    transform: translateY(-2px);
}

.newsletter-message {
    min-height: 20px;
    font-size: 0.9rem;
    margin-top: 10px;
}

.newsletter-message.success {
    color: #28a745;
}

.newsletter-message.error {
    color: #dc3545;
}

/* Inline Style */
.newsletter-inline .newsletter-input-group {
    flex-direction: column;
}

.newsletter-inline .newsletter-button {
    width: 100%;
}

/* Boxed Style */
.newsletter-boxed .newsletter-wrapper {
    background: #f8f9fa;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .newsletter-title {
        font-size: 1.5rem;
    }
    
    .newsletter-input-group {
        flex-direction: column;
    }
    
    .newsletter-button {
        width: 100%;
    }
}
</style>

<script>
function handleNewsletterSubmit(event, uniqueId) {
    event.preventDefault();
    const form = document.getElementById(uniqueId + '-form');
    const messageEl = document.getElementById(uniqueId + '-message');
    const email = form.querySelector('input[name="email"]').value;
    
    messageEl.textContent = '';
    messageEl.className = 'newsletter-message';
    
    // Simple email validation
    if (!email || !email.includes('@')) {
        messageEl.textContent = '{{$data['error_message']}}';
        messageEl.classList.add('error');
        return;
    }
    
    // Here you would typically send to your backend
    // For now, just show success message
    messageEl.textContent = '{{$data['success_message']}}';
    messageEl.classList.add('success');
    form.reset();
    
    // Reset message after 5 seconds
    setTimeout(() => {
        messageEl.textContent = '';
        messageEl.className = 'newsletter-message';
    }, 5000);
}
</script>
