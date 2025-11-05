<div class="footer-widget footer-social-media-widget" 
     data-padding-top="{{$data['padding_top']}}"
     data-padding-bottom="{{$data['padding_bottom']}}"
     id="{{$data['section_id']}}">
    <div class="footer-social-wrapper" style="text-align: {{$data['alignment']}};">
        @if(!empty($data['title']))
            <h4 class="footer-widget-title">{{$data['title']}}</h4>
        @endif
        
        @if(!empty($data['social_links']) && count($data['social_links']) > 0)
            <ul class="footer-social-list footer-social-{{$data['icon_style']}} footer-social-{{$data['icon_size']}}">
                @foreach($data['social_links'] as $link)
                    <li>
                        <a href="{{$link['url']}}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           title="{{$link['title']}}"
                           class="footer-social-link">
                            <i class="{{$link['icon']}}"></i>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<style>
.footer-social-media-widget {
    margin-bottom: 0;
}
.footer-social-wrapper {
    margin-bottom: 0;
    margin-top: 20px;
}
.footer-widget-title {
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: 600;
    color: inherit;
}
.footer-social-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
.footer-social-list li {
    margin: 0;
}
.footer-social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    color: rgba(255, 255, 255, 0.8);
    background: rgba(255, 255, 255, 0.1);
}
.footer-social-link:hover {
    transform: translateY(-2px);
    color: #fff;
    background: rgba(255, 255, 255, 0.2);
}

/* Icon Sizes */
.footer-social-small .footer-social-link {
    width: 36px;
    height: 36px;
    font-size: 14px;
}
.footer-social-medium .footer-social-link {
    width: 40px;
    height: 40px;
    font-size: 18px;
}
.footer-social-large .footer-social-link {
    width: 48px;
    height: 48px;
    font-size: 22px;
}

/* Icon Styles */
.footer-social-circle .footer-social-link {
    border-radius: 50%;
}
.footer-social-rounded .footer-social-link {
    border-radius: 8px;
}
.footer-social-square .footer-social-link {
    border-radius: 4px;
}
</style>

