<div class="reviews-section common-reviews-section" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="row">
            @if(array_key_exists('repeater_name_', $data['repeater_data'] ?? []))
                @foreach($data['repeater_data']['repeater_name_'] as $key => $name)
                    <div class="col-lg-{{12 / (int)$data['columns']}} col-md-6 mt-4">
                        <div class="review-card">
                            <div class="review-header">
                                @if(!empty($data['repeater_data']['repeater_image_'][$key] ?? ''))
                                    <div class="review-image">
                                        {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'][$key], 'thumbnail') !!}
                                    </div>
                                @endif
                                
                                <div class="review-info">
                                    @if(!empty($name))
                                        <h5 class="review-name">{{$name}}</h5>
                                    @endif
                                    
                                    @if(!empty($data['repeater_data']['repeater_date_'][$key] ?? ''))
                                        <p class="review-date">{{$data['repeater_data']['repeater_date_'][$key]}}</p>
                                    @endif
                                </div>
                            </div>
                            
                            @if(!empty($data['repeater_data']['repeater_rating_'][$key] ?? ''))
                                <div class="review-rating mt-2">
                                    {!! render_star_rating_markup($data['repeater_data']['repeater_rating_'][$key]) !!}
                                </div>
                            @endif
                            
                            @if(!empty($data['repeater_data']['repeater_review_text_'][$key] ?? ''))
                                <p class="review-text mt-3">
                                    {{$data['repeater_data']['repeater_review_text_'][$key]}}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<style>
.review-card {
    padding: 25px;
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: 100%;
}
.review-header {
    display: flex;
    align-items: center;
    gap: 15px;
}
.review-image img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}
.review-name {
    margin: 0;
    font-weight: 600;
}
.review-date {
    margin: 0;
    color: #999;
    font-size: 0.85rem;
}
.review-text {
    color: #666;
    line-height: 1.6;
}
</style>

