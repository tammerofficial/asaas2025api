<div class="icon-box-section common-icon-box-section" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="row">
            @if(array_key_exists('repeater_title_', $data['repeater_data'] ?? []))
                @foreach($data['repeater_data']['repeater_title_'] as $key => $title)
                    <div class="col-lg-{{12 / (int)$data['columns']}} col-md-6 mt-4">
                        <div class="icon-box-item text-center">
                            @if(!empty($data['repeater_data']['repeater_icon_'][$key] ?? ''))
                                <div class="icon-box-icon mb-3">
                                    <i class="{{$data['repeater_data']['repeater_icon_'][$key]}}"></i>
                                </div>
                            @endif
                            
                            @if(!empty($title))
                                <h4 class="icon-box-title">{{$title}}</h4>
                            @endif
                            
                            @if(!empty($data['repeater_data']['repeater_description_'][$key] ?? ''))
                                <p class="icon-box-description mt-2">
                                    {{$data['repeater_data']['repeater_description_'][$key]}}
                                </p>
                            @endif
                            
                            @if(!empty($data['repeater_data']['repeater_link_'][$key] ?? ''))
                                <a href="{{$data['repeater_data']['repeater_link_'][$key]}}" class="icon-box-link mt-3">
                                    {{__('Learn More')}} <i class="las la-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<style>
.icon-box-item {
    padding: 30px 20px;
    border-radius: 10px;
    transition: all 0.3s;
    height: 100%;
}
.icon-box-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.icon-box-icon {
    font-size: 3rem;
    color: var(--main-color-one, #007bff);
}
.icon-box-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
}
.icon-box-description {
    color: #666;
    line-height: 1.6;
}
.icon-box-link {
    color: var(--main-color-one, #007bff);
    text-decoration: none;
    font-weight: 500;
}
</style>

