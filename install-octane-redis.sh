#!/bin/bash

# 🚀 Laravel Octane + RoadRunner Installation Script
# For TammerSaaS Multi-Tenant Application
# With Complete Tenant Isolation

echo "🚀 Starting Laravel Octane + RoadRunner Installation..."
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Change to core directory
cd "$(dirname "$0")/core" || exit 1

echo "📍 Current directory: $(pwd)"
echo ""

# Step 1: Check Requirements
echo "📋 Step 1: Checking Requirements..."
echo ""

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "  ✓ PHP Version: $PHP_VERSION"

# Check if Redis is installed
if command -v redis-cli &> /dev/null; then
    REDIS_VERSION=$(redis-cli --version | awk '{print $2}')
    echo "  ✓ Redis Version: $REDIS_VERSION"
    
    # Check if Redis is running
    if redis-cli ping &> /dev/null; then
        echo "  ✓ Redis is running"
    else
        echo -e "  ${RED}✗ Redis is not running${NC}"
        echo "  Starting Redis..."
        redis-server --daemonize yes
    fi
else
    echo -e "  ${RED}✗ Redis is not installed${NC}"
    echo "  Please install Redis first:"
    echo "    macOS: brew install redis"
    echo "    Ubuntu: sudo apt-get install redis-server"
    exit 1
fi

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo -e "  ${RED}✗ Composer is not installed${NC}"
    exit 1
fi
echo "  ✓ Composer is installed"
echo ""

# Step 2: Install PHP Redis Extension (if not installed)
echo "📦 Step 2: Checking PHP Redis Extension..."
echo ""

if php -m | grep -q "redis"; then
    echo "  ✓ PHP Redis extension is installed"
else
    echo -e "  ${YELLOW}⚠ PHP Redis extension is not installed${NC}"
    echo "  Please install it:"
    echo "    macOS: pecl install redis"
    echo "    Ubuntu: sudo apt-get install php-redis"
    echo ""
    read -p "Continue anyway? (y/n): " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi
echo ""

# Step 3: Install Laravel Octane
echo "📦 Step 3: Installing Laravel Octane..."
echo ""

composer require laravel/octane
echo ""

# Step 4: Install RoadRunner
echo "📦 Step 4: Installing RoadRunner..."
echo ""

php artisan octane:install --server=roadrunner
echo ""

# Step 5: Install Predis (for Redis support)
echo "📦 Step 5: Installing Predis..."
echo ""

composer require predis/predis
echo ""

# Step 6: Copy configuration files
echo "📝 Step 6: Setting up Configuration Files..."
echo ""

# The config files are already created by our script
echo "  ✓ config/cache-tenancy.php"
echo "  ✓ config/octane.php (created by Octane installation)"
echo "  ✓ app/Providers/TenantCacheServiceProvider.php"
echo "  ✓ app/Http/Middleware/OctaneTenantIsolation.php"
echo ""

# Step 7: Update .env file
echo "📝 Step 7: Updating .env Configuration..."
echo ""

ENV_FILE="../.env"

# Function to update or add env variable
update_env() {
    local key=$1
    local value=$2
    local file=$3
    
    if grep -q "^${key}=" "$file"; then
        # Update existing
        sed -i.bak "s/^${key}=.*/${key}=${value}/" "$file"
        echo "  ✓ Updated: ${key}=${value}"
    else
        # Add new
        echo "${key}=${value}" >> "$file"
        echo "  ✓ Added: ${key}=${value}"
    fi
}

# Update cache driver
update_env "CACHE_DRIVER" "redis" "$ENV_FILE"
update_env "CACHE_PREFIX" "cache" "$ENV_FILE"

# Update session driver
update_env "SESSION_DRIVER" "redis" "$ENV_FILE"
update_env "SESSION_CONNECTION" "session" "$ENV_FILE"

# Update queue driver
update_env "QUEUE_CONNECTION" "redis" "$ENV_FILE"

# Redis configuration
update_env "REDIS_CLIENT" "phpredis" "$ENV_FILE"
update_env "REDIS_HOST" "127.0.0.1" "$ENV_FILE"
update_env "REDIS_PASSWORD" "null" "$ENV_FILE"
update_env "REDIS_PORT" "6379" "$ENV_FILE"

# Redis database allocation
update_env "REDIS_DB" "0" "$ENV_FILE"
update_env "REDIS_CACHE_DB" "1" "$ENV_FILE"
update_env "REDIS_SESSION_DB" "2" "$ENV_FILE"
update_env "REDIS_QUEUE_DB" "15" "$ENV_FILE"

# Octane configuration
update_env "OCTANE_SERVER" "roadrunner" "$ENV_FILE"

echo ""

# Step 8: Register Service Provider
echo "📝 Step 8: Registering TenantCacheServiceProvider..."
echo ""

BOOTSTRAP_FILE="bootstrap/providers.php"

if [ -f "$BOOTSTRAP_FILE" ]; then
    if ! grep -q "TenantCacheServiceProvider" "$BOOTSTRAP_FILE"; then
        # Add provider before the closing bracket
        sed -i.bak '/];/i\    App\\Providers\\TenantCacheServiceProvider::class,' "$BOOTSTRAP_FILE"
        echo "  ✓ TenantCacheServiceProvider registered"
    else
        echo "  ✓ TenantCacheServiceProvider already registered"
    fi
else
    echo -e "  ${YELLOW}⚠ bootstrap/providers.php not found (Laravel 11 only)${NC}"
    echo "  For Laravel 10 or earlier, add to config/app.php providers array"
fi
echo ""

# Step 9: Clear caches
echo "🧹 Step 9: Clearing Caches..."
echo ""

php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "  ✓ All caches cleared"
echo ""

# Step 10: Test Redis Connection
echo "🧪 Step 10: Testing Redis Connection..."
echo ""

php artisan tinker --execute="
try {
    \Illuminate\Support\Facades\Redis::connection('cache')->ping();
    echo '✓ Redis cache connection successful\n';
} catch (\Exception \$e) {
    echo '✗ Redis cache connection failed: ' . \$e->getMessage() . '\n';
}
"

echo ""

# Step 11: Create supervisor configuration (for production)
echo "📝 Step 11: Creating Supervisor Configuration (Optional - Production)..."
echo ""

SUPERVISOR_CONF="octane-roadrunner.conf"

cat > "$SUPERVISOR_CONF" << 'EOF'
[program:octane-roadrunner]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/core/artisan octane:start --server=roadrunner --host=0.0.0.0 --port=8000 --workers=4 --max-requests=500
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/octane.log
stopwaitsecs=3600
EOF

echo "  ✓ Supervisor config created: $SUPERVISOR_CONF"
echo "  📝 Edit the file and update paths, then copy to /etc/supervisor/conf.d/"
echo ""

# Step 12: Create start script
echo "📝 Step 12: Creating Start Scripts..."
echo ""

cat > "start-octane.sh" << 'EOF'
#!/bin/bash
# Start Laravel Octane with RoadRunner

echo "🚀 Starting Laravel Octane with RoadRunner..."

# Check if already running
if pgrep -f "octane:start" > /dev/null; then
    echo "⚠️  Octane is already running"
    echo "Run ./stop-octane.sh first"
    exit 1
fi

# Start Octane
php artisan octane:start \
    --server=roadrunner \
    --host=127.0.0.1 \
    --port=8000 \
    --workers=4 \
    --max-requests=500 \
    --watch

echo "✅ Octane started successfully!"
echo "🌐 Application running at: http://127.0.0.1:8000"
EOF

chmod +x start-octane.sh

cat > "stop-octane.sh" << 'EOF'
#!/bin/bash
# Stop Laravel Octane

echo "🛑 Stopping Laravel Octane..."

php artisan octane:stop

echo "✅ Octane stopped successfully!"
EOF

chmod +x stop-octane.sh

cat > "reload-octane.sh" << 'EOF'
#!/bin/bash
# Reload Laravel Octane (after code changes)

echo "🔄 Reloading Laravel Octane..."

php artisan octane:reload

echo "✅ Octane reloaded successfully!"
EOF

chmod +x reload-octane.sh

echo "  ✓ start-octane.sh created"
echo "  ✓ stop-octane.sh created"
echo "  ✓ reload-octane.sh created"
echo ""

# Completion
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ Installation Complete!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "📋 Next Steps:"
echo ""
echo "1️⃣  Test Redis Cache:"
echo "   redis-cli ping"
echo ""
echo "2️⃣  Start Octane (Development):"
echo "   ./start-octane.sh"
echo "   or"
echo "   php artisan octane:start --watch"
echo ""
echo "3️⃣  After code changes:"
echo "   ./reload-octane.sh"
echo "   or"
echo "   php artisan octane:reload"
echo ""
echo "4️⃣  Run tests to verify tenant isolation:"
echo "   php artisan test"
echo ""
echo "5️⃣  Monitor performance:"
echo "   redis-cli info stats"
echo "   php artisan octane:status"
echo ""
echo "🔗 Access your application at:"
echo "   http://127.0.0.1:8000"
echo ""
echo "📚 Documentation:"
echo "   - Octane: https://laravel.com/docs/octane"
echo "   - RoadRunner: https://roadrunner.dev/"
echo "   - Redis: https://redis.io/docs/"
echo ""
echo "⚠️  Important Notes:"
echo "   - Use octane:reload after code changes"
echo "   - Monitor memory usage with: php artisan octane:metrics"
echo "   - For production, use Supervisor (see octane-roadrunner.conf)"
echo "   - Tenant isolation is automatic via middleware"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

