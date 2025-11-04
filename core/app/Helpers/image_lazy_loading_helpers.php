<?php

/**
 * Image Lazy Loading Helpers
 * 
 * Helpers to implement lazy loading for images
 * to improve page load performance
 */

if (!function_exists('lazy_image')) {
    /**
     * Generate lazy-loaded image tag
     *
     * @param string $src Image source URL
     * @param string $alt Alt text
     * @param array $attributes Additional attributes
     * @return string HTML image tag with lazy loading
     */
    function lazy_image(string $src, string $alt = '', array $attributes = []): string
    {
        $attrs = [];
        
        // Add loading="lazy" for native lazy loading
        $attrs[] = 'loading="lazy"';
        
        // Add data-src for fallback/custom lazy load
        $attrs[] = 'data-src="' . e($src) . '"';
        
        // Use placeholder as src
        $placeholder = $attributes['placeholder'] ?? 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 300 200\'%3E%3Crect fill=\'%23f0f0f0\' width=\'300\' height=\'200\'/%3E%3Ctext fill=\'%23999\' x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\'%3ELoading...%3C/text%3E%3C/svg%3E';
        $attrs[] = 'src="' . $placeholder . '"';
        
        // Add alt text
        if ($alt) {
            $attrs[] = 'alt="' . e($alt) . '"';
        }
        
        // Add class for lazy loading
        $class = isset($attributes['class']) ? $attributes['class'] . ' lazy-load' : 'lazy-load';
        $attrs[] = 'class="' . $class . '"';
        
        // Add other attributes
        unset($attributes['class'], $attributes['placeholder']);
        foreach ($attributes as $key => $value) {
            $attrs[] = e($key) . '="' . e($value) . '"';
        }
        
        return '<img ' . implode(' ', $attrs) . '>';
    }
}

if (!function_exists('lazy_background')) {
    /**
     * Generate element with lazy-loaded background image
     *
     * @param string $src Background image URL
     * @param string $tag HTML tag (default: div)
     * @param array $attributes Additional attributes
     * @return string Opening HTML tag with lazy background
     */
    function lazy_background(string $src, string $tag = 'div', array $attributes = []): string
    {
        $attrs = [];
        
        // Add data-background for lazy loading
        $attrs[] = 'data-background="' . e($src) . '"';
        
        // Add class for lazy loading
        $class = isset($attributes['class']) ? $attributes['class'] . ' lazy-background' : 'lazy-background';
        $attrs[] = 'class="' . $class . '"';
        
        // Add style with placeholder
        $placeholder = $attributes['placeholder-color'] ?? '#f0f0f0';
        $style = isset($attributes['style']) ? $attributes['style'] : '';
        $style .= " background-color: {$placeholder};";
        $attrs[] = 'style="' . trim($style) . '"';
        
        // Add other attributes
        unset($attributes['class'], $attributes['style'], $attributes['placeholder-color']);
        foreach ($attributes as $key => $value) {
            $attrs[] = e($key) . '="' . e($value) . '"';
        }
        
        return '<' . $tag . ' ' . implode(' ', $attrs) . '>';
    }
}

if (!function_exists('responsive_image')) {
    /**
     * Generate responsive image with srcset and sizes
     *
     * @param string $src Base image URL
     * @param array $sizes Image sizes [width => url]
     * @param string $alt Alt text
     * @param array $attributes Additional attributes
     * @return string HTML picture element or img with srcset
     */
    function responsive_image(string $src, array $sizes = [], string $alt = '', array $attributes = []): string
    {
        if (empty($sizes)) {
            return lazy_image($src, $alt, $attributes);
        }
        
        $srcset = [];
        foreach ($sizes as $width => $url) {
            $srcset[] = e($url) . ' ' . $width . 'w';
        }
        
        $attributes['srcset'] = implode(', ', $srcset);
        $attributes['sizes'] = $attributes['sizes'] ?? '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw';
        
        return lazy_image($src, $alt, $attributes);
    }
}

if (!function_exists('image_with_webp')) {
    /**
     * Generate picture element with WebP fallback
     *
     * @param string $src Original image URL
     * @param string $webpSrc WebP image URL
     * @param string $alt Alt text
     * @param array $attributes Additional attributes
     * @return string HTML picture element
     */
    function image_with_webp(string $webpSrc, string $src, string $alt = '', array $attributes = []): string
    {
        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= ' ' . e($key) . '="' . e($value) . '"';
        }
        
        return <<<HTML
        <picture>
            <source srcset="{$webpSrc}" type="image/webp">
            <source srcset="{$src}" type="image/jpeg">
            {$attrString} alt="{$alt}" loading="lazy">
        </picture>
        HTML;
    }
}

if (!function_exists('optimize_image_url')) {
    /**
     * Generate optimized image URL with resize parameters
     *
     * @param string $url Original image URL
     * @param int|null $width Target width
     * @param int|null $height Target height
     * @param string $format Target format (webp, jpg, png)
     * @return string Optimized image URL
     */
    function optimize_image_url(string $url, ?int $width = null, ?int $height = null, string $format = 'webp'): string
    {
        // If using a CDN with image optimization (like Cloudinary, Imgix)
        // you can append query parameters here
        
        $params = [];
        
        if ($width) {
            $params[] = 'w=' . $width;
        }
        
        if ($height) {
            $params[] = 'h=' . $height;
        }
        
        if ($format) {
            $params[] = 'fm=' . $format;
        }
        
        $params[] = 'q=85'; // Quality 85%
        $params[] = 'auto=compress'; // Auto compression
        
        if (!empty($params)) {
            $separator = strpos($url, '?') !== false ? '&' : '?';
            $url .= $separator . implode('&', $params);
        }
        
        return $url;
    }
}

if (!function_exists('lazy_load_script')) {
    /**
     * Generate lazy loading JavaScript
     *
     * @return string JavaScript code for lazy loading
     */
    function lazy_load_script(): string
    {
        return <<<'JS'
        <script>
        // Lazy Loading Images
        document.addEventListener('DOMContentLoaded', function() {
            // Check if IntersectionObserver is supported
            if ('IntersectionObserver' in window) {
                // Lazy load images
                const lazyImages = document.querySelectorAll('img.lazy-load');
                
                const imageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const src = img.getAttribute('data-src');
                            
                            if (src) {
                                img.src = src;
                                img.classList.remove('lazy-load');
                                img.classList.add('lazy-loaded');
                                imageObserver.unobserve(img);
                            }
                        }
                    });
                }, {
                    rootMargin: '50px 0px', // Start loading 50px before element enters viewport
                    threshold: 0.01
                });
                
                lazyImages.forEach(function(img) {
                    imageObserver.observe(img);
                });
                
                // Lazy load background images
                const lazyBackgrounds = document.querySelectorAll('.lazy-background');
                
                const backgroundObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            const element = entry.target;
                            const bgUrl = element.getAttribute('data-background');
                            
                            if (bgUrl) {
                                element.style.backgroundImage = 'url(' + bgUrl + ')';
                                element.classList.remove('lazy-background');
                                element.classList.add('lazy-background-loaded');
                                backgroundObserver.unobserve(element);
                            }
                        }
                    });
                }, {
                    rootMargin: '50px 0px',
                    threshold: 0.01
                });
                
                lazyBackgrounds.forEach(function(element) {
                    backgroundObserver.observe(element);
                });
            } else {
                // Fallback for browsers that don't support IntersectionObserver
                const lazyImages = document.querySelectorAll('img.lazy-load');
                lazyImages.forEach(function(img) {
                    const src = img.getAttribute('data-src');
                    if (src) {
                        img.src = src;
                        img.classList.remove('lazy-load');
                        img.classList.add('lazy-loaded');
                    }
                });
                
                const lazyBackgrounds = document.querySelectorAll('.lazy-background');
                lazyBackgrounds.forEach(function(element) {
                    const bgUrl = element.getAttribute('data-background');
                    if (bgUrl) {
                        element.style.backgroundImage = 'url(' + bgUrl + ')';
                        element.classList.remove('lazy-background');
                        element.classList.add('lazy-background-loaded');
                    }
                });
            }
        });
        </script>
        JS;
    }
}

if (!function_exists('lazy_load_css')) {
    /**
     * Generate lazy loading CSS
     *
     * @return string CSS code for lazy loading
     */
    function lazy_load_css(): string
    {
        return <<<'CSS'
        <style>
        /* Lazy Loading Styles */
        img.lazy-load {
            opacity: 0;
            transition: opacity 0.3s ease-in;
        }
        
        img.lazy-loaded {
            opacity: 1;
        }
        
        .lazy-background {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: background-image 0.3s ease-in;
        }
        
        /* Blur effect while loading */
        img.lazy-load.blur-effect {
            filter: blur(10px);
        }
        
        img.lazy-loaded.blur-effect {
            filter: blur(0);
        }
        
        /* Skeleton loading effect */
        .lazy-load-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s ease-in-out infinite;
        }
        
        @keyframes skeleton-loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }
        </style>
        CSS;
    }
}

if (!function_exists('image_placeholder')) {
    /**
     * Generate base64 encoded placeholder image
     *
     * @param int $width Placeholder width
     * @param int $height Placeholder height
     * @param string $color Placeholder color
     * @param string $text Placeholder text
     * @return string Data URI of placeholder image
     */
    function image_placeholder(int $width = 300, int $height = 200, string $color = '#f0f0f0', string $text = 'Loading...'): string
    {
        $svg = <<<SVG
        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 {$width} {$height}'>
            <rect fill='{$color}' width='{$width}' height='{$height}'/>
            <text fill='#999' x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='16'>{$text}</text>
        </svg>
        SVG;
        
        return 'data:image/svg+xml,' . rawurlencode($svg);
    }
}

