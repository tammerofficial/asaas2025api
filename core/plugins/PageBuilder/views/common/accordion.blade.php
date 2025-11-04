<div class="accordion-section common-accordion-section accordion-{{$data['style']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}"
     data-allow-multiple="{{$data['allow_multiple']}}">
    <div class="container">
        @if(array_key_exists('repeater_title_', $data['repeater_data'] ?? []))
            <div class="accordion-wrapper">
                @foreach($data['repeater_data']['repeater_title_'] as $key => $title)
                    @php
                        $is_open = ($data['repeater_data']['repeater_default_open_'][$key] ?? 'no') === 'yes';
                        $unique_id = 'accordion-' . $data['section_id'] . '-' . $key;
                    @endphp
                    <div class="accordion-item">
                        <div class="accordion-header" 
                             onclick="toggleAccordion('{{$unique_id}}', {{$data['allow_multiple'] === 'yes' ? 'true' : 'false'}})">
                            <div class="accordion-title-wrapper">
                                @if(!empty($data['repeater_data']['repeater_icon_'][$key] ?? ''))
                                    <i class="accordion-icon {{$data['repeater_data']['repeater_icon_'][$key]}}"></i>
                                @endif
                                <h4 class="accordion-title">{{$title}}</h4>
                            </div>
                            <i class="accordion-toggle-icon las la-angle-{{$is_open ? 'up' : 'down'}}"></i>
                        </div>
                        <div class="accordion-content {{$is_open ? 'active' : ''}}" id="{{$unique_id}}">
                            <div class="accordion-content-body">
                                {!! $data['repeater_data']['repeater_content_'][$key] ?? '' !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
.accordion-section {
    padding: 20px 0;
}

.accordion-wrapper {
    width: 100%;
}

.accordion-item {
    margin-bottom: 15px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.accordion-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.accordion-header {
    padding: 20px;
    background: #f8f9fa;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.accordion-header:hover {
    background: #e9ecef;
}

.accordion-title-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 1;
}

.accordion-icon {
    font-size: 1.5rem;
    color: var(--main-color-one, #007bff);
}

.accordion-title {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
}

.accordion-toggle-icon {
    font-size: 1.5rem;
    color: #666;
    transition: transform 0.3s ease;
}

.accordion-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.accordion-content.active {
    max-height: 2000px;
}

.accordion-content-body {
    padding: 20px;
    background: #fff;
}

/* Boxed Style */
.accordion-boxed .accordion-item {
    border: 2px solid #e0e0e0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.accordion-boxed .accordion-header {
    background: #fff;
}

/* Minimal Style */
.accordion-minimal .accordion-item {
    border: none;
    border-bottom: 1px solid #e0e0e0;
    border-radius: 0;
    box-shadow: none;
}

.accordion-minimal .accordion-header {
    background: transparent;
    padding: 15px 0;
}

.accordion-minimal .accordion-content-body {
    padding: 10px 0 20px;
}

@media (max-width: 768px) {
    .accordion-header {
        padding: 15px;
    }
    
    .accordion-title {
        font-size: 1rem;
    }
}
</style>

<script>
function toggleAccordion(id, allowMultiple) {
    const content = document.getElementById(id);
    const item = content.closest('.accordion-item');
    const toggleIcon = item.querySelector('.accordion-toggle-icon');
    const isActive = content.classList.contains('active');
    
    if (!allowMultiple) {
        // Close all other accordions
        const allItems = content.closest('.accordion-wrapper').querySelectorAll('.accordion-item');
        allItems.forEach(otherItem => {
            if (otherItem !== item) {
                const otherContent = otherItem.querySelector('.accordion-content');
                const otherIcon = otherItem.querySelector('.accordion-toggle-icon');
                otherContent.classList.remove('active');
                otherIcon.classList.remove('la-angle-up');
                otherIcon.classList.add('la-angle-down');
            }
        });
    }
    
    // Toggle current accordion
    if (isActive) {
        content.classList.remove('active');
        toggleIcon.classList.remove('la-angle-up');
        toggleIcon.classList.add('la-angle-down');
    } else {
        content.classList.add('active');
        toggleIcon.classList.remove('la-angle-down');
        toggleIcon.classList.add('la-angle-up');
    }
}
</script>

