<div class="button-section common-button-section" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <a href="{{$data['url']}}" 
                   target="{{$data['target']}}"
                   class="btn btn-{{$data['style']}} btn-{{$data['size']}}">
                    @if(!empty($data['icon']))
                        <i class="{{$data['icon']}}"></i>
                    @endif
                    {{$data['text']}}
                </a>
            </div>
        </div>
    </div>
</div>

