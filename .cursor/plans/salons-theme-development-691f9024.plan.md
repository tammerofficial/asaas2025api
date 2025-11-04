<!-- 691f9024-aa4c-4be7-ad39-577a66f65f78 af9d45bd-297c-4f96-aa67-65985ba8bab3 -->
# PageBuilder Additional Common Addons Plan

## Overview

Add 12 essential PageBuilder addons to the existing Common addons collection. These addons are selected from the extensive list provided, focusing on the most useful and safe to implement features that won't cause issues in the project.

## Selected Priority Addons

From the extensive list, we've selected **12 essential addons** that are:

- Most useful for website owners
- Safe to implement (no breaking changes)
- Commonly needed across different industries
- Easy to maintain

### Priority Order

1. **Counter** - Statistics and achievements (essential)
2. **ProgressBar** - Skills and progress visualization (very useful)
3. **Countdown** - Marketing and sales deadlines (high value)
4. **Accordion** - FAQ and content organization (very common)
5. **Divider** - Visual separation (layout essential)
6. **Spacer** - Layout spacing (layout essential)
7. **Gallery** - Advanced image gallery (high value)
8. **GoogleMaps** - Location display (essential for businesses)
9. **ImageComparison** - Before/after slider (perfect for salons, clinics, etc.)
10. **ModalBox** - Popup content (very useful)
11. **SocialIcons** - Social media links (essential)
12. **Newsletter** - Email marketing (high value)

### Additional Useful Addons from Elementor List

From the extensive Elementor widgets list, we've identified additional useful addons that are safe and commonly needed:

13. **FlipBox** - Flip card effect (very engaging)
14. **HotSpots** - Interactive hotspots on images (great for product tours)
15. **Typewriter** - Animated typing effect (eye-catching)
16. **Table** - Data table widget (essential for displaying structured data)
17. **Breadcrumb** - Navigation breadcrumbs (SEO and UX friendly)
18. **Alert** - Alert/notification messages (important for user communication)

## Directory Structure

### New Addon Classes (PHP Files)

```
core/plugins/PageBuilder/Addons/Common/
├── Counter/
│   └── Counter.php
├── ProgressBar/
│   └── ProgressBar.php
├── Countdown/
│   └── Countdown.php
├── Accordion/
│   └── Accordion.php
├── Divider/
│   └── Divider.php
├── Spacer/
│   └── Spacer.php
├── Gallery/
│   └── Gallery.php
├── GoogleMaps/
│   └── GoogleMaps.php
├── ImageComparison/
│   └── ImageComparison.php
├── ModalBox/
│   └── ModalBox.php
├── SocialIcons/
│   └── SocialIcons.php
└── Newsletter/
    └── Newsletter.php
```

### New View Files (Blade Templates)

```
core/plugins/PageBuilder/views/common/
├── counter.blade.php
├── progress-bar.blade.php
├── countdown.blade.php
├── accordion.blade.php
├── divider.blade.php
├── spacer.blade.php
├── gallery.blade.php
├── google-maps.blade.php
├── image-comparison.blade.php
├── modal-box.blade.php
├── social-icons.blade.php
└── newsletter.blade.php
```

## Addon Specifications

### 1. Counter / Number Counter

**Fields:**

- Number (target value)
- Prefix (e.g., "$", "+")
- Suffix (e.g., "+", "K", "M")
- Icon (optional)
- Title
- Description
- Animation Speed (ms)
- Starting Number

**View:** Animated counter that counts up to target number

**Use Case:** Statistics, achievements, milestones (e.g., "1000+ Clients", "50 Services")

### 2. Progress Bar

**Fields:**

- Repeater for progress items:
  - Label
  - Percentage (0-100)
  - Color
  - Animated (yes/no)
- Style (horizontal/vertical/circular)
- Show Percentage Text

**View:** Progress bars showing skills, completion, or statistics

**Use Case:** Skills section, project progress, feature completion

### 3. Countdown Timer

**Fields:**

- Target Date/Time
- Label (e.g., "Sale Ends In", "Event Starts In")
- Format (Days/Hours/Minutes/Seconds)
- Style (default/compact/minimal)
- Timezone

**View:** Countdown timer to specific date

**Use Case:** Sales, events, launches, deadlines

### 4. Accordion

**Fields:**

- Repeater for accordion items:
  - Title
  - Content (WYSIWYG)
  - Icon (optional)
  - Default Open (yes/no)
- Style (default/boxed/minimal)
- Allow Multiple Open

**View:** Collapsible accordion sections

**Use Case:** FAQ, features list, content organization

### 5. Divider / Separator

**Fields:**

- Style (solid/dashed/dotted/double/wavy)
- Width (px/%)
- Color
- Height/Thickness (px)
- Alignment (left/center/right)
- Margin Top/Bottom

**View:** Visual separator between sections

**Use Case:** Section breaks, visual separation

### 6. Spacer

**Fields:**

- Height (px/rem/vh)
- Visibility (all/desktop/mobile)
- Background Color (optional)
- Responsive Heights (desktop/tablet/mobile)

**View:** Empty space/spacing element

**Use Case:** Layout spacing, vertical gaps

### 7. Gallery

**Fields:**

- Repeater for gallery items:
  - Image
  - Title (optional)
  - Description (optional)
  - Link (optional)
- Layout (grid/masonry/carousel)
- Columns (1-6)
- Lightbox (yes/no)
- Image Size
- Spacing

**View:** Advanced image gallery with lightbox

**Use Case:** Portfolio, before/after, product images

### 8. Google Maps

**Fields:**

- Address
- Latitude
- Longitude
- Zoom Level (1-20)
- Map Type (roadmap/satellite/hybrid/terrain)
- Marker (custom image)
- Height (px)
- Show Info Window

**View:** Embedded Google Maps

**Use Case:** Location display, contact page, directions

### 9. Image Comparison

**Fields:**

- Before Image
- After Image
- Slider Position (0-100%)
- Orientation (horizontal/vertical)
- Label Before/After
- Show Labels

**View:** Before/after image comparison slider

**Use Case:** Transformations, product comparisons, before/after results (perfect for salons!)

### 10. Modal Box

**Fields:**

- Trigger Type (button/image/custom)
- Trigger Text/Image
- Modal Title
- Modal Content (WYSIWYG)
- Size (small/medium/large/fullscreen)
- Show Close Button
- Close on Outside Click

**View:** Popup modal box

**Use Case:** Video popups, forms, detailed information

### 11. Social Icons

**Fields:**

- Repeater for social platforms:
  - Platform (Facebook/Instagram/Twitter/etc.)
  - Icon (custom or preset)
  - URL
- Style (rounded/square/circle)
- Size (small/medium/large)
- Alignment (left/center/right)
- Spacing

**View:** Social media icons with links

**Use Case:** Social links, footer, header

### 12. Newsletter

**Fields:**

- Title
- Description
- Placeholder Text
- Button Text
- Success Message
- Error Message
- API Integration (Mailchimp/Email/None)
- API Key (optional)

**View:** Newsletter subscription form

**Use Case:** Email list building, marketing

## Implementation Steps

### 1. Create Addon Classes

Each addon will follow the same pattern as existing addons:

- Extend `PageBuilderBase`
- Implement `preview_image()`, `admin_render()`, `frontend_render()`, `enable()`, `addon_title()`
- Use PageBuilder Fields (Text, Image, Repeater, Select, etc.)
- Support padding/spacing via `padding_fields()`
- Use `section_id_and_class_fields()` for custom CSS
- Sanitize all output with `SanitizeInput`

### 2. Create View Files

- Location: `core/plugins/PageBuilder/views/common/`
- Use `renderView('common.{addon-name}', $data)` method
- Views should be responsive
- Use existing CSS/TailwindCSS classes

### 3. Register Addons

Update `core/plugins/PageBuilder/PageBuilderSetup.php`:

Add to `$globalAddons` array (around line 215-235):

```php
// Additional essential addons
\Plugins\PageBuilder\Addons\Common\Counter\Counter::class,
\Plugins\PageBuilder\Addons\Common\ProgressBar\ProgressBar::class,
\Plugins\PageBuilder\Addons\Common\Countdown\Countdown::class,
\Plugins\PageBuilder\Addons\Common\Accordion\Accordion::class,
\Plugins\PageBuilder\Addons\Common\Divider\Divider::class,
\Plugins\PageBuilder\Addons\Common\Spacer\Spacer::class,
\Plugins\PageBuilder\Addons\Common\Gallery\Gallery::class,
\Plugins\PageBuilder\Addons\Common\GoogleMaps\GoogleMaps::class,
\Plugins\PageBuilder\Addons\Common\ImageComparison\ImageComparison::class,
\Plugins\PageBuilder\Addons\Common\ModalBox\ModalBox::class,
\Plugins\PageBuilder\Addons\Common\SocialIcons\SocialIcons::class,
\Plugins\PageBuilder\Addons\Common\Newsletter\Newsletter::class,
```

### 4. Create Preview Images

- Location: `core/public/assets/pagebuilder-previews/common/`
- Files: counter.jpg, progress-bar.jpg, countdown.jpg, etc.
- Each preview should show the addon's design

## Files to Create

### PHP Classes (12 files)

1. `core/plugins/PageBuilder/Addons/Common/Counter/Counter.php`
2. `core/plugins/PageBuilder/Addons/Common/ProgressBar/ProgressBar.php`
3. `core/plugins/PageBuilder/Addons/Common/Countdown/Countdown.php`
4. `core/plugins/PageBuilder/Addons/Common/Accordion/Accordion.php`
5. `core/plugins/PageBuilder/Addons/Common/Divider/Divider.php`
6. `core/plugins/PageBuilder/Addons/Common/Spacer/Spacer.php`
7. `core/plugins/PageBuilder/Addons/Common/Gallery/Gallery.php`
8. `core/plugins/PageBuilder/Addons/Common/GoogleMaps/GoogleMaps.php`
9. `core/plugins/PageBuilder/Addons/Common/ImageComparison/ImageComparison.php`
10. `core/plugins/PageBuilder/Addons/Common/ModalBox/ModalBox.php`
11. `core/plugins/PageBuilder/Addons/Common/SocialIcons/SocialIcons.php`
12. `core/plugins/PageBuilder/Addons/Common/Newsletter/Newsletter.php`

### View Files (12 files)

1. `core/plugins/PageBuilder/views/common/counter.blade.php`
2. `core/plugins/PageBuilder/views/common/progress-bar.blade.php`
3. `core/plugins/PageBuilder/views/common/countdown.blade.php`
4. `core/plugins/PageBuilder/views/common/accordion.blade.php`
5. `core/plugins/PageBuilder/views/common/divider.blade.php`
6. `core/plugins/PageBuilder/views/common/spacer.blade.php`
7. `core/plugins/PageBuilder/views/common/gallery.blade.php`
8. `core/plugins/PageBuilder/views/common/google-maps.blade.php`
9. `core/plugins/PageBuilder/views/common/image-comparison.blade.php`
10. `core/plugins/PageBuilder/views/common/modal-box.blade.php`
11. `core/plugins/PageBuilder/views/common/social-icons.blade.php`
12. `core/plugins/PageBuilder/views/common/newsletter.blade.php`

### Configuration Update

1. `core/plugins/PageBuilder/PageBuilderSetup.php` - Add 12 new addons to global registration

## Safety & Risk Analysis

### Risk Level: LOW

**Why Safe:**

- Following exact same patterns as existing addons
- Each addon uses `class_exists()` check
- `enable()` method controls visibility
- If addon has errors, it simply won't appear in widget list
- Adding to `globalAddons` array only (non-breaking)
- Existing try-catch blocks in frontend rendering already handle errors gracefully

### Safety Measures

1. **Implementation Strategy:**

   - Add addons one by one
   - Test each addon individually before adding to array
   - Use proper error handling in each addon class
   - Follow existing addon patterns exactly

2. **Code Safety:**

   - All output sanitized with `SanitizeInput`
   - Use existing PageBuilder field types
   - Return empty string on error (graceful degradation)
   - Log errors instead of breaking page

3. **Rollback Plan:**

   - Quick Fix: Remove new addons from `globalAddons` array
   - Full Rollback: Restore from backup

## Excluded Addons (Intentionally)

- **WooCommerce-specific addons** - Not applicable (we use custom products)
- **Blog-specific addons** - Can use existing blog widgets
- **Complex animations** - May cause performance issues
- **Third-party integrations** - Need API keys and external dependencies
- **Header/Footer specific** - Already handled separately

## Technical Notes

- All addons extend `PageBuilderBase`
- Use `SanitizeInput` for all output
- Support multilingual content via `admin_language_tab()` if needed
- Include padding/spacing options via `padding_fields()`
- Use `section_id_and_class_fields()` for custom CSS
- Views should be mobile-responsive
- Follow existing PageBuilder patterns for consistency
- Use `renderView()` method for frontend rendering

## Conclusion

Safe to proceed - These addons are additive and won't break existing functionality. They follow the same safe patterns as existing Common addons and will be extremely useful for website owners across all industries.

## Additional Priority Addons from Elementor List

After analyzing the extensive Elementor widgets list, we've identified **6 additional useful addons** that are safe and commonly needed:

### Additional Addons (Priority Order)

13. **FlipBox** - Flip card effect (very engaging for features/services)
14. **HotSpots** - Interactive hotspots on images (great for product tours, team photos)
15. **Typewriter** - Animated typing effect (eye-catching for headlines)
16. **Table** - Data table widget (essential for pricing, schedules, comparisons)
17. **Breadcrumb** - Navigation breadcrumbs (SEO and UX friendly)
18. **Alert** - Alert/notification messages (important for user communication)

### Additional Addon Specifications

#### 13. FlipBox / Flip Card

**Fields:**

- Front Side:
  - Icon/Image
  - Title
  - Description
- Back Side:
  - Title
  - Description
  - Button (optional)
- Flip Direction (horizontal/vertical)
- Flip Trigger (hover/click)
- Style (default/3d/minimal)

**View:** Card that flips to reveal back content

**Use Case:** Feature showcases, service cards, team member cards

#### 14. HotSpots / Interactive Hotspots

**Fields:**

- Background Image
- Repeater for hotspots:
  - X Position (%)
  - Y Position (%)
  - Tooltip Title
  - Tooltip Content
  - Icon (optional)
  - Link (optional)
- Tooltip Style (default/popup/always visible)

**View:** Interactive image with clickable hotspots showing tooltips

**Use Case:** Product tours, interactive maps, team photos with info

#### 15. Typewriter / Animated Text

**Fields:**

- Static Text (before)
- Repeater for animated words/phrases
- Typing Speed (ms per character)
- Deleting Speed (ms per character)
- Cursor Style (pipe/underscore/custom)
- Loop (yes/no)
- Show Cursor

**View:** Text that types out character by character

**Use Case:** Hero headlines, attention-grabbing text

#### 16. Table

**Fields:**

- Repeater for rows:
  - Repeater for cells (text/content)
- Header Row (yes/no)
- Striped Rows (yes/no)
- Border Style
- Responsive (scroll/stack)

**View:** Data table with rows and columns

**Use Case:** Pricing tables, schedules, data comparison, feature matrices

#### 17. Breadcrumb

**Fields:**

- Separator (/, >, |, custom)
- Home Text
- Show Home (yes/no)
- Style (default/minimal/arrows)
- Alignment

**View:** Navigation breadcrumb trail

**Use Case:** Page navigation, SEO, user orientation

#### 18. Alert / Notification

**Fields:**

- Type (success/error/warning/info)
- Title
- Message/Content
- Icon (yes/no/custom)
- Dismissible (yes/no)
- Style (default/filled/outlined)

**View:** Alert/notification message box

**Use Case:** Important notices, success messages, warnings

## Extended Risk Analysis

### Comprehensive Safety Assessment

#### 1. Code Safety (CRITICAL)

**Existing Safety Mechanisms:**

- ✅ `class_exists()` check before instantiation
- ✅ Try-catch blocks in `render_widgets_by_name_for_frontend()`
- ✅ `enable()` method controls visibility
- ✅ Graceful degradation (returns empty string on error)
- ✅ Error logging instead of breaking page

**New Addons Safety:**

- All new addons MUST follow exact same pattern
- Use `SanitizeInput` for ALL output (no exceptions)
- No direct database queries (use existing helpers)
- No file system operations (use existing media helpers)
- No external API calls without try-catch
- No JavaScript inline (use external JS files or data attributes)

#### 2. Performance Impact (MODERATE)

**Potential Risks:**

- JavaScript libraries (Google Maps, Typewriter, etc.)
- Heavy animations (FlipBox, Typewriter)
- Multiple addons on same page

**Mitigation:**

- Lazy load JavaScript (only load when addon is present)
- Use CSS animations where possible (better performance)
- Limit animation complexity
- Add loading states for async content (Google Maps)

#### 3. Dependency Management (LOW-MODERATE)

**External Dependencies:**

- Google Maps API (requires API key)
- Animation libraries (if needed)

**Mitigation:**

- Make API keys optional (show placeholder if not configured)
- Use native browser APIs where possible
- Bundle lightweight libraries (avoid heavy dependencies)
- Document all dependencies clearly

#### 4. Breaking Changes (LOW)

**Why Safe:**

- Addons are additive (don't modify existing code)
- Each addon is independent
- No changes to core PageBuilder logic
- Can be disabled individually if issues arise

**Rollback Strategy:**

1. Remove addon from `globalAddons` array (instant)
2. Delete addon class file (if needed)
3. Remove view file (if needed)
4. Clear cache

#### 5. Security Risks (LOW)

**Potential Concerns:**

- User input sanitization
- XSS vulnerabilities
- API key exposure

**Mitigation:**

- ✅ All output sanitized with `SanitizeInput`
- ✅ No eval() or dangerous functions
- ✅ API keys stored in settings (not in frontend)
- ✅ Validate all user inputs
- ✅ Escape all HTML output

### Implementation Safety Checklist

For each new addon:

- [ ] Extends `PageBuilderBase` (not custom base class)
- [ ] Implements all required methods (admin_render, frontend_render, enable, addon_title, preview_image)
- [ ] Uses existing PageBuilder Fields (Text, Image, Repeater, Select, etc.)
- [ ] All output sanitized with `SanitizeInput`
- [ ] No direct database queries
- [ ] No file system operations
- [ ] Error handling in frontend_render (try-catch)
- [ ] Returns empty string on error (graceful degradation)
- [ ] Mobile responsive
- [ ] Follows existing naming conventions
- [ ] Uses `renderView()` for frontend rendering
- [ ] Includes padding/spacing options
- [ ] Supports custom CSS classes

### Testing Strategy

#### Phase 1: Individual Testing

1. Create each addon one by one
2. Test admin panel (rendering, saving)
3. Test frontend rendering
4. Test error scenarios (missing data, invalid input)
5. Test on mobile devices

#### Phase 2: Integration Testing

1. Add to `globalAddons` array
2. Test in PageBuilder editor
3. Test with existing addons
4. Test with different themes
5. Test performance (page load time)

#### Phase 3: User Acceptance

1. Test with real content
2. Test edge cases
3. Monitor error logs
4. Gather user feedback

### Rollback Procedures

#### Quick Rollback (5 minutes)

1. Comment out addon in `PageBuilderSetup.php` globalAddons array
2. Clear cache
3. Test page loads correctly

#### Full Rollback (if needed)

1. Remove addon from `globalAddons` array
2. Delete addon class file
3. Delete view file
4. Clear all caches
5. Verify no broken pages

### Documentation Requirements

For each addon:

- [ ] Clear description in `addon_title()`
- [ ] Field labels are descriptive
- [ ] Preview image shows functionality
- [ ] View file is well-commented
- [ ] No hardcoded values (use translations)

## Final Risk Assessment

### Overall Risk Level: **LOW** ✅

**Justification:**

1. Following proven patterns (same as existing addons)
2. Additive changes only (no modifications to core)
3. Multiple safety mechanisms in place
4. Easy rollback if issues arise
5. Independent addons (one failure doesn't break others)

### Recommended Implementation Order

**Phase 1 (Safest - No External Dependencies):**

1. Divider
2. Spacer
3. Accordion
4. Table
5. Breadcrumb
6. Alert

**Phase 2 (Moderate - Light JavaScript):**

7. Counter
8. ProgressBar
9. FlipBox
10. Typewriter

**Phase 3 (Higher Complexity):**

11. Countdown
12. Gallery
13. ImageComparison
14. ModalBox
15. HotSpots

**Phase 4 (External Dependencies):**

16. GoogleMaps (requires API key)
17. Newsletter (may require API)
18. SocialIcons

### Conclusion

✅ **Safe to Proceed** - With proper implementation following existing patterns, all addons can be added safely without breaking core functionality. The risk is LOW because:

- Addons are independent and isolated
- Multiple safety mechanisms exist
- Easy rollback is available
- No core modifications required

### To-dos

- [ ] Copy aromatic theme directory structure to salons theme directory