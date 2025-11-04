/**
 * Salons Theme - Booking Calendar JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize date picker with minimum date as today
    const dateInput = document.getElementById('booking-date');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    }
    
    // Time slot selection
    const timeSlots = document.querySelectorAll('.salons-time-slot');
    timeSlots.forEach(slot => {
        slot.addEventListener('click', function() {
            // Remove active class from all slots
            timeSlots.forEach(s => s.classList.remove('active'));
            
            // Add active class to clicked slot
            this.classList.add('active');
            
            // Set selected time
            const selectedTimeInput = document.getElementById('selected-time');
            if (selectedTimeInput) {
                selectedTimeInput.value = this.dataset.time;
            }
        });
    });
    
    // Booking form submission
    const bookingForm = document.getElementById('booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            const service = document.getElementById('booking-service').value;
            const staff = document.getElementById('booking-staff').value;
            const date = document.getElementById('booking-date').value;
            const time = document.getElementById('selected-time').value;
            const name = this.querySelector('input[name="name"]').value;
            const phone = this.querySelector('input[name="phone"]').value;
            
            if (!service || !staff || !date || !time || !name || !phone) {
                alert('{{ __("الرجاء ملء جميع الحقول المطلوبة") }}');
                return;
            }
            
            // Here you would typically send the form data to your backend
            // For now, we'll show a success message
            const formData = new FormData(this);
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.textContent = '{{ __("جاري الحجز...") }}';
            submitButton.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                alert('{{ __("شكراً لك! سيتم التواصل معك قريباً لتأكيد الحجز") }}');
                
                // Close modal
                const modalElement = document.getElementById('booking-calendar-modal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                }
                
                // Reset form
                this.reset();
                timeSlots.forEach(s => s.classList.remove('active'));
                
                // Reset button
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            }, 1000);
        });
    }
    
    // Open booking modal function (global)
    window.openBookingModal = function(service = '', staff = '') {
        const modalElement = document.getElementById('booking-calendar-modal');
        if (!modalElement) return;
        
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        // Set service if provided
        if (service) {
            const serviceSelect = document.getElementById('booking-service');
            if (serviceSelect) {
                const serviceValue = service.toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^a-z0-9-]/g, '');
                serviceSelect.value = serviceValue;
            }
        }
        
        // Set staff if provided
        if (staff) {
            const staffSelect = document.getElementById('booking-staff');
            if (staffSelect) {
                const staffValue = staff.toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^a-z0-9-]/g, '');
                staffSelect.value = staffValue;
            }
        }
    };
    
    // Smooth scroll to booking section
    const bookButtons = document.querySelectorAll('a[href="#booking"], a[href="#services"]');
    bookButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            } else {
                // If section doesn't exist, open booking modal
                openBookingModal();
            }
        });
    });
});

