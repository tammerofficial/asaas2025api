<!-- Salons Footer -->
<footer class="footer-area theme-salons-footer">
    <div class="container-three">
        <div class="footer-middle padding-top-30 padding-bottom-60">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <h4 class="footer-title">{{ __('عن الصالون') }}</h4>
                        <p>{{ __('صالون فاخر يوفر أفضل خدمات التجميل والجمال مع فريق محترف ومتمرس. نحن ملتزمون بتقديم تجربة فريدة لعملائنا.') }}</p>
                        <div class="footer-social">
                            <ul>
                                <li><a href="#"><i class="lab la-instagram"></i></a></li>
                                <li><a href="#"><i class="lab la-facebook"></i></a></li>
                                <li><a href="#"><i class="lab la-twitter"></i></a></li>
                                <li><a href="#"><i class="lab la-whatsapp"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <h4 class="footer-title">{{ __('روابط سريعة') }}</h4>
                        <ul class="footer-list">
                            <li><a href="#services">{{ __('خدماتنا') }}</a></li>
                            <li><a href="#staff">{{ __('فريقنا') }}</a></li>
                            <li><a href="#gallery">{{ __('معرض الأعمال') }}</a></li>
                            <li><a href="#packages">{{ __('الباقات') }}</a></li>
                            <li><a href="#reviews">{{ __('آراء العملاء') }}</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <h4 class="footer-title">{{ __('ساعات العمل') }}</h4>
                        <ul class="footer-list">
                            <li>{{ __('السبت - الخميس') }}: 9:00 ص - 9:00 م</li>
                            <li>{{ __('الجمعة') }}: 2:00 م - 9:00 م</li>
                            <li><strong>{{ __('للاستفسارات والحجوزات') }}</strong></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <h4 class="footer-title">{{ __('اتصل بنا') }}</h4>
                        <ul class="footer-list">
                            <li>
                                <i class="las la-map-marker-alt"></i>
                                {{ get_static_option('site_address') ?? __('الكويت، السالمية') }}
                            </li>
                            <li>
                                <i class="las la-phone"></i>
                                <a href="tel:{{ get_static_option('site_phone') ?? '96512345678' }}">
                                    {{ get_static_option('site_phone') ?? '+965 1234 5678' }}
                                </a>
                            </li>
                            <li>
                                <i class="las la-envelope"></i>
                                <a href="mailto:{{ get_static_option('site_email') ?? 'info@salon.com' }}">
                                    {{ get_static_option('site_email') ?? 'info@salon.com' }}
                                </a>
                            </li>
                        </ul>
                        <div class="footer-cta mt-4">
                            <a href="javascript:void(0)" class="salons-hero-cta" onclick="openBookingModal()">
                                {{ __('احجز موعدك الآن') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="copyright-area copyright-border">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="copyright-contents">
                        {!! get_footer_copyright_text() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.theme-salons-footer {
    background: linear-gradient(135deg, rgba(212, 175, 55, 0.05), rgba(233, 30, 99, 0.05));
    padding-top: 60px;
}

.theme-salons-footer .footer-title {
    font-family: var(--playfair-font);
    font-size: 24px;
    color: var(--heading-color);
    margin-bottom: 25px;
    position: relative;
    padding-bottom: 15px;
}

.theme-salons-footer .footer-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(90deg, var(--salon-gold), var(--salon-pink));
}

.theme-salons-footer .footer-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.theme-salons-footer .footer-list li {
    margin-bottom: 12px;
    color: var(--light-color);
}

.theme-salons-footer .footer-list li a {
    color: var(--light-color);
    transition: color 0.3s ease;
}

.theme-salons-footer .footer-list li a:hover {
    color: var(--salon-pink);
}

.theme-salons-footer .footer-list li i {
    color: var(--salon-gold);
    margin-right: 8px;
}

.theme-salons-footer .footer-social ul {
    list-style: none;
    padding: 0;
    margin: 20px 0 0;
    display: flex;
    gap: 10px;
}

.theme-salons-footer .footer-social ul li a {
    display: inline-block;
    width: 40px;
    height: 40px;
    line-height: 40px;
    text-align: center;
    background: var(--salon-gold);
    color: #fff;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.theme-salons-footer .footer-social ul li a:hover {
    background: var(--salon-pink);
    transform: translateY(-3px);
}

.salons-book-appointment-btn {
    display: inline-block;
    padding: 12px 25px;
    background: var(--salon-pink);
    color: #fff !important;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    margin-left: 15px;
    font-size: 14px;
}

.salons-book-appointment-btn:hover {
    background: var(--salon-gold);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
    color: #fff !important;
}

.salons-book-appointment-btn i {
    margin-left: 5px;
}

@media (max-width: 768px) {
    .salons-book-appointment-btn {
        margin-left: 0;
        margin-top: 10px;
        display: block;
        text-align: center;
    }
}
</style>

