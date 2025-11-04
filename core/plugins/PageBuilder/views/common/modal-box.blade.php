<div class="modal-box-section common-modal-box-section" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="modal-trigger-wrapper">
            @if($data['trigger_type'] === 'button')
                <button class="modal-trigger-btn" onclick="openModal('{{$data['unique_id']}}')">
                    {{$data['trigger_text']}}
                </button>
            @elseif($data['trigger_type'] === 'image' && !empty($data['trigger_image']))
                <img src="{{$data['trigger_image']}}" 
                     alt="Modal Trigger" 
                     class="modal-trigger-image"
                     onclick="openModal('{{$data['unique_id']}}')"
                     style="cursor: pointer;">
            @elseif($data['trigger_type'] === 'custom' && !empty($data['trigger_custom_html']))
                <div class="modal-trigger-custom" onclick="openModal('{{$data['unique_id']}}')">
                    {!! $data['trigger_custom_html'] !!}
                </div>
            @endif
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal-overlay" 
         id="{{$data['unique_id']}}-overlay"
         onclick="{{$data['close_on_outside_click'] === 'yes' ? "closeModal('{$data['unique_id']}')" : ''}}">
        <div class="modal-container modal-{{$data['modal_size']}}" 
             id="{{$data['unique_id']}}"
             onclick="event.stopPropagation()">
            <div class="modal-header">
                @if(!empty($data['modal_title']))
                    <h3 class="modal-title">{{$data['modal_title']}}</h3>
                @endif
                @if($data['show_close_button'] === 'yes')
                    <button class="modal-close" onclick="closeModal('{{$data['unique_id']}}')">
                        <span>&times;</span>
                    </button>
                @endif
            </div>
            <div class="modal-body">
                {!! $data['modal_content'] !!}
            </div>
        </div>
    </div>
</div>

<style>
.modal-box-section {
    padding: 20px 0;
}

.modal-trigger-wrapper {
    text-align: center;
}

.modal-trigger-btn {
    padding: 12px 30px;
    background: var(--main-color-one, #007bff);
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.modal-trigger-btn:hover {
    background: var(--main-color-two, #0056b3);
    transform: translateY(-2px);
}

.modal-trigger-image {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    transition: transform 0.3s;
}

.modal-trigger-image:hover {
    transform: scale(1.05);
}

.modal-trigger-custom {
    cursor: pointer;
}

/* Modal Overlay */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9998;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s;
}

.modal-overlay.active {
    display: flex;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-container {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s;
    position: relative;
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-small {
    width: 90%;
    max-width: 400px;
}

.modal-medium {
    width: 90%;
    max-width: 600px;
}

.modal-large {
    width: 90%;
    max-width: 900px;
}

.modal-fullscreen {
    width: 95%;
    height: 95vh;
    max-width: none;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #e0e0e0;
}

.modal-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--heading-color, #333);
}

.modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    color: #666;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.3s;
}

.modal-close:hover {
    color: #333;
}

.modal-body {
    padding: 25px;
    color: var(--light-color, #666);
    line-height: 1.6;
}

@media (max-width: 768px) {
    .modal-small,
    .modal-medium,
    .modal-large {
        width: 95%;
        max-width: none;
    }
    
    .modal-header {
        padding: 15px 20px;
    }
    
    .modal-body {
        padding: 20px;
    }
}
</style>

<script>
function openModal(modalId) {
    const overlay = document.getElementById(modalId + '-overlay');
    if (overlay) {
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const overlay = document.getElementById(modalId + '-overlay');
    if (overlay) {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const activeModals = document.querySelectorAll('.modal-overlay.active');
        activeModals.forEach(overlay => {
            const modalId = overlay.id.replace('-overlay', '');
            closeModal(modalId);
        });
    }
});
</script>

