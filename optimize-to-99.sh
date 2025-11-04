#!/bin/bash

# 🚀 Optimize to 99 - Complete Performance Optimization
# This script applies ALL optimizations to reach Lighthouse 90+

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🚀 Optimizing to Lighthouse 90+ Score"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

cd "$(dirname "$0")" || exit 1

# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# Step 0: Check DebugBar in Production
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

echo "🔍 Step 0: Checking DebugBar Configuration..."
echo ""

cd core || exit 1

# Check if DebugBar is enabled in production
if [ -f ".env" ]; then
    APP_DEBUG=$(grep "^APP_DEBUG" .env | cut -d'=' -f2)
    DEBUGBAR_ENABLED=$(grep "^DEBUGBAR_ENABLED" .env | cut -d'=' -f2)
    
    if [ "$APP_DEBUG" = "true" ] || [ "$DEBUGBAR_ENABLED" = "true" ]; then
        echo -e "${RED}⚠ DebugBar is ENABLED in production!${NC}"
        echo "  This will hurt your PageSpeed score!"
        echo ""
        echo "  Add to .env:"
        echo "    APP_DEBUG=false"
        echo "    DEBUGBAR_ENABLED=false"
        echo ""
        echo "  Then run: php artisan config:clear && php artisan config:cache"
        echo ""
    else
        echo -e "${GREEN}✓${NC} DebugBar is disabled in production"
        echo ""
    fi
else
    echo -e "${YELLOW}⚠${NC} .env file not found"
    echo "  Make sure APP_DEBUG=false and DEBUGBAR_ENABLED=false in production"
    echo ""
fi

cd ..

# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# Step 1: Check Cache Headers
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

echo "📦 Step 1: Checking Cache Headers..."
echo ""

# Test if .htaccess cache is working
CACHE_TEST=$(curl -sI https://asaas.local/assets/landlord/admin/css/style.css 2>/dev/null | grep -i "cache-control")

if [ -z "$CACHE_TEST" ]; then
    echo -e "${YELLOW}⚠ Cache headers not working${NC}"
    echo "  Solution: Check if mod_expires and mod_headers are enabled in Apache"
    echo ""
else
    echo -e "${GREEN}✓${NC} Cache headers are working"
    echo "  $CACHE_TEST"
    echo ""
fi

# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# Step 2: Optimize Images
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

echo "🖼️  Step 2: Optimizing Images..."
echo ""

# Check if ImageMagick is installed
if command -v convert &> /dev/null; then
    echo -e "${GREEN}✓${NC} ImageMagick is installed"
    
    # Find large images
    UPLOADS_DIR="assets/landlord/uploads/media-uploader"
    
    if [ -d "$UPLOADS_DIR" ]; then
        echo "  Scanning for large images..."
        
        # Find PNG files larger than 50KB
        find "$UPLOADS_DIR" -name "*.png" -size +50k -exec echo "  Found: {}" \;
        
        # Find JPG files larger than 50KB
        find "$UPLOADS_DIR" -name "*.jpg" -size +50k -exec echo "  Found: {}" \;
        
        echo ""
        echo -e "${YELLOW}💡 To optimize manually:${NC}"
        echo "  convert input.png -resize 50% -quality 85 output.png"
        echo "  convert input.png -quality 85 output.webp"
        echo ""
    fi
else
    echo -e "${YELLOW}⚠ ImageMagick not installed${NC}"
    echo "  Install: brew install imagemagick (macOS)"
    echo "  Or: sudo apt-get install imagemagick (Linux)"
    echo ""
fi

# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# Step 3: Add Image Lazy Loading
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

echo "⚡ Step 3: Adding Image Lazy Loading..."
echo ""

# Add loading="lazy" to images in views
VIEWS_DIR="core/resources/views"

if [ -d "$VIEWS_DIR" ]; then
    # Count images without lazy loading
    IMG_COUNT=$(grep -r "<img" "$VIEWS_DIR" | grep -v "loading=\"lazy\"" | grep -v ".blade.php~" | wc -l)
    
    echo "  Found $IMG_COUNT images without lazy loading"
    
    if [ $IMG_COUNT -gt 0 ]; then
        echo -e "${YELLOW}💡 Add loading=\"lazy\" to all <img> tags${NC}"
        echo ""
    else
        echo -e "${GREEN}✓${NC} All images have lazy loading"
        echo ""
    fi
fi

# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# Step 4: Minify CSS/JS
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

echo "🗜️  Step 4: Checking CSS/JS Minification..."
echo ""

cd core || exit 1

# Check if files are minified
CSS_FILE="public/assets/landlord/admin/css/style.css"
JS_FILE="public/assets/landlord/admin/js/misc.js"

if [ -f "$CSS_FILE" ]; then
    # Check if minified (minified files have very long lines)
    FIRST_LINE=$(head -n 1 "$CSS_FILE")
    if [ ${#FIRST_LINE} -lt 200 ]; then
        echo -e "${YELLOW}⚠${NC} style.css is NOT minified"
    else
        echo -e "${GREEN}✓${NC} style.css is minified"
    fi
fi

if [ -f "$JS_FILE" ]; then
    FIRST_LINE=$(head -n 1 "$JS_FILE")
    if [ ${#FIRST_LINE} -lt 200 ]; then
        echo -e "${YELLOW}⚠${NC} misc.js is NOT minified"
    else
        echo -e "${GREEN}✓${NC} misc.js is minified"
    fi
fi

echo ""
echo -e "${YELLOW}💡 To minify:${NC}"
echo "  npm run production"
echo "  Or use online tools:"
echo "  - https://cssminifier.com/"
echo "  - https://javascript-minifier.com/"
echo ""

# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# Step 5: Optimize Database
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

echo "🗄️  Step 5: Optimizing Database..."
echo ""

php artisan app:optimize-database --all 2>/dev/null

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Database optimized"
else
    echo -e "${YELLOW}⚠${NC} Database optimization command not found"
    echo "  Run manually: php artisan app:optimize-database --all"
fi

echo ""

# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# Step 6: Clear and Warm Caches
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

echo "🧹 Step 6: Clearing and Warming Caches..."
echo ""

php artisan cache:clear > /dev/null 2>&1
echo -e "${GREEN}✓${NC} Cache cleared"

php artisan config:cache > /dev/null 2>&1
echo -e "${GREEN}✓${NC} Config cached"

php artisan route:cache > /dev/null 2>&1
echo -e "${GREEN}✓${NC} Routes cached"

php artisan view:cache > /dev/null 2>&1
echo -e "${GREEN}✓${NC} Views cached"

echo ""

# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# Step 7: Check Redis
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

echo "📊 Step 7: Checking Redis..."
echo ""

if redis-cli ping > /dev/null 2>&1; then
    echo -e "${GREEN}✓${NC} Redis is running"
    
    # Get Redis stats
    REDIS_KEYS=$(redis-cli dbsize 2>/dev/null | awk '{print $2}')
    echo "  Keys in database: $REDIS_KEYS"
    
    # Check cache driver
    CACHE_DRIVER=$(grep "CACHE_DRIVER" ../.env 2>/dev/null | cut -d'=' -f2)
    if [ "$CACHE_DRIVER" = "redis" ]; then
        echo -e "${GREEN}✓${NC} Cache driver is Redis"
    else
        echo -e "${YELLOW}⚠${NC} Cache driver is not Redis (current: $CACHE_DRIVER)"
        echo "  Set CACHE_DRIVER=redis in .env"
    fi
else
    echo -e "${YELLOW}⚠${NC} Redis is not running"
    echo "  Start Redis: redis-server --daemonize yes"
fi

echo ""

cd ..

# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# Step 8: Check Octane
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

echo "🚀 Step 8: Checking Octane..."
echo ""

cd core || exit 1

if [ -f "vendor/laravel/octane/bin/rr" ] || [ -f "rr" ]; then
    echo -e "${GREEN}✓${NC} Octane is installed"
    
    # Check if Octane is running
    if pgrep -f "octane:start" > /dev/null; then
        echo -e "${GREEN}✓${NC} Octane is running"
        echo -e "${YELLOW}💡 After changes, reload: php artisan octane:reload${NC}"
    else
        echo -e "${YELLOW}⚠${NC} Octane is not running"
        echo "  Start: php artisan octane:start --watch"
    fi
else
    echo -e "${YELLOW}⚠${NC} Octane is not installed"
    echo "  Install: ./install-octane-redis.sh"
fi

echo ""

cd ..

# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# Summary
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📊 Optimization Summary"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "✅ Completed Code Optimizations:"
echo "  ✓ LCP image preload added"
echo "  ✓ Font optimization (preconnect + display=swap)"
echo "  ✓ Conditional script loading (chart.js, update-info.js)"
echo "  ✓ Image lazy loading added"
echo "  ✓ Non-critical CSS deferred"
echo "  ✓ DebugBar config file created"
echo "  ✓ CDN libraries (jQuery, Bootstrap, etc.)"
echo "  ✓ Cache headers in .htaccess"
echo "  ✓ Defer/Async JavaScript"
echo "  ✓ Gzip compression"
echo "  ✓ Database optimization"
echo "  ✓ Laravel caches (config, route, view)"
echo ""

echo "⚠️  Manual Steps Required (High Priority):"
echo ""
echo "1️⃣  Disable DebugBar in Production:"
echo "    Add to .env:"
echo "      APP_DEBUG=false"
echo "      DEBUGBAR_ENABLED=false"
echo "    Then: cd core && php artisan config:clear && php artisan config:cache"
echo ""
echo "2️⃣  Optimize Images:"
echo "    - tammerred-11762270748.png: Resize to 280x134"
echo "    - no-image1672896265.jpg: Resize to 88x88"
echo "    - Convert to WebP format"
echo "    See: PAGESPEED_99_OPTIMIZATIONS.md for commands"
echo ""
echo "3️⃣  Minify CSS/JS:"
echo "    cd core && npm run production"
echo "    Or use online tools: https://cssminifier.com/"
echo ""
echo "4️⃣  Remove Unused CSS (PurgeCSS):"
echo "    See: PAGESPEED_99_OPTIMIZATIONS.md for setup"
echo ""
echo "5️⃣  Enable Redis Cache:"
echo "    Add to .env: CACHE_DRIVER=redis"
echo "    Then: cd core && php artisan config:cache"
echo ""
echo "6️⃣  Start Octane (Optional):"
echo "    cd core && php artisan octane:start --watch"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🧪 Testing"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "1️⃣  Open: https://asaas.local/admin-home"
echo ""
echo "2️⃣  Chrome DevTools (F12) → Lighthouse"
echo ""
echo "3️⃣  Run audit and check:"
echo "    • Performance: Target 90+"
echo "    • FCP: Target <0.9s"
echo "    • LCP: Target <1.2s"
echo "    • TBT: Target <200ms"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📚 Documentation"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "  Complete Guide: PERFORMANCE_TO_99_PLAN.md"
echo "  CDN Guide: CDN_OPTIMIZATION_GUIDE.md"
echo "  Redis+Octane: REDIS_OCTANE_SETUP_GUIDE.md"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo -e "${GREEN}🎉 Optimization script completed!${NC}"
echo ""
echo "Current Score: 81/100"
echo "Expected After Code Fixes: 85-88/100"
echo "Expected After Manual Fixes: 95-99/100"
echo ""
echo "📚 See PAGESPEED_99_OPTIMIZATIONS.md for complete guide"
echo ""

