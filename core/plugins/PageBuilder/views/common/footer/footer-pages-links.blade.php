<div class="footer-widget footer-pages-links-widget" 
     data-padding-top="{{$data['padding_top']}}"
     data-padding-bottom="{{$data['padding_bottom']}}"
     id="{{$data['section_id']}}">
    <div class="footer-pages-links-wrapper" style="text-align: {{$data['alignment']}};">
        @if(!empty($data['title']))
            <h4 class="footer-widget-title">{{$data['title']}}</h4>
        @endif
        
        @if(!empty($data['links']) && count($data['links']) > 0)
            <ul class="footer-links-list">
                @foreach($data['links'] as $link)
                    <li>
                        <a href="{{$link['url']}}" class="footer-link">
                            {{$link['label']}}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<style>
.footer-pages-links-widget {
    margin-bottom: 0;
}
.footer-pages-links-wrapper {
    margin-bottom: 0;
}
.footer-widget-title {
    margin-bottom: 18px;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    display: inline-block;
    padding: 4px 12px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.05);
}
.footer-links-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.footer-links-list li {
    margin-bottom: 10px;
}
.footer-links-list li:last-child {
    margin-bottom: 0;
}
.footer-link {
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    font-size: 14px;
    line-height: 1.6;
}
.footer-link:hover {
    color: #fff;
    padding-left: 8px;
    opacity: 1;
}
[dir="rtl"] .footer-link:hover {
    padding-left: 0;
    padding-right: 8px;
}
</style>

