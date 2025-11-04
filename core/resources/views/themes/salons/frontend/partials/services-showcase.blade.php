<!-- Salons Services Showcase -->
<section class="salons-services-section" id="services">
    <div class="container">
        <div class="salons-section-title">
            <h2>{{ __('خدماتنا') }}</h2>
            <p>{{ __('اكتشف مجموعة واسعة من خدمات التجميل والجمال') }}</p>
        </div>
        
        <div class="row">
            @php
                $services = [
                    [
                        'name' => __('قص الشعر'),
                        'price' => '25',
                        'duration' => '60',
                        'image' => 'services/haircut.jpg',
                    ],
                    [
                        'name' => __('تلوين الشعر'),
                        'price' => '80',
                        'duration' => '120',
                        'image' => 'services/hair-coloring.jpg',
                    ],
                    [
                        'name' => __('فايسيال'),
                        'price' => '50',
                        'duration' => '90',
                        'image' => 'services/facial-treatment.jpg',
                    ],
                    [
                        'name' => __('مناكير'),
                        'price' => '30',
                        'duration' => '45',
                        'image' => 'services/nail-service.jpg',
                    ],
                    [
                        'name' => __('سبا'),
                        'price' => '100',
                        'duration' => '120',
                        'image' => 'services/spa-treatment.jpg',
                    ],
                    [
                        'name' => __('مكياج'),
                        'price' => '60',
                        'duration' => '90',
                        'image' => 'services/makeup.jpg',
                    ],
                ];
            @endphp
            
            @foreach($services as $service)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="salons-service-card">
                        <img src="{{ theme_assets('img/' . $service['image'], 'salons') }}" 
                             alt="{{ $service['name'] }}" 
                             class="salons-service-image"
                             onerror="this.src='https://via.placeholder.com/800x600/D4AF37/E91E63?text={{ $service['name'] }}'">
                        <div class="salons-service-content">
                            <h3 class="salons-service-title">{{ $service['name'] }}</h3>
                            <div class="salons-service-price">{{ $service['price'] }} {{ __('دينار') }}</div>
                            <div class="salons-service-duration">
                                <i class="las la-clock"></i>
                                {{ $service['duration'] }} {{ __('دقيقة') }}
                            </div>
                            <button class="salons-book-btn" onclick="openBookingModal('{{ $service['name'] }}')">
                                {{ __('احجز الآن') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

