<?php

namespace App\Helpers;

/**
 * 🎨 Brand Color Helper
 * 
 * مساعد الألوان - للوصول السريع لألوان البراند في أي مكان بالنظام
 * Brand Color Helper - Quick access to brand colors throughout the system
 * 
 * Usage Examples:
 * - In PHP: BrandColorHelper::primary()
 * - In Blade: {{ brand_color('primary') }}
 * - CSS Class: <div class="bg-brand-primary">
 */
class BrandColorHelper
{
    /**
     * Get brand colors configuration
     */
    protected static function config(): array
    {
        return config('brand-colors', []);
    }
    
    /**
     * 🎯 Primary Brand Colors
     */
    public static function primary(string $shade = 'base'): string
    {
        return self::config()['primary'][$shade] ?? '#7f1625';
    }
    
    /**
     * Get primary color variations
     */
    public static function primaryBase(): string
    {
        return self::primary('base'); // #7f1625
    }
    
    public static function primaryDark(): string
    {
        return self::primary('dark'); // #5a0f19
    }
    
    public static function primaryDarker(): string
    {
        return self::primary('darker'); // #3d0a11
    }
    
    public static function primaryLight(): string
    {
        return self::primary('light'); // #a01d2f
    }
    
    public static function primaryLighter(): string
    {
        return self::primary('lighter'); // #c5253d
    }
    
    public static function primaryPale(): string
    {
        return self::primary('pale'); // #e6394f
    }
    
    public static function primaryHover(): string
    {
        return self::primary('hover'); // #5a0f19
    }
    
    /**
     * 🎨 RGBA Variations
     */
    public static function rgba(string $opacity = '50'): string
    {
        $key = "primary_{$opacity}";
        return self::config()['rgba'][$key] ?? "rgba(127, 22, 37, 0.5)";
    }
    
    public static function rgba20(): string
    {
        return self::rgba('20');
    }
    
    public static function rgba30(): string
    {
        return self::rgba('30');
    }
    
    public static function rgba50(): string
    {
        return self::rgba('50');
    }
    
    public static function rgba70(): string
    {
        return self::rgba('70');
    }
    
    public static function rgba90(): string
    {
        return self::rgba('90');
    }
    
    /**
     * ✅ Success Colors
     */
    public static function success(string $shade = 'base'): string
    {
        return self::config()['success'][$shade] ?? '#28a745';
    }
    
    /**
     * ⚠️ Warning Colors
     */
    public static function warning(string $shade = 'base'): string
    {
        return self::config()['warning'][$shade] ?? '#ffc107';
    }
    
    /**
     * ❌ Danger Colors
     */
    public static function danger(string $shade = 'base'): string
    {
        return self::config()['danger'][$shade] ?? '#dc3545';
    }
    
    /**
     * ℹ️ Info Colors
     */
    public static function info(string $shade = 'base'): string
    {
        return self::config()['info'][$shade] ?? '#17a2b8';
    }
    
    /**
     * ⚪ Neutral Colors
     */
    public static function neutral(string $shade): string
    {
        return self::config()['neutral'][$shade] ?? '#ffffff';
    }
    
    public static function white(): string
    {
        return self::neutral('white');
    }
    
    public static function black(): string
    {
        return self::neutral('black');
    }
    
    public static function gray(int $level = 500): string
    {
        $key = "gray_{$level}";
        return self::neutral($key);
    }
    
    /**
     * 🎭 UI Element Colors
     */
    public static function ui(string $element): string
    {
        return self::config()['ui'][$element] ?? '#ffffff';
    }
    
    public static function background(): string
    {
        return self::ui('background');
    }
    
    public static function backgroundDark(): string
    {
        return self::ui('background_dark');
    }
    
    public static function border(): string
    {
        return self::ui('border');
    }
    
    public static function borderLight(): string
    {
        return self::ui('border_light');
    }
    
    public static function textPrimary(): string
    {
        return self::ui('text_primary');
    }
    
    public static function textSecondary(): string
    {
        return self::ui('text_secondary');
    }
    
    public static function textMuted(): string
    {
        return self::ui('text_muted');
    }
    
    public static function link(): string
    {
        return self::ui('link');
    }
    
    public static function linkHover(): string
    {
        return self::ui('link_hover');
    }
    
    /**
     * Get color by key path (dot notation)
     * 
     * Examples:
     * - get('primary.base')
     * - get('success.light')
     * - get('neutral.gray_500')
     */
    public static function get(string $path, string $default = '#000000'): string
    {
        $keys = explode('.', $path);
        $config = self::config();
        
        foreach ($keys as $key) {
            if (isset($config[$key])) {
                $config = $config[$key];
            } else {
                return $default;
            }
        }
        
        return is_string($config) ? $config : $default;
    }
    
    /**
     * Get all colors as array
     */
    public static function all(): array
    {
        return self::config();
    }
    
    /**
     * Generate CSS variables output
     */
    public static function toCssVariables(): string
    {
        $config = self::config();
        $css = ":root {\n";
        
        // Primary colors
        foreach ($config['primary'] ?? [] as $shade => $color) {
            $css .= "    --brand-primary-{$shade}: {$color};\n";
        }
        
        // RGBA variations
        foreach ($config['rgba'] ?? [] as $key => $color) {
            $css .= "    --brand-{$key}: {$color};\n";
        }
        
        $css .= "}\n";
        
        return $css;
    }
}

