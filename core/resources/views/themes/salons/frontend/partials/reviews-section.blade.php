<!-- Salons Reviews Section -->
<section class="salons-reviews-section" id="reviews">
    <div class="container">
        <div class="salons-section-title">
            <h2>{{ __('آراء عملائنا') }}</h2>
            <p>{{ __('ما يقوله عملاؤنا عن خدماتنا') }}</p>
        </div>
        
        <div class="row">
            @php
                $reviews = [
                    [
                        'name' => __('سارة محمد'),
                        'photo' => 'reviews/customer-1.jpg',
                        'rating' => 5,
                        'text' => __('تجربة رائعة! الفريق محترف جداً والخدمة ممتازة. أنصح الجميع بالزيارة.'),
                    ],
                    [
                        'name' => __('فاطمة أحمد'),
                        'photo' => 'reviews/customer-2.jpg',
                        'rating' => 5,
                        'text' => __('أفضل صالون في المنطقة! النتيجة أكثر من رائعة والخدمة ممتازة.'),
                    ],
                    [
                        'name' => __('نورا خالد'),
                        'photo' => 'reviews/customer-3.jpg',
                        'rating' => 5,
                        'text' => __('خدمة احترافية ومكان نظيف ومريح. بالتأكيد سأعود مرة أخرى.'),
                    ],
                    [
                        'name' => __('ليلى علي'),
                        'photo' => 'reviews/customer-4.jpg',
                        'rating' => 5,
                        'text' => __('فريق محترف ومكان راقي. أنصح الجميع بتجربة خدماتهم.'),
                    ],
                ];
            @endphp
            
            @foreach($reviews as $review)
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="salons-review-card">
                        <img src="{{ theme_assets('img/' . $review['photo'], 'salons') }}" 
                             alt="{{ $review['name'] }}" 
                             class="salons-review-photo"
                             onerror="this.src='https://via.placeholder.com/400x400/D4AF37/E91E63?text={{ $review['name'] }}'">
                        <div class="salons-review-stars">
                            @for($i = 0; $i < $review['rating']; $i++)
                                <i class="las la-star"></i>
                            @endfor
                        </div>
                        <p class="salons-review-text">"{{ $review['text'] }}"</p>
                        <h5 class="salons-review-name">{{ $review['name'] }}</h5>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

