<div class="social-icons-section common-social-icons-section" 
     data-padding-top="{{$data['padding_top']}}" 
     data-padding-bottom="{{$data['padding_bottom']}}" 
     id="{{$data['section_id']}}"
     style="text-align: {{$data['alignment']}};">
    <div class="container">
        <div class="social-icons-wrapper" style="gap: {{$data['spacing']}}px;">
            @if(array_key_exists('repeater_platform_', $data['repeater_data'] ?? []))
                @foreach($data['repeater_data']['repeater_platform_'] as $key => $platform)
                    @php
                        $icon = $data['repeater_data']['repeater_icon_'][$key] ?? '';
                        $url = $data['repeater_data']['repeater_url_'][$key] ?? '#';
                    @endphp
                    @if(!empty($url) && $url !== '#')
                        <a href="{{$url}}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="social-icon social-{{$data['style']}} social-{{$data['size']}} social-{{$platform}}"
                           aria-label="{{ucfirst($platform)}}">
                            @if(!empty($icon))
                                <i class="{{$icon}}"></i>
                            @endif
                        </a>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>

<style>
.social-icons-section {
    padding: 30px 0;
}

.social-icons-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
}

.social-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: #fff;
    transition: all 0.3s;
    border: none;
}

/* Sizes */
.social-small {
    width: 35px;
    height: 35px;
    font-size: 1rem;
}

.social-medium {
    width: 45px;
    height: 45px;
    font-size: 1.3rem;
}

.social-large {
    width: 55px;
    height: 55px;
    font-size: 1.6rem;
}

/* Styles */
.social-circle {
    border-radius: 50%;
}

.social-rounded {
    border-radius: 8px;
}

.social-square {
    border-radius: 0;
}

/* Platform Colors */
.social-facebook {
    background: #1877f2;
}

.social-instagram {
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
}

.social-twitter {
    background: #1da1f2;
}

.social-linkedin {
    background: #0077b5;
}

.social-youtube {
    background: #ff0000;
}

.social-pinterest {
    background: #bd081c;
}

.social-whatsapp {
    background: #25d366;
}

.social-tiktok {
    background: #000000;
}

.social-snapchat {
    background: #fffc00;
    color: #000;
}

.social-custom {
    background: var(--main-color-one, #007bff);
}

/* Hover Effects */
.social-icon:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.social-facebook:hover {
    background: #166fe5;
}

.social-twitter:hover {
    background: #1a91da;
}

.social-linkedin:hover {
    background: #006399;
}

.social-youtube:hover {
    background: #e60000;
}

.social-pinterest:hover {
    background: #a30717;
}

.social-whatsapp:hover {
    background: #20ba5a;
}

@media (max-width: 768px) {
    .social-icons-wrapper {
        gap: 10px !important;
    }
    
    .social-medium {
        width: 40px;
        height: 40px;
        font-size: 1.1rem;
    }
    
    .social-large {
        width: 50px;
        height: 50px;
        font-size: 1.4rem;
    }
}
</style>

