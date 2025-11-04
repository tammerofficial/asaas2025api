<div class="steps-timeline-section common-steps-timeline-section layout-{{$data['layout']}}" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        @if(array_key_exists('repeater_title_', $data['repeater_data'] ?? []))
            @if($data['layout'] === 'horizontal')
                <div class="row">
                    @foreach($data['repeater_data']['repeater_title_'] as $key => $title)
                        <div class="col-lg-{{12 / count($data['repeater_data']['repeater_title_'])}} col-md-6 mt-4">
                            <div class="step-item">
                                @if(!empty($data['repeater_data']['repeater_step_number_'][$key] ?? ''))
                                    <div class="step-number">{{$data['repeater_data']['repeater_step_number_'][$key]}}</div>
                                @endif
                                
                                @if(!empty($data['repeater_data']['repeater_icon_'][$key] ?? ''))
                                    <div class="step-icon">
                                        <i class="{{$data['repeater_data']['repeater_icon_'][$key]}}"></i>
                                    </div>
                                @endif
                                
                                @if(!empty($title))
                                    <h4 class="step-title">{{$title}}</h4>
                                @endif
                                
                                @if(!empty($data['repeater_data']['repeater_description_'][$key] ?? ''))
                                    <p class="step-description">{{$data['repeater_data']['repeater_description_'][$key]}}</p>
                                @endif
                                
                                @if(!empty($data['repeater_data']['repeater_date_'][$key] ?? ''))
                                    <span class="step-date">{{$data['repeater_data']['repeater_date_'][$key]}}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="timeline-vertical">
                    @foreach($data['repeater_data']['repeater_title_'] as $key => $title)
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                @if(!empty($data['repeater_data']['repeater_step_number_'][$key] ?? ''))
                                    <span class="step-number">{{$data['repeater_data']['repeater_step_number_'][$key]}}</span>
                                @elseif(!empty($data['repeater_data']['repeater_icon_'][$key] ?? ''))
                                    <i class="{{$data['repeater_data']['repeater_icon_'][$key]}}"></i>
                                @else
                                    <div class="timeline-dot"></div>
                                @endif
                            </div>
                            
                            <div class="timeline-content">
                                @if(!empty($title))
                                    <h4 class="step-title">{{$title}}</h4>
                                @endif
                                
                                @if(!empty($data['repeater_data']['repeater_description_'][$key] ?? ''))
                                    <p class="step-description">{{$data['repeater_data']['repeater_description_'][$key]}}</p>
                                @endif
                                
                                @if(!empty($data['repeater_data']['repeater_date_'][$key] ?? ''))
                                    <span class="step-date">{{$data['repeater_data']['repeater_date_'][$key]}}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>

<style>
.timeline-vertical {
    position: relative;
    padding-left: 40px;
}
.timeline-vertical::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #ddd;
}
.timeline-item {
    position: relative;
    margin-bottom: 40px;
}
.timeline-marker {
    position: absolute;
    left: -32px;
    top: 0;
    width: 30px;
    height: 30px;
    background: var(--main-color-one, #007bff);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    z-index: 1;
}
.step-item {
    text-align: center;
    padding: 20px;
}
.step-number {
    display: inline-block;
    width: 50px;
    height: 50px;
    line-height: 50px;
    background: var(--main-color-one, #007bff);
    color: #fff;
    border-radius: 50%;
    font-weight: bold;
    margin-bottom: 15px;
}
.step-icon {
    font-size: 2.5rem;
    color: var(--main-color-one, #007bff);
    margin-bottom: 15px;
}
</style>

