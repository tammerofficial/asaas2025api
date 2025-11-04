<!-- 691f9024-aa4c-4be7-ad39-577a66f65f78 6a602b67-b1be-4cda-a0c7-ab39203692c0 -->
# Salons Theme Development Plan

## Overview

Create a new "Salons" theme for beauty and personal services businesses, optimized for appointment booking conversion. The theme will be built by copying the aromatic theme structure and customizing it for salon-specific needs. All images will be sourced from Freepik's free image library.

## Theme Structure

### 1. Theme Directory Setup

- Location: `core/resources/views/themes/salons/`
- Copy structure from `aromatic` theme as base
- Create `theme.json` with salon-specific configuration
- Set `status: true` to activate the theme

### 2. Theme Configuration File

Create `core/resources/views/themes/salons/theme.json`:

```json
{
  "name": "Salons",
  "slug": "salons",
  "description": "Beauty & Personal Services Theme - Luxury experience focused on appointment booking",
  "author": "TammerSaaS",
  "version": "1.0.0",
  "status": true,
  "screenshot": [{"primary": "primary.jpg"}],
  "headerHook": [{
    "loadCoreStyle": {
      "odometer": false,
      "common": false,
      "magnific-popup": false
    },
    "style": ["salons"],
    "rtl_style": ["rtl"],
    "script": [],
    "blade": [],
    "navbarArea": "navbar",
    "breadcrumbArea": "breadcrumb"
  }],
  "footerHook": [{
    "loadCoreScript": {
      "odometer": false,
      "viewport.jquery": false,
      "main": false
    },
    "style": [],
    "script": ["main", "booking-calendar"],
    "blade": [],
    "widgetArea": "footer-salons"
  }]
}
```

### 3. Required Frontend Components

#### Hero Section (Full Screen + CTA)

- File: `frontend/partials/hero-section.blade.php`
- Full-screen hero with Freepik background image (salon interior/beauty scene)
- Prominent "Book Appointment" CTA button
- Smooth scroll to booking section

#### Services Showcase

- File: `frontend/partials/services-showcase.blade.php`
- Service cards with:
  - Service name
  - Price
  - Duration
  - Service image (from Freepik: haircut, coloring, facial, nail, spa)
  - "Book Now" button per service

#### Staff Section

- File: `frontend/partials/staff-section.blade.php`
- Stylist profiles with:
  - Photo (professional portraits from Freepik)
  - Name
  - Specialization
  - Social links
  - "Book with [Name]" button

#### Booking Calendar Integration

- File: `frontend/partials/booking-calendar-modal.blade.php`
- Modal popup with calendar widget
- Time slot selection
- Service selection
- Staff selection
- Form submission

#### Gallery Slider

- File: `frontend/partials/gallery-slider.blade.php`
- Before/After image slider (transformation images from Freepik)
- Lightbox functionality
- Category filtering

#### Reviews Section

- File: `frontend/partials/reviews-section.blade.php`
- Star ratings
- Customer photos (happy customers from Freepik)
- Review text
- Testimonial slider

#### Packages Section

- File: `frontend/partials/packages-section.blade.php`
- Bridal packages
- Special offers
- Package comparison
- Pricing cards

#### FAQ Section

- File: `frontend/partials/faq-section.blade.php`
- Accordion-style FAQ
- Common booking questions
- Service-related Q&A

### 4. Assets Customization

#### CSS Files

- `assets/css/salons.css` - Main theme styles
- `assets/css/rtl.css` - RTL support
- Custom styles for:
  - Hero section (full-screen)
  - Service cards
  - Booking modal
  - Gallery slider
  - Review stars

#### JavaScript Files

- `assets/js/main.js` - Core functionality
- `assets/js/booking-calendar.js` - Calendar integration
- `assets/js/gallery-slider.js` - Gallery functionality

#### Screenshot & Images (from Freepik Free)

- `screenshot/primary.jpg` - Theme preview image (from Freepik)
- `assets/img/hero-bg.jpg` - Hero background (1920x1080px - salon interior)
- `assets/img/services/` - Service images (800x600px):
  - haircut.jpg
  - hair-coloring.jpg
  - facial-treatment.jpg
  - nail-service.jpg
  - spa-treatment.jpg
- `assets/img/staff/` - Staff portraits (400x400px - professional stylists)
- `assets/img/gallery/before-after/` - Gallery images (1200x800px - transformations)
- `assets/img/reviews/` - Customer photos (400x400px - happy clients)

**Freepik Image Search Keywords:**

- Hero: "salon interior", "beauty salon", "hairdresser salon"
- Services: "haircut", "hair coloring", "facial treatment", "nail art", "spa massage"
- Staff: "professional hairstylist", "beauty expert", "makeup artist"
- Gallery: "before after hair", "beauty transformation", "makeup transformation"
- Reviews: "happy customer", "satisfied client", "beauty client"

### 5. Navigation Components

#### Navbar

- File: `headerNavbarArea/navbar.blade.php`
- Customized for salon branding
- "Book Appointment" button in header
- Mobile-responsive menu

#### Footer

- File: `footerWidgetArea/footer-salons.blade.php`
- Contact information
- Social media links
- Quick booking CTA
- Business hours

### 6. Page Layout Files

- `assets/page_layout/home-layout.json` - Homepage layout with all sections
- `assets/page_layout/about-layout.json` - About page layout
- `assets/page_layout/contact-layout.json` - Contact page layout

### 7. Integration Points

- Booking form integration with existing appointment system
- Service display using existing product/service module
- Staff profiles integration
- Gallery integration with media library
- Reviews integration with testimonial/review system

## Implementation Steps

1. Copy aromatic theme structure to salons directory
2. Create theme.json with salons configuration
3. Source and download free images from Freepik for all sections
4. Customize CSS for salon branding (luxury/beauty aesthetic)
5. Create hero section with full-screen design and CTA (using Freepik hero image)
6. Build services showcase with price/duration cards (using Freepik service images)
7. Create staff section with stylist profiles (using Freepik professional portraits)
8. Implement booking calendar modal
9. Build gallery slider with before/after functionality (using Freepik transformation images)
10. Create reviews section with stars and photos (using Freepik customer photos)
11. Build packages section for bridal/offers
12. Add FAQ accordion section
13. Customize navbar and footer
14. Add screenshot image from Freepik
15. Test theme activation and functionality

## Files to Create/Modify

### Core Files

- `core/resources/views/themes/salons/theme.json` (NEW)
- `core/resources/views/themes/salons/screenshot/primary.jpg` (NEW - from Freepik)

### Assets

- `core/resources/views/themes/salons/assets/css/salons.css` (NEW)
- `core/resources/views/themes/salons/assets/css/rtl.css` (NEW)
- `core/resources/views/themes/salons/assets/js/main.js` (NEW)
- `core/resources/views/themes/salons/assets/js/booking-calendar.js` (NEW)
- `core/resources/views/themes/salons/assets/img/hero-bg.jpg` (NEW - from Freepik)
- `core/resources/views/themes/salons/assets/img/services/` (NEW - from Freepik)
- `core/resources/views/themes/salons/assets/img/staff/` (NEW - from Freepik)
- `core/resources/views/themes/salons/assets/img/gallery/before-after/` (NEW - from Freepik)
- `core/resources/views/themes/salons/assets/img/reviews/` (NEW - from Freepik)

### Frontend Components

- `core/resources/views/themes/salons/frontend/partials/hero-section.blade.php` (NEW)
- `core/resources/views/themes/salons/frontend/partials/services-showcase.blade.php` (NEW)
- `core/resources/views/themes/salons/frontend/partials/staff-section.blade.php` (NEW)
- `core/resources/views/themes/salons/frontend/partials/booking-calendar-modal.blade.php` (NEW)
- `core/resources/views/themes/salons/frontend/partials/gallery-slider.blade.php` (NEW)
- `core/resources/views/themes/salons/frontend/partials/reviews-section.blade.php` (NEW)
- `core/resources/views/themes/salons/frontend/partials/packages-section.blade.php` (NEW)
- `core/resources/views/themes/salons/frontend/partials/faq-section.blade.php` (NEW)

### Navigation

- `core/resources/views/themes/salons/headerNavbarArea/navbar.blade.php` (COPY & MODIFY)
- `core/resources/views/themes/salons/footerWidgetArea/footer-salons.blade.php` (NEW)
- `core/resources/views/themes/salons/headerBreadcrumbArea/breadcrumb.blade.php` (COPY)

### Page Layouts

- `core/resources/views/themes/salons/assets/page_layout/home-layout.json` (NEW)
- `core/resources/views/themes/salons/assets/page_layout/about-layout.json` (NEW)
- `core/resources/views/themes/salons/assets/page_layout/contact-layout.json` (NEW)

## Design Focus

- Luxury/Beauty aesthetic
- Conversion-optimized (appointment booking focus)
- Mobile-responsive
- Fast loading
- RTL support for Arabic
- Professional styling matching salon industry standards
- High-quality images from Freepik free library

## Image Sources (Freepik Free)

All images will be sourced from Freepik's free image library with appropriate keywords:

- Salon interiors and beauty scenes
- Professional beauty services
- Stylist and expert portraits
- Before/after transformations
- Happy customers and testimonials

### To-dos

- [ ] Copy aromatic theme directory structure to salons theme directory
- [ ] Create theme.json file with salons configuration and activate theme
- [ ] Create salons.css with luxury/beauty styling and RTL support
- [ ] Build full-screen hero section with CTA Book Appointment button
- [ ] Create services showcase component with price, duration, and booking buttons
- [ ] Build staff section with stylist profiles and booking integration
- [ ] Implement booking calendar modal with time slot selection
- [ ] Build gallery slider with before/after functionality
- [ ] Create reviews section with star ratings and customer photos
- [ ] Build packages section for bridal packages and special offers
- [ ] Add FAQ accordion section with common booking questions
- [ ] Customize navbar and footer for salon branding
- [ ] Create page layout JSON files for homepage, about, and contact pages
- [ ] Add primary.jpg screenshot image for theme preview
- [ ] Test theme activation and verify all components work correctly