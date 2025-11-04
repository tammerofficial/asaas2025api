<!-- Salons Hero Section - Full Screen with CTA -->
<section class="salons-hero-section" style="background-image: url('{{ theme_assets('img/hero-bg.jpg', 'salons') }}');">
    <div class="container">
        <div class="salons-hero-content">
            <h1>{{ get_static_option('hero_title') ?? __('استمتع بتجربة جمال فاخرة') }}</h1>
            <p>{{ get_static_option('hero_subtitle') ?? __('نقدم لك أفضل خدمات التجميل والجمال مع فريق محترف') }}</p>
            <a href="#booking" class="salons-hero-cta" onclick="document.getElementById('booking-calendar-modal').style.display='block'">
                {{ __('احجز موعدك الآن') }}
            </a>
        </div>
    </div>
</section>

