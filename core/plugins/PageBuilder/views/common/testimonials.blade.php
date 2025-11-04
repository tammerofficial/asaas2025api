<div class="testimonials-section common-testimonials-section" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="row">
            @if(array_key_exists('repeater_name_', $data['repeater_data'] ?? []))
                @foreach($data['repeater_data']['repeater_name_'] as $key => $name)
                    <div class="col-lg-{{12 / (int)$data['columns']}} col-md-6 mt-4">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                @if(!empty($data['repeater_data']['repeater_content_'][$key] ?? ''))
                                    <p class="testimonial-text">
                                        "{{$data['repeater_data']['repeater_content_'][$key]}}"
                                    </p>
                                @endif
                                
                                @if(!empty($data['repeater_data']['repeater_rating_'][$key] ?? ''))
                                    {!! render_star_rating_markup($data['repeater_data']['repeater_rating_'][$key]) !!}
                                @endif
                            </div>
                            
                            <div class="testimonial-author mt-3">
                                @if(!empty($data['repeater_data']['repeater_image_'][$key] ?? ''))
                                    <div class="testimonial-image">
                                        {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'][$key], 'thumbnail') !!}
                                    </div>
                                @endif
                                
                                <div class="testimonial-info">
                                    @if(!empty($name))
                                        <h5 class="testimonial-name">{{$name}}</h5>
                                    @endif
                                    
                                    @if(!empty($data['repeater_data']['repeater_role_'][$key] ?? ''))
                                        <p class="testimonial-role">{{$data['repeater_data']['repeater_role_'][$key]}}</p>
                                    @endif
                                    
                                    @if(!empty($data['repeater_data']['repeater_company_'][$key] ?? ''))
                                        <p class="testimonial-company">{{$data['repeater_data']['repeater_company_'][$key]}}</p>
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
.testimonial-card {
    padding: 30px;
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: 100%;
    transition: all 0.3s;
}
.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}
.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
}
.testimonial-image img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}
.testimonial-name {
    margin: 0;
    font-weight: 600;
}
.testimonial-role {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}
.testimonial-company {
    margin: 0;
    color: #999;
    font-size: 0.85rem;
}
</style>

