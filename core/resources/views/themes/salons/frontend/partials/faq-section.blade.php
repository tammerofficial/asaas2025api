<!-- Salons FAQ Section -->
<section class="salons-faq-section" id="faq">
    <div class="container">
        <div class="salons-section-title">
            <h2>{{ __('الأسئلة الشائعة') }}</h2>
            <p>{{ __('إجابات على الأسئلة الأكثر شيوعاً') }}</p>
        </div>
        
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                @php
                    $faqs = [
                        [
                            'question' => __('كيف يمكنني حجز موعد؟'),
                            'answer' => __('يمكنك حجز موعد من خلال موقعنا الإلكتروني أو الاتصال بنا مباشرة. ننصح بالحجز مسبقاً لضمان توفر المواعيد المناسبة لك.'),
                        ],
                        [
                            'question' => __('ما هي ساعات العمل؟'),
                            'answer' => __('نعمل من السبت إلى الخميس من الساعة 9 صباحاً حتى 9 مساءً. الجمعة من الساعة 2 ظهراً حتى 9 مساءً.'),
                        ],
                        [
                            'question' => __('هل يمكن إلغاء أو تغيير الموعد؟'),
                            'answer' => __('نعم، يمكنك إلغاء أو تغيير الموعد قبل 24 ساعة على الأقل من موعدك. يرجى الاتصال بنا لإجراء التغييرات.'),
                        ],
                        [
                            'question' => __('ما هي طرق الدفع المتاحة؟'),
                            'answer' => __('نقبل الدفع نقداً أو بالبطاقات المصرفية. كما يمكنك الدفع عبر التحويل البنكي.'),
                        ],
                        [
                            'question' => __('هل تقدمون خدمات للرجال؟'),
                            'answer' => __('نعم، نقدم خدمات متنوعة للرجال مثل قص الشعر وتصفيفه.'),
                        ],
                        [
                            'question' => __('ما هي التجهيزات المطلوبة قبل الحضور؟'),
                            'answer' => __('ننصح بالحضور بشعر نظيف وجاف. إذا كان لديك حساسية من منتجات معينة، يرجى إخبارنا مسبقاً.'),
                        ],
                    ];
                @endphp
                
                @foreach($faqs as $index => $faq)
                    <div class="salons-faq-item">
                        <div class="salons-faq-question" onclick="toggleFAQ(this)">
                            <span>{{ $faq['question'] }}</span>
                            <i class="las la-angle-down"></i>
                        </div>
                        <div class="salons-faq-answer">
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
function toggleFAQ(element) {
    const faqItem = element.closest('.salons-faq-item');
    const isActive = faqItem.classList.contains('active');
    
    // Close all FAQ items
    document.querySelectorAll('.salons-faq-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Open clicked FAQ item if it wasn't active
    if (!isActive) {
        faqItem.classList.add('active');
    }
}
</script>

