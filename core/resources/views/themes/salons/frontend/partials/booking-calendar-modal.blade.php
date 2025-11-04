<!-- Salons Booking Calendar Modal -->
<div class="modal fade salons-booking-modal" id="booking-calendar-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('احجز موعدك') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="salons-booking-form" id="booking-form">
                    @csrf
                    
                    <!-- Service Selection -->
                    <div class="mb-4">
                        <label class="form-label">{{ __('اختر الخدمة') }}</label>
                        <select class="form-select" name="service" id="booking-service" required>
                            <option value="">{{ __('-- اختر الخدمة --') }}</option>
                            <option value="haircut">{{ __('قص الشعر') }}</option>
                            <option value="hair-coloring">{{ __('تلوين الشعر') }}</option>
                            <option value="facial">{{ __('فايسيال') }}</option>
                            <option value="nail">{{ __('مناكير') }}</option>
                            <option value="spa">{{ __('سبا') }}</option>
                            <option value="makeup">{{ __('مكياج') }}</option>
                        </select>
                    </div>
                    
                    <!-- Staff Selection -->
                    <div class="mb-4">
                        <label class="form-label">{{ __('اختر الخبير') }}</label>
                        <select class="form-select" name="staff" id="booking-staff" required>
                            <option value="">{{ __('-- اختر الخبير --') }}</option>
                            <option value="sara">{{ __('سارة أحمد') }}</option>
                            <option value="fatima">{{ __('فاطمة علي') }}</option>
                            <option value="nora">{{ __('نورا محمد') }}</option>
                            <option value="layla">{{ __('ليلى خالد') }}</option>
                        </select>
                    </div>
                    
                    <!-- Date Selection -->
                    <div class="mb-4">
                        <label class="form-label">{{ __('اختر التاريخ') }}</label>
                        <input type="date" class="form-control" name="date" id="booking-date" required min="{{ date('Y-m-d') }}">
                    </div>
                    
                    <!-- Time Slots -->
                    <div class="mb-4">
                        <label class="form-label">{{ __('اختر الوقت') }}</label>
                        <div class="salons-time-slots" id="time-slots">
                            @php
                                $timeSlots = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
                            @endphp
                            @foreach($timeSlots as $time)
                                <div class="salons-time-slot" data-time="{{ $time }}" onclick="selectTimeSlot(this)">
                                    {{ $time }}
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="time" id="selected-time" required>
                    </div>
                    
                    <!-- Customer Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('الاسم') }}</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('رقم الهاتف') }}</label>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">{{ __('ملاحظات') }}</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="salons-hero-cta">
                            {{ __('تأكيد الحجز') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openBookingModal(service = '', staff = '') {
    const modal = document.getElementById('booking-calendar-modal');
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    if(service) {
        document.getElementById('booking-service').value = service.toLowerCase().replace(/\s+/g, '-');
    }
    
    if(staff) {
        document.getElementById('booking-staff').value = staff.toLowerCase().replace(/\s+/g, '-');
    }
}

function selectTimeSlot(element) {
    // Remove active class from all slots
    document.querySelectorAll('.salons-time-slot').forEach(slot => {
        slot.classList.remove('active');
    });
    
    // Add active class to clicked slot
    element.classList.add('active');
    
    // Set selected time
    document.getElementById('selected-time').value = element.dataset.time;
}

// Form submission
document.getElementById('booking-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Here you would typically send the form data to your backend
    // For now, we'll just show an alert
    alert('{{ __('شكراً لك! سيتم التواصل معك قريباً لتأكيد الحجز') }}');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('booking-calendar-modal'));
    modal.hide();
    
    // Reset form
    this.reset();
    document.querySelectorAll('.salons-time-slot').forEach(slot => {
        slot.classList.remove('active');
    });
});
</script>

