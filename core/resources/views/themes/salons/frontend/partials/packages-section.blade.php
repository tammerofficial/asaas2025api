<!-- Salons Packages Section -->
<section class="salons-packages-section" id="packages">
    <div class="container">
        <div class="salons-section-title">
            <h2>{{ __('باقات خاصة') }}</h2>
            <p>{{ __('اختر الباقة التي تناسبك') }}</p>
        </div>
        
        <div class="row">
            @php
                $packages = [
                    [
                        'name' => __('باقة العروس'),
                        'price' => '300',
                        'features' => [
                            __('مكياج عروس كامل'),
                            __('تصفيف شعر'),
                            __('مناكير وباديكير'),
                            __('فايسيال'),
                            __('استشارة مجانية'),
                        ],
                        'popular' => false,
                    ],
                    [
                        'name' => __('باقة الذهبية'),
                        'price' => '150',
                        'features' => [
                            __('قص شعر'),
                            __('تلوين شعر'),
                            __('فايسيال'),
                            __('مناكير'),
                            __('قناع شعر'),
                        ],
                        'popular' => true,
                    ],
                    [
                        'name' => __('باقة الفضي'),
                        'price' => '80',
                        'features' => [
                            __('قص شعر'),
                            __('تصفيف شعر'),
                            __('مناكير'),
                            __('مكياج خفيف'),
                        ],
                        'popular' => false,
                    ],
                ];
            @endphp
            
            @foreach($packages as $package)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="salons-package-card {{ $package['popular'] ? 'popular' : '' }}">
                        @if($package['popular'])
                            <div class="text-center mb-3">
                                <span class="badge bg-warning text-dark">{{ __('الأكثر شعبية') }}</span>
                            </div>
                        @endif
                        <h3 class="salons-package-title">{{ $package['name'] }}</h3>
                        <div class="salons-package-price">
                            {{ $package['price'] }} {{ __('دينار') }}
                        </div>
                        <ul class="salons-package-features">
                            @foreach($package['features'] as $feature)
                                <li>
                                    <i class="las la-check-circle"></i>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                        <button class="salons-package-btn" onclick="openBookingModal('{{ $package['name'] }}')">
                            {{ __('احجز الآن') }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
.salons-package-card.popular {
    border: 3px solid var(--salon-gold);
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .salons-package-card.popular {
        transform: scale(1);
    }
}
</style>

