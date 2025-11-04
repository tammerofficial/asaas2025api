<div class="flipbox-section common-flipbox-section flipbox-{{$data['style']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}"
     data-trigger="{{$data['flip_trigger']}}">
    <div class="container">
        <div class="row">
            @if(array_key_exists('repeater_front_title_', $data['repeater_data'] ?? []))
                @foreach($data['repeater_data']['repeater_front_title_'] as $key => $frontTitle)
                    <div class="col-lg-{{12 / (int)$data['columns']}} col-md-6 col-sm-12 mb-4">
                        <div class="flipbox-card flip-{{$data['flip_direction']}}" 
                             data-trigger="{{$data['flip_trigger']}}">
                            <div class="flipbox-inner">
                                <div class="flipbox-front">
                                    @if(!empty($data['repeater_data']['repeater_front_image_'][$key] ?? ''))
                                        <div class="flipbox-image">
                                            <img src="{{$data['repeater_data']['repeater_front_image_'][$key]}}" alt="{{$frontTitle}}">
                                        </div>
                                    @elseif(!empty($data['repeater_data']['repeater_front_icon_'][$key] ?? ''))
                                        <div class="flipbox-icon">
                                            <i class="{{$data['repeater_data']['repeater_front_icon_'][$key]}}"></i>
                                        </div>
                                    @endif
                                    <h4 class="flipbox-title">{{$frontTitle}}</h4>
                                    @if(!empty($data['repeater_data']['repeater_front_description_'][$key] ?? ''))
                                        <p class="flipbox-description">{{$data['repeater_data']['repeater_front_description_'][$key]}}</p>
                                    @endif
                                </div>
                                <div class="flipbox-back">
                                    <h4 class="flipbox-title">{{$data['repeater_data']['repeater_back_title_'][$key] ?? ''}}</h4>
                                    @if(!empty($data['repeater_data']['repeater_back_description_'][$key] ?? ''))
                                        <p class="flipbox-description">{{$data['repeater_data']['repeater_back_description_'][$key]}}</p>
                                    @endif
                                    @if(!empty($data['repeater_data']['repeater_back_button_text_'][$key] ?? ''))
                                        <a href="{{$data['repeater_data']['repeater_back_button_url_'][$key] ?? '#'}}" 
                                           class="flipbox-button">
                                            {{$data['repeater_data']['repeater_back_button_text_'][$key]}}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<style>
.flipbox-section {
    padding: 40px 0;
}

.flipbox-card {
    perspective: 1000px;
    height: 300px;
}

.flipbox-inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    transition: transform 0.6s;
    transform-style: preserve-3d;
}

.flipbox-card[data-trigger="hover"]:hover .flipbox-inner,
.flipbox-card[data-trigger="click"].flipped .flipbox-inner {
    transform: rotateY(180deg);
}

.flipbox-card.flip-vertical[data-trigger="hover"]:hover .flipbox-inner,
.flipbox-card.flip-vertical[data-trigger="click"].flipped .flipbox-inner {
    transform: rotateX(180deg);
}

.flipbox-front,
.flipbox-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: 10px;
    padding: 30px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.flipbox-front {
    background: linear-gradient(135deg, var(--main-color-one, #007bff), var(--main-color-two, #0056b3));
    color: #fff;
}

.flipbox-back {
    background: #fff;
    color: #333;
    transform: rotateY(180deg);
}

.flipbox-card.flip-vertical .flipbox-back {
    transform: rotateX(180deg);
}

.flipbox-image img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 15px;
}

.flipbox-icon {
    font-size: 4rem;
    margin-bottom: 15px;
    color: #fff;
}

.flipbox-back .flipbox-icon {
    color: var(--main-color-one, #007bff);
}

.flipbox-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 15px 0;
}

.flipbox-description {
    margin: 10px 0;
    line-height: 1.6;
}

.flipbox-button {
    margin-top: 20px;
    padding: 10px 25px;
    background: var(--main-color-one, #007bff);
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.3s;
}

.flipbox-button:hover {
    background: var(--main-color-two, #0056b3);
    transform: translateY(-2px);
}

/* 3D Style */
.flipbox-3d .flipbox-card {
    perspective: 1500px;
}

.flipbox-3d .flipbox-inner {
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

/* Minimal Style */
.flipbox-minimal .flipbox-front,
.flipbox-minimal .flipbox-back {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e0e0e0;
}

@media (max-width: 768px) {
    .flipbox-card {
        height: 250px;
    }
    
    .flipbox-front,
    .flipbox-back {
        padding: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const flipCards = document.querySelectorAll('.flipbox-card[data-trigger="click"]');
    
    flipCards.forEach(card => {
        card.addEventListener('click', function() {
            this.classList.toggle('flipped');
        });
    });
});
</script>

