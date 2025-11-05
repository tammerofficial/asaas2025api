<div class="footer-widget footer-logo-widget" 
     data-padding-top="{{$data['padding_top']}}"
     data-padding-bottom="{{$data['padding_bottom']}}"
     id="{{$data['section_id']}}">
    <div class="footer-logo-wrapper" style="text-align: {{$data['alignment']}};">
        @if(!empty($data['logo_id']))
            <a href="{{$data['link_url']}}" class="footer-logo-link">
                {!! render_image_markup_by_attachment_id($data['logo_id'], 'full', false, false, false, $data['alt_text']) !!}
            </a>
        @else
            <a href="{{$data['link_url']}}" class="footer-logo-link">
                <span class="footer-logo-text">{{ get_static_option('site_title') ?? 'Logo' }}</span>
            </a>
        @endif
    </div>
</div>

<style>
.footer-logo-widget {
    margin-bottom: 0;
}
.footer-logo-wrapper {
    margin-bottom: 0;
}
.footer-logo-link {
    display: inline-block;
    text-decoration: none;
    transition: opacity 0.3s ease;
}
.footer-logo-link:hover {
    opacity: 0.9;
}
.footer-logo-link img {
    max-width: 100%;
    height: auto;
    max-height: 60px;
    width: auto;
    display: block;
}
.footer-logo-text {
    font-size: 24px;
    font-weight: 700;
    color: inherit;
    letter-spacing: -0.5px;
}
</style>

