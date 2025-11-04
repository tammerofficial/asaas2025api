<div class="tabs-section common-tabs-section" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        @if(array_key_exists('repeater_tab_title_', $data['repeater_data'] ?? []))
            <ul class="nav nav-tabs tabs-nav-{{$data['style']}}" role="tablist">
                @foreach($data['repeater_data']['repeater_tab_title_'] as $key => $title)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{$key == $data['default_tab'] ? 'active' : ''}}" 
                                id="tab-{{$key}}" 
                                data-bs-toggle="tab" 
                                data-bs-target="#tab-content-{{$key}}" 
                                type="button" 
                                role="tab">
                            {{$title}}
                        </button>
                    </li>
                @endforeach
            </ul>
            
            <div class="tab-content mt-4">
                @foreach($data['repeater_data']['repeater_tab_title_'] as $key => $title)
                    <div class="tab-pane fade {{$key == $data['default_tab'] ? 'show active' : ''}}" 
                         id="tab-content-{{$key}}" 
                         role="tabpanel">
                        <div class="tab-content-body">
                            {!! $data['repeater_data']['repeater_tab_content_'][$key] ?? '' !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
.tabs-nav-pills .nav-link {
    border-radius: 50px;
    margin-right: 10px;
}
.tabs-nav-underline .nav-link {
    border-bottom: 2px solid transparent;
}
.tabs-nav-underline .nav-link.active {
    border-bottom-color: var(--main-color-one, #007bff);
}
</style>

