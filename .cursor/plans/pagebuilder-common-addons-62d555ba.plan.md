<!-- 62d555ba-d09e-49af-9624-a3e79a446ce5 bb139175-4510-4b4a-8a70-190a66dba3d0 -->
# PageBuilder Common Addons Implementation Plan

## Overview

Create reusable, theme-agnostic PageBuilder addons in `core/plugins/PageBuilder/Addons/Common/` that work across all tenant themes. These addons will be registered globally and available to all tenants regardless of their theme.

## Directory Structure

### Addon Classes (PHP Files)

```
core/plugins/PageBuilder/Addons/Common/
├── Hero/
│   └── Hero.php
├── Heading/
│   └── Heading.php
├── TextEditor/
│   └── TextEditor.php
├── Button/
│   └── Button.php
├── BackgroundOverlay/
│   └── BackgroundOverlay.php
├── IconBox/
│   └── IconBox.php
├── ImageLottie/
│   └── ImageLottie.php
├── PricingTable/
│   └── PricingTable.php
├── Tabs/
│   └── Tabs.php
├── Testimonials/
│   └── Testimonials.php
├── Reviews/
│   └── Reviews.php
├── CallToAction/
│   └── CallToAction.php
├── LogosCarousel/
│   └── LogosCarousel.php
├── StepsTimeline/
│   └── StepsTimeline.php
├── VideoBox/
│   └── VideoBox.php
└── FormWidget/
    └── FormWidget.php
```

### View Files (Blade Templates)

```
core/plugins/PageBuilder/views/common/
├── hero.blade.php
├── heading.blade.php
├── text-editor.blade.php
├── button.blade.php
├── background-overlay.blade.php
├── icon-box.blade.php
├── image-lottie.blade.php
├── pricing-table.blade.php
├── tabs.blade.php
├── testimonials.blade.php
├── reviews.blade.php
├── call-to-action.blade.php
├── logos-carousel.blade.php
├── steps-timeline.blade.php
├── video-box.blade.php
└── form-widget.blade.php
```

**Important Notes:**

- Views are in `core/plugins/PageBuilder/views/common/` (NOT in resources/views)
- Views are registered via `PageBuilderServiceProvider` as namespace `pagebuilder`
- View path in code: `common.hero` (not `common.hero.view`)

## Implementation Steps

### 1. Update PageBuilderSetup Registration

- Modify `core/plugins/PageBuilder/PageBuilderSetup.php`
- Add Common addons to `$globalAddons` array (around line 199)
- Ensure they're available for all tenant themes

### 2. Create Base Addon Structure

Each addon will:

- Extend `PageBuilderBase`
- Implement `admin_render()`, `frontend_render()`, `preview_image()`, `addon_title()`
- Use PageBuilder Fields (Text, Image, Repeater, Select, etc.)
- Support multilingual content
- Include padding/spacing options
- Use `renderView()` for frontend rendering

### 3. Addon Specifications

#### Hero Section

- Fields: Title, Subtitle, Description, Button Text/URL, Background Image, Overlay Color/Opacity, Align (left/center/right)
- View: Full-width hero with customizable content positioning

#### Heading

- Fields: Text, Size (H1-H6), Align, Color, Margin Top/Bottom
- View: Simple heading component

#### Text Editor

- Fields: Content (Summernote/WYSIWYG), Align, Width
- View: Rich text content area

#### Button

- Fields: Text, URL, Icon, Style (primary/secondary), Size, Target (blank/self)
- View: Styled button component

#### Background Overlay

- Fields: Overlay Color, Opacity (0-100), Gradient Support
- View: Overlay layer component (used with other sections)

#### Icon Box / Feature Box

- Fields: Repeater for features (Icon, Title, Description, Link)
- View: Grid/list of feature boxes (AI powered, Dashboard, Analytics, etc.)

#### Image / Lottie Animation

- Fields: Image Upload, Lottie JSON URL, Animation Type, Animation Speed
- View: Animated image/Lottie component

#### Pricing Table

- Fields: Title, Subtitle, Order By, Order, Custom CSS Class
- View: Display tenant's PricePlans with features (essential for SaaS)

#### Tabs / Toggle

- Fields: Repeater for tabs (Tab Title, Tab Content), Default Tab, Style
- View: Tabbed content sections (Features, Integrations, Plans)

#### Testimonials

- Fields: Repeater (Name, Role, Company, Image, Content, Rating)
- View: Testimonial cards/slider

#### Reviews + Stars

- Fields: Repeater (Name, Rating 1-5, Review Text, Date, Image)
- View: Review cards with star ratings

#### Call To Action

- Fields: Title, Subtitle, Primary Button (Text/URL), Secondary Button (Text/URL), Background, Style
- View: CTA section (Buy now, Start free trial)

#### Logos Carousel

- Fields: Repeater (Logo Image, Link, Alt Text), Autoplay, Speed
- View: Carousel of partner/SDK/Integration logos

#### Steps / Timeline

- Fields: Repeater (Step Number, Title, Description, Icon, Date), Layout (vertical/horizontal)
- View: Step-by-step or timeline visualization (Changelog, How it works)

#### Video Box

- Fields: Video URL (YouTube/Vimeo), Thumbnail, Autoplay, Controls
- View: Video embed component (Demo video, system preview)

#### Form Widget

- Fields: Select Form (from FormBuilder), Integration Type (HubSpot/Email/WhatsApp), Custom Fields, Success Message
- View: Lead generation form with integration support

### 4. View Files Location

- Views will be in: `core/resources/views/pagebuilder/addons/common/{addon-name}/`
- Each addon uses `renderView()` method pointing to its view path
- Views should be responsive and use TailwindCSS classes

### 5. Registration Update

Update `PageBuilderSetup.php` to register Common addons:

```php
// Global addons for all theme
$globalAddons = [
    RawHTML::class,
    FaqOne::class,
    // Common addons
    \Plugins\PageBuilder\Addons\Common\Hero\Hero::class,
    \Plugins\PageBuilder\Addons\Common\Heading\Heading::class,
    // ... all other common addons
];
```

### 6. Preview Images

- Create preview images in `core/public/assets/pagebuilder-previews/common/`
- Each addon should have a preview image showing its design

### 7. Enable Method

- All Common addons should return `true` in `enable()` method
- Available for all tenants: `return !is_null(tenant());`

## Files to Create/Modify

### Core Files

1. `core/plugins/PageBuilder/Addons/Common/Hero/Hero.php`
2. `core/plugins/PageBuilder/Addons/Common/Heading/Heading.php`
3. `core/plugins/PageBuilder/Addons/Common/TextEditor/TextEditor.php`
4. `core/plugins/PageBuilder/Addons/Common/Button/Button.php`
5. `core/plugins/PageBuilder/Addons/Common/BackgroundOverlay/BackgroundOverlay.php`
6. `core/plugins/PageBuilder/Addons/Common/IconBox/IconBox.php`
7. `core/plugins/PageBuilder/Addons/Common/ImageLottie/ImageLottie.php`
8. `core/plugins/PageBuilder/Addons/Common/PricingTable/PricingTable.php`
9. `core/plugins/PageBuilder/Addons/Common/Tabs/Tabs.php`
10. `core/plugins/PageBuilder/Addons/Common/Testimonials/Testimonials.php`
11. `core/plugins/PageBuilder/Addons/Common/Reviews/Reviews.php`
12. `core/plugins/PageBuilder/Addons/Common/CallToAction/CallToAction.php`
13. `core/plugins/PageBuilder/Addons/Common/LogosCarousel/LogosCarousel.php`
14. `core/plugins/PageBuilder/Addons/Common/StepsTimeline/StepsTimeline.php`
15. `core/plugins/PageBuilder/Addons/Common/VideoBox/VideoBox.php`
16. `core/plugins/PageBuilder/Addons/Common/FormWidget/FormWidget.php`

### View Files

1. `core/resources/views/pagebuilder/addons/common/hero/view.blade.php`
2. `core/resources/views/pagebuilder/addons/common/heading/view.blade.php`
3. `core/resources/views/pagebuilder/addons/common/text-editor/view.blade.php`
4. `core/resources/views/pagebuilder/addons/common/button/view.blade.php`
5. `core/resources/views/pagebuilder/addons/common/background-overlay/view.blade.php`
6. `core/resources/views/pagebuilder/addons/common/icon-box/view.blade.php`
7. `core/resources/views/pagebuilder/addons/common/image-lottie/view.blade.php`
8. `core/resources/views/pagebuilder/addons/common/pricing-table/view.blade.php`
9. `core/resources/views/pagebuilder/addons/common/tabs/view.blade.php`
10. `core/resources/views/pagebuilder/addons/common/testimonials/view.blade.php`
11. `core/resources/views/pagebuilder/addons/common/reviews/view.blade.php`
12. `core/resources/views/pagebuilder/addons/common/call-to-action/view.blade.php`
13. `core/resources/views/pagebuilder/addons/common/logos-carousel/view.blade.php`
14. `core/resources/views/pagebuilder/addons/common/steps-timeline/view.blade.php`
15. `core/resources/views/pagebuilder/addons/common/video-box/view.blade.php`
16. `core/resources/views/pagebuilder/addons/common/form-widget/view.blade.php`

### Configuration Update

1. `core/plugins/PageBuilder/PageBuilderSetup.php` - Add Common addons to global registration

## Technical Notes

- All addons extend `PageBuilderBase`
- Use `SanitizeInput` for all output
- Support multilingual content via `admin_language_tab()`
- Include padding/spacing options via `padding_fields()`
- Use `section_id_and_class_fields()` for custom CSS
- Views should be mobile-responsive
- Follow existing PageBuilder patterns for consistency

## Safety & Risk Analysis

### Current System Safety

✅ **Safe Features Already in Place:**

- Uses `class_exists()` check before instantiating addons
- Uses `try-catch` in admin panel widget listing
- Uses `enable()` method to conditionally show addons
- `globalAddons` array already exists and is used safely

### Risk Level: LOW ✅

**Why Safe:**

- Adding to `globalAddons` array only (non-breaking)
- Each addon uses `class_exists()` check
- `enable()` method controls visibility
- If addon has errors, it simply won't appear in widget list

### Safety Recommendations

1. **Before Implementation:**

   - Create database and code backup
   - Test in staging/dev environment first
   - Add addons one by one, test each

2. **During Implementation:**

   - Test each addon individually before adding to array
   - Use proper error handling in each addon class
   - Follow existing addon patterns exactly

3. **Code Safety:**

   - Add try-catch in frontend rendering for extra safety
   - Log errors instead of breaking page
   - Return empty string on error (graceful degradation)

### Rollback Plan

If something breaks:

1. **Quick Fix**: Remove Common addons from `globalAddons` array
2. **Full Rollback**: Restore from backup

### Conclusion

✅ **Safe to Proceed** - Addons are additive and won't break existing functionality