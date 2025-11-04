# Salons Theme - Beauty & Personal Services Theme

## Overview
This is a luxury beauty and personal services theme optimized for appointment booking conversion. The theme focuses on creating a premium salon experience with emphasis on booking appointments.

## Features
- ✅ Full-screen hero section with CTA
- ✅ Services showcase with pricing and duration
- ✅ Staff profiles with booking integration
- ✅ Booking calendar modal with time slot selection
- ✅ Gallery slider with before/after images
- ✅ Reviews section with star ratings
- ✅ Packages section for bridal packages and offers
- ✅ FAQ accordion section
- ✅ **Multilingual support** (Arabic & English)
- ✅ **Arabic font (PingAR+)** for RTL/Arabic language

## Theme Structure
- `/assets/css/salons.css` - Main theme styles with luxury/beauty aesthetic + Arabic font support
- `/assets/css/rtl.css` - RTL support for Arabic
- `/assets/js/booking-calendar.js` - Booking calendar functionality
- `/frontend/partials/` - All frontend components (all using translation functions)
- `/headerNavbarArea/` - Customized navbar with booking button
- `/footerWidgetArea/` - Customized footer for salons

## Color Scheme
- Gold (#D4AF37) - Luxury
- Pink (#E91E63) - Beauty
- Purple (#9C27B0) - Elegance
- Rose (#FF6B9D) - Feminine

## Multilingual Support

### Arabic Font (PingAR+)
- Font family: `PingAR+` (PingARLT)
- Automatically applied when `dir="rtl"` or `lang="ar"`
- Font weights: 100-900 (Thin to Heavy)
- All text elements support Arabic font

### Translation
All text uses Laravel's `__()` translation function:
- Arabic: Default text
- English: Can be added via translation files
- Language switching: Supported via Laravel's language system

## Images Required from Freepik

**See `FREEPIK_IMAGES_GUIDE.md` for detailed image requirements.**

### Quick Summary:
- **Hero Background**: Salon interior (1920x1080px)
- **Services**: 6 images (haircut, coloring, facial, nail, spa, makeup) - 800x600px
- **Staff**: 4 professional portraits - 400x400px
- **Gallery**: 6 before/after images - 1200x800px
- **Reviews**: 4 customer photos - 400x400px
- **Screenshot**: Theme preview - primary.jpg

**All images should be from Freepik Free License with salon/beauty keywords.**

## Status
✅ Theme is active (status: true) and ready for use
✅ All components use translation functions
✅ Arabic font support added
✅ RTL support implemented

## Next Steps
1. Download images from Freepik (see FREEPIK_IMAGES_GUIDE.md)
2. Place images in appropriate directories
3. Test theme activation
4. Configure translations if needed

## Files Modified/Created
- ✅ `theme.json` - Theme configuration (status: true)
- ✅ `assets/css/salons.css` - Main styles + Arabic font + multilingual support
- ✅ `assets/js/booking-calendar.js` - Booking functionality
- ✅ `frontend/partials/*.blade.php` - All components with translations
- ✅ `headerNavbarArea/navbar.blade.php` - Customized navbar
- ✅ `footerWidgetArea/footer-salons.blade.php` - Customized footer
- ✅ `FREEPIK_IMAGES_GUIDE.md` - Image requirements guide
