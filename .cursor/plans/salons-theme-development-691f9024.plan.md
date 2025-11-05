<!-- 691f9024-aa4c-4be7-ad39-577a66f65f78 af9d45bd-297c-4f96-aa67-65985ba8bab3 -->
# PageBuilder Header & Footer Integration Plan

## Overview

إضافة دعم PageBuilder لبناء Header و Footer بشكل ديناميكي، مع إمكانية الرجوع لملفات Blade الحالية للحفاظ على التوافق مع الثيمات الموجودة.

## Current Structure Analysis

### Header System:

- File: `core/resources/views/tenant/frontend/partials/navbar.blade.php`
- Gets navbar filename from `theme.json` → `headerHook.navbarArea`
- Includes: `themes.{theme}.headerNavbarArea.{navbar_name}.blade.php`
- Example: `themes.salons.headerNavbarArea.navbar.blade.php`

### Footer System:

- File: `core/resources/views/tenant/frontend/partials/widget-area.blade.php`
- Gets footer filename from `theme.json` → `footerHook.widgetArea`
- Includes: `themes.{theme}.footerWidgetArea.{footer_name}.blade.php`
- Example: `themes.salons.footerWidgetArea.footer-salons.blade.php`

### PageBuilder System:

- Uses `addon_location` field to store widget location
- Method: `render_frontend_pagebuilder_content_by_location($location)`
- Locations are dynamic (e.g., "homepage", "about", etc.)

## Implementation Strategy

### Phase 1: Helper Functions

Create helper functions to check and render PageBuilder content for Header/Footer:

**File: `core/app/Helpers/ThemeMetaData.php`**

- Add `hasPageBuilderHeader()` - Check if PageBuilder content exists for header
- Add `hasPageBuilderFooter()` - Check if PageBuilder content exists for footer
- Add `renderPageBuilderHeader()` - Render PageBuilder header content
- Add `renderPageBuilderFooter()` - Render PageBuilder footer content

### Phase 2: Update Navbar Template

Modify navbar.blade.php to check PageBuilder first, then fallback to Blade:

**File: `core/resources/views/tenant/frontend/partials/navbar.blade.php`**

```blade
@php
    // Check if PageBuilder header exists
    $pagebuilder_header = \App\Facades\ThemeDataFacade::renderPageBuilderHeader();
@endphp

@if(!empty($pagebuilder_header))
    {{-- Render PageBuilder Header --}}
    {!! $pagebuilder_header !!}
@else
    {{-- Fallback to Blade template --}}
    @php
        $current_theme_slug = getSelectedThemeSlug();
        $navbar_area_name = getHeaderNavbarArea();
        $navbar_view = 'themes.'.$current_theme_slug.'.headerNavbarArea.'.$navbar_area_name;
    @endphp
    
    @if(View::exists($navbar_view))
        @include($navbar_view)
    @else
        @include('tenant.frontend.partials.pages-portion.navbars.navbar-01')
    @endif
@endif
```

### Phase 3: Update Footer Template

Modify widget-area.blade.php to check PageBuilder first:

**File: `core/resources/views/tenant/frontend/partials/widget-area.blade.php`**

```blade
@php
    // Check if PageBuilder footer exists
    $pagebuilder_footer = \App\Facades\ThemeDataFacade::renderPageBuilderFooter();
@endphp

@if(!empty($pagebuilder_footer))
    {{-- Render PageBuilder Footer --}}
    {!! $pagebuilder_footer !!}
@else
    {{-- Fallback to Blade template --}}
    @php
        $current_theme_slug = getSelectedThemeSlug();
        $widget_area_name = getFooterWidgetArea();
        $footer_view = 'themes.'.$current_theme_slug.'.footerWidgetArea.'.$widget_area_name;
    @endphp
    
    @if(View::exists($footer_view))
        @include($footer_view)
    @else
        @include('tenant.frontend.partials.pages-portion.footers.footer-medicom')
    @endif
@endif
```

### Phase 4: Add Helper Methods

Implement helper methods in ThemeMetaData:

**File: `core/app/Helpers/ThemeMetaData.php`**

Add methods:

1. `hasPageBuilderHeader()` - Check if widgets exist for location "header"
2. `hasPageBuilderFooter()` - Check if widgets exist for location "footer"
3. `renderPageBuilderHeader()` - Render all widgets with location "header"
4. `renderPageBuilderFooter()` - Render all widgets with location "footer"

### Phase 5: Admin Panel Integration

Add Header/Footer builder pages in admin panel:

**Option A: Separate Pages**

- Route: `/admin/appearance/header-builder`
- Route: `/admin/appearance/footer-builder`
- Controller: `HeaderBuilderController`, `FooterBuilderController`
- Views: Use existing PageBuilder editor with location preset

**Option B: Settings Toggle**

- Add toggle in Theme Settings: "Use PageBuilder for Header/Footer"
- If enabled, show PageBuilder editor in settings
- If disabled, use Blade templates

**Recommended: Option A (Separate Pages)**

### Phase 6: PageBuilder Locations

Add special locations:

- `header` - For header widgets
- `footer` - For footer widgets

These locations will be available in PageBuilder dropdown when editing Header/Footer.

## Files to Modify

### 1. Core Helper

- `core/app/Helpers/ThemeMetaData.php`
  - Add 4 new methods for PageBuilder Header/Footer

### 2. Frontend Templates

- `core/resources/views/tenant/frontend/partials/navbar.blade.php`
  - Add PageBuilder check before Blade include

- `core/resources/views/tenant/frontend/partials/widget-area.blade.php`
  - Add PageBuilder check before Blade include

### 3. Admin Panel (Optional but Recommended)

- Create `core/app/Http/Controllers/Admin/HeaderBuilderController.php`
- Create `core/app/Http/Controllers/Admin/FooterBuilderController.php`
- Create routes in `routes/web.php` or `routes/admin.php`
- Create views: `resources/views/admin/header-builder.blade.php`
- Create views: `resources/views/admin/footer-builder.blade.php`

### 4. Facade Update

- Update `ThemeDataFacade` if needed to expose new methods

## Technical Details

### Location Naming Convention

- Header widgets: `location = 'header'`
- Footer widgets: `location = 'footer'`

### Widget Order

- Use `addon_order` field to control widget sequence
- Header widgets: Top to bottom order
- Footer widgets: Top to bottom order

### Backward Compatibility

- System will check PageBuilder content first
- If no PageBuilder content exists, fallback to Blade templates
- Existing themes continue working without changes
- New themes can use PageBuilder or Blade or both

### Performance Considerations

- Cache PageBuilder Header/Footer content
- Use `Cache::remember()` for 24 hours
- Clear cache when Header/Footer widgets are updated

## Implementation Order

1. **Phase 1**: Add helper methods to ThemeMetaData (4 methods)
2. **Phase 2**: Update ThemeDataFacade annotations
3. **Phase 3**: Update navbar.blade.php template (check PageBuilder first)
4. **Phase 4**: Update widget-area.blade.php template (check PageBuilder first)
5. **Phase 5**: Test backward compatibility (existing themes should still work)
6. **Phase 6**: Create HeaderBuilderController and routes
7. **Phase 7**: Create FooterBuilderController and routes
8. **Phase 8**: Create admin views (header-builder.blade.php, footer-builder.blade.php)
9. **Phase 9**: Test full flow (build header/footer in admin, verify frontend rendering)

## Testing Checklist

- [ ] PageBuilder header renders correctly
- [ ] PageBuilder footer renders correctly
- [ ] Fallback to Blade works when no PageBuilder content
- [ ] Widget order is respected
- [ ] All existing themes still work
- [ ] Admin panel pages work correctly
- [ ] Cache works properly
- [ ] Mobile responsive

## Risk Assessment

**Risk Level: LOW-MODERATE**

**Potential Issues:**

1. Performance: Multiple widgets in Header/Footer may slow down page load
2. Caching: Need proper cache invalidation
3. CSS Conflicts: PageBuilder widgets may conflict with theme CSS
4. JavaScript: Widgets may need specific JS loading order

**Mitigation:**

- Use lazy loading for heavy widgets
- Implement proper cache invalidation
- Add CSS isolation for PageBuilder widgets
- Document JS loading order requirements

## Success Criteria

1. ✅ Header can be built using PageBuilder
2. ✅ Footer can be built using PageBuilder
3. ✅ Existing themes still work (backward compatibility)
4. ✅ Admin panel allows editing Header/Footer
5. ✅ Widgets render in correct order
6. ✅ Performance is acceptable

## Next Steps

After implementation:

1. Create documentation for using PageBuilder Header/Footer
2. Add example Header/Footer templates
3. Create video tutorial (optional)
4. Update theme.json schema documentation

### To-dos

- [x] Copy aromatic theme directory structure to salons theme directory