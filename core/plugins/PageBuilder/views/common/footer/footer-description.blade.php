<div class="footer-widget footer-description-widget" 
     data-padding-top="{{$data['padding_top']}}"
     data-padding-bottom="{{$data['padding_bottom']}}"
     id="{{$data['section_id']}}">
    <div class="footer-description-wrapper" style="text-align: {{$data['alignment']}}; {{!empty($data['text_color']) ? 'color: ' . $data['text_color'] . ';' : ''}}">
        @if(!empty($data['description']))
            <div class="footer-description-text">
                {!! $data['description'] !!}
            </div>
        @endif
    </div>
</div>

<style>
.footer-description-widget {
    margin-bottom: 0;
}
.footer-description-wrapper {
    margin-bottom: 0;
    margin-top: 15px;
}
.footer-description-text {
    line-height: 1.7;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.8);
    max-width: 100%;
}
.footer-description-text p {
    margin-bottom: 12px;
    line-height: 1.7;
}
.footer-description-text p:last-child {
    margin-bottom: 0;
}
</style>

