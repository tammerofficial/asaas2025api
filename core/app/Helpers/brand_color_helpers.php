<?php

use App\Helpers\BrandColorHelper;

if (!function_exists('brand_color')) {
    /**
     * 🎨 Get brand color by key
     * 
     * استخدام سريع للألوان في Blade Templates
     * Quick access to brand colors in Blade templates
     * 
     * @param string $key Color key (e.g., 'primary', 'primary.light', 'success.base')
     * @param string $default Default color if not found
     * @return string Color hex code
     * 
     * Usage Examples:
     * - {{ brand_color('primary') }}           // Returns: #7f1625
     * - {{ brand_color('primary.dark') }}      // Returns: #5a0f19
     * - {{ brand_color('success') }}           // Returns: #28a745
     * - style="color: {{ brand_color('primary') }}"
     * - style="background-color: {{ brand_color('primary.light') }}"
     */
    function brand_color(string $key, string $default = '#000000'): string
    {
        // Handle simple keys (no dot)
        if (!str_contains($key, '.')) {
            // Check if it's a method name
            if (method_exists(BrandColorHelper::class, $key)) {
                return BrandColorHelper::$key();
            }
            
            // Default to primary colors
            return BrandColorHelper::primary($key);
        }
        
        // Handle dot notation (e.g., 'primary.light')
        return BrandColorHelper::get($key, $default);
    }
}

if (!function_exists('brand_primary')) {
    /**
     * Get primary brand color
     * 
     * @param string $shade Shade variation (base, dark, darker, light, lighter, pale, hover)
     * @return string
     */
    function brand_primary(string $shade = 'base'): string
    {
        return BrandColorHelper::primary($shade);
    }
}

if (!function_exists('brand_rgba')) {
    /**
     * Get primary color with opacity
     * 
     * @param string $opacity Opacity level (20, 30, 50, 70, 90)
     * @return string
     */
    function brand_rgba(string $opacity = '50'): string
    {
        return BrandColorHelper::rgba($opacity);
    }
}

if (!function_exists('brand_success')) {
    /**
     * Get success color
     */
    function brand_success(string $shade = 'base'): string
    {
        return BrandColorHelper::success($shade);
    }
}

if (!function_exists('brand_warning')) {
    /**
     * Get warning color
     */
    function brand_warning(string $shade = 'base'): string
    {
        return BrandColorHelper::warning($shade);
    }
}

if (!function_exists('brand_danger')) {
    /**
     * Get danger color
     */
    function brand_danger(string $shade = 'base'): string
    {
        return BrandColorHelper::danger($shade);
    }
}

if (!function_exists('brand_info')) {
    /**
     * Get info color
     */
    function brand_info(string $shade = 'base'): string
    {
        return BrandColorHelper::info($shade);
    }
}

if (!function_exists('table_header_style')) {
    /**
     * 📊 Get standard table header style with brand color
     * 
     * الحصول على ستايل موحد لرؤوس الجداول
     * Get unified style for table headers
     * 
     * @return string Inline CSS style
     * 
     * Usage in Blade:
     * <thead class="text-white" style="{{ table_header_style() }}">
     */
    function table_header_style(): string
    {
        return 'background-color: ' . brand_primary();
    }
}

if (!function_exists('table_header_class')) {
    /**
     * Get standard table header classes
     * 
     * @return string CSS classes
     */
    function table_header_class(): string
    {
        return 'text-white bg-brand-primary';
    }
}

if (!function_exists('brand_hover')) {
    /**
     * 🎯 Get brand hover color (for button hover states)
     * 
     * الحصول على لون الـ hover للأزرار
     * Get hover color for buttons
     * 
     * @return string Hover color hex code
     * 
     * Usage:
     * .btn:hover { background-color: {{ brand_hover() }}; }
     */
    function brand_hover(): string
    {
        return BrandColorHelper::primaryHover();
    }
}

