<!-- 62d555ba-d09e-49af-9624-a3e79a446ce5 bb139175-4510-4b4a-8a70-190a66dba3d0 -->
# Footer PageBuilder Widgets Implementation Plan

## Overview

إضافة 5 widgets منفصلة للـ Footer Builder في PageBuilder لتوفير:

- FooterLogo: عرض الشعار
- FooterDescription: عرض الوصف تحت الشعار
- FooterSocialMedia: عرض وسائل التواصل الاجتماعي
- FooterUsefulLinks: عرض روابط مفيدة من النظام (Home, Products, Categories, Blog)
- FooterPagesLinks: عرض روابط الصفحات من جدول Page (من نحن، الشروط والأحكام، إلخ)

## Implementation Steps

### Step 1: Create FooterLogo Widget

**File**: `core/plugins/PageBuilder/Addons/Common/Footer/FooterLogo/FooterLogo.php`

- Extend `PageBuilderBase`
- Fields:
  - `Image` field for logo upload
  - `Text` field for alt text
  - `Text` field for link URL (default: home)
  - `Select` field for alignment (left/center/right)
- View: `core/plugins/PageBuilder/views/common/footer/footer-logo.blade.php`
- Preview image: `assets/plugins/PageBuilder/images/Common/Footer/footer-logo.jpg`

### Step 2: Create FooterDescription Widget

**File**: `core/plugins/PageBuilder/Addons/Common/Footer/FooterDescription/FooterDescription.php`

- Extend `PageBuilderBase`
- Fields:
  - `Textarea` field for description text
  - `Select` field for text alignment (left/center/right)
  - `ColorPicker` field for text color (optional)
- View: `core/plugins/PageBuilder/views/common/footer/footer-description.blade.php`
- Preview image: `assets/plugins/PageBuilder/images/Common/Footer/footer-description.jpg`

### Step 3: Create FooterSocialMedia Widget

**File**: `core/plugins/PageBuilder/Addons/Common/Footer/FooterSocialMedia/FooterSocialMedia.php`

- Extend `PageBuilderBase`
- Fields:
  - `Repeater` field with:
    - Icon Picker
    - Text field for URL
    - Text field for title/tooltip
  - `Select` field for icon style (rounded/square/circle)
  - `Select` field for icon size (small/medium/large)
  - `Select` field for alignment (left/center/right)
- View: `core/plugins/PageBuilder/views/common/footer/footer-social-media.blade.php`
- Preview image: `assets/plugins/PageBuilder/images/Common/Footer/footer-social-media.jpg`

### Step 4: Create FooterUsefulLinks Widget

**File**: `core/plugins/PageBuilder/Addons/Common/Footer/FooterUsefulLinks/FooterUsefulLinks.php`

- Extend `PageBuilderBase`
- Logic:
  - Get links from system automatically:
    - Home: `get_static_option('home_page')` → get page slug → generate URL
    - Products: Check if products module active → generate products route
    - Categories: Check if products module active → generate categories route
    - Blog: `get_static_option('blog_page')` → get page slug → generate URL
  - Allow admin to enable/disable each link via `Switcher` fields
  - `Text` field for widget title (default: "روابط مفيدة")
  - `Select` field for alignment (left/center/right)
- View: `core/plugins/PageBuilder/views/common/footer/footer-useful-links.blade.php`
- Preview image: `assets/plugins/PageBuilder/images/Common/Footer/footer-useful-links.jpg`

### Step 5: Create FooterPagesLinks Widget

**File**: `core/plugins/PageBuilder/Addons/Common/Footer/FooterPagesLinks/FooterPagesLinks.php`

- Extend `PageBuilderBase`
- Logic:
  - Get all pages from `Page::where('status', 1)->get()`
  - Allow admin to select which pages to display via `Select` field with multiple selection
  - Or display all pages by default with option to exclude specific pages
  - `Text` field for widget title (default: "صفحات مهمة")
  - `Select` field for alignment (left/center/right)
- View: `core/plugins/PageBuilder/views/common/footer/footer-pages-links.blade.php`
- Preview image: `assets/plugins/PageBuilder/images/Common/Footer/footer-pages-links.jpg`

### Step 6: Register All Footer Widgets

**File**: `core/plugins/PageBuilder/PageBuilderSetup.php`

- Add `use` statements for all 5 Footer widgets
- Add all 5 widgets to `$globalAddons` array in `registerd_widgets()` method

### Step 7: Create View Files

Create Blade view files for each widget:

- `core/plugins/PageBuilder/views/common/footer/footer-logo.blade.php`
- `core/plugins/PageBuilder/views/common/footer/footer-description.blade.php`
- `core/plugins/PageBuilder/views/common/footer/footer-social-media.blade.php`
- `core/plugins/PageBuilder/views/common/footer/footer-useful-links.blade.php`
- `core/plugins/PageBuilder/views/common/footer/footer-pages-links.blade.php`

### Step 8: Create Preview Images

Create directory: `assets/plugins/PageBuilder/images/Common/Footer/`

Generate 5 placeholder SVG images for previews

### Step 9: Helper Functions for Useful Links

Create helper functions or use existing ones to generate URLs:

- Home URL: `url('/')` or get page slug from `get_static_option('home_page')`
- Products URL: Check if route exists `route('tenant.frontend.products')` or similar
- Categories URL: Check if route exists `route('tenant.frontend.categories')` or similar
- Blog URL: Get page slug from `get_static_option('blog_page')` and generate URL

### Step 10: Testing

- Test each widget in Footer Builder admin panel
- Test frontend rendering
- Verify all links work correctly
- Test responsive design

## Technical Notes

### Useful Links Implementation

```php
// In FooterUsefulLinks.php frontend_render()
$home_page_id = get_static_option('home_page');
$home_url = $home_page_id ? get_page_slug($home_page_id) : url('/');

$blog_page_id = get_static_option('blog_page');
$blog_url = $blog_page_id ? get_page_slug($blog_page_id) : null;

$products_url = null;
if (isPluginActive('Product')) {
    $products_url = route('tenant.frontend.products') ?? url('/products');
}

$categories_url = null;
if (isPluginActive('Product')) {
    $categories_url = route('tenant.frontend.categories') ?? url('/categories');
}
```

### Pages Links Implementation

```php
// In FooterPagesLinks.php frontend_render()
$selected_page_ids = $this->setting_item('selected_pages') ?? [];
$pages = Page::where('status', 1)
    ->when(!empty($selected_page_ids), function($query) use ($selected_page_ids) {
        return $query->whereIn('id', $selected_page_ids);
    })
    ->get();
```

### View Structure

All footer widgets should follow this structure:

```blade
<div class="footer-widget footer-{widget-name}" 
     data-padding-top="{{$data['padding_top']}}"
     data-padding-bottom="{{$data['padding_bottom']}}"
     id="{{$data['section_id']}}">
    {{-- Widget content --}}
</div>
```

## Files to Create/Modify

### New Files (15 files):

1. `core/plugins/PageBuilder/Addons/Common/Footer/FooterLogo/FooterLogo.php`
2. `core/plugins/PageBuilder/Addons/Common/Footer/FooterDescription/FooterDescription.php`
3. `core/plugins/PageBuilder/Addons/Common/Footer/FooterSocialMedia/FooterSocialMedia.php`
4. `core/plugins/PageBuilder/Addons/Common/Footer/FooterUsefulLinks/FooterUsefulLinks.php`
5. `core/plugins/PageBuilder/Addons/Common/Footer/FooterPagesLinks/FooterPagesLinks.php`
6. `core/plugins/PageBuilder/views/common/footer/footer-logo.blade.php`
7. `core/plugins/PageBuilder/views/common/footer/footer-description.blade.php`
8. `core/plugins/PageBuilder/views/common/footer/footer-social-media.blade.php`
9. `core/plugins/PageBuilder/views/common/footer/footer-useful-links.blade.php`
10. `core/plugins/PageBuilder/views/common/footer/footer-pages-links.blade.php`

11-15. Preview images (5 SVG files)

### Modified Files (1 file):

1. `core/plugins/PageBuilder/PageBuilderSetup.php` - Register widgets

## Dependencies

- PageBuilder system (already exists)
- Page model (already exists)
- StaticOption helpers (already exists)
- Route helpers (already exists)

### To-dos

- [ ] Create FooterLogo widget class and view file
- [ ] Create FooterDescription widget class and view file
- [ ] Create FooterSocialMedia widget class and view file
- [ ] Create FooterUsefulLinks widget with automatic system links (Home, Products, Categories, Blog)
- [ ] Create FooterPagesLinks widget with automatic pages from Page model
- [ ] Register all 5 Footer widgets in PageBuilderSetup.php
- [ ] Create preview images for all 5 Footer widgets
- [ ] Test all Footer widgets in admin panel and frontend