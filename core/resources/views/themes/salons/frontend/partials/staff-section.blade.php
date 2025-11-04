<!-- Salons Staff Section -->
<section class="salons-staff-section" id="staff">
    <div class="container">
        <div class="salons-section-title">
            <h2>{{ __('فريقنا المحترف') }}</h2>
            <p>{{ __('تعرف على خبرائنا في التجميل والجمال') }}</p>
        </div>
        
        <div class="row">
            @php
                $staff = [
                    [
                        'name' => __('سارة أحمد'),
                        'specialization' => __('خبيرة قص شعر'),
                        'photo' => 'staff/stylist-1.jpg',
                        'social' => [
                            'instagram' => '#',
                            'facebook' => '#',
                        ],
                    ],
                    [
                        'name' => __('فاطمة علي'),
                        'specialization' => __('خبيرة تلوين'),
                        'photo' => 'staff/stylist-2.jpg',
                        'social' => [
                            'instagram' => '#',
                            'facebook' => '#',
                        ],
                    ],
                    [
                        'name' => __('نورا محمد'),
                        'specialization' => __('خبيرة مكياج'),
                        'photo' => 'staff/stylist-3.jpg',
                        'social' => [
                            'instagram' => '#',
                            'facebook' => '#',
                        ],
                    ],
                    [
                        'name' => __('ليلى خالد'),
                        'specialization' => __('خبيرة فايسيال'),
                        'photo' => 'staff/stylist-4.jpg',
                        'social' => [
                            'instagram' => '#',
                            'facebook' => '#',
                        ],
                    ],
                ];
            @endphp
            
            @foreach($staff as $member)
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="salons-staff-card">
                        <img src="{{ theme_assets('img/' . $member['photo'], 'salons') }}" 
                             alt="{{ $member['name'] }}" 
                             class="salons-staff-photo"
                             onerror="this.src='https://via.placeholder.com/400x400/D4AF37/E91E63?text={{ $member['name'] }}'">
                        <h4 class="salons-staff-name">{{ $member['name'] }}</h4>
                        <p class="salons-staff-specialization">{{ $member['specialization'] }}</p>
                        <div class="salons-staff-social">
                            @if(isset($member['social']['instagram']))
                                <a href="{{ $member['social']['instagram'] }}" target="_blank">
                                    <i class="lab la-instagram"></i>
                                </a>
                            @endif
                            @if(isset($member['social']['facebook']))
                                <a href="{{ $member['social']['facebook'] }}" target="_blank">
                                    <i class="lab la-facebook"></i>
                                </a>
                            @endif
                        </div>
                        <button class="salons-staff-book-btn" onclick="openBookingModal('', '{{ $member['name'] }}')">
                            {{ __('احجز مع') }} {{ $member['name'] }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

