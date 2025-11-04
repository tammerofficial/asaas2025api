<div class="text-editor-section common-text-editor-section" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-content text-{{$data['align']}}">
                    {!! $data['content'] !!}
                </div>
            </div>
        </div>
    </div>
</div>

