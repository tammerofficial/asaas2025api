<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class OptimizePerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize-performance 
                            {--cache : Clear and warm up cache}
                            {--config : Optimize configuration}
                            {--routes : Cache routes}
                            {--views : Cache views}
                            {--all : Run all optimizations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '🚀 Optimize application performance with caching and optimizations';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Starting Performance Optimization...');
        $this->newLine();

        $all = $this->option('all');

        // Cache Optimization
        if ($all || $this->option('cache')) {
            $this->optimizeCache();
        }

        // Config Optimization
        if ($all || $this->option('config')) {
            $this->optimizeConfig();
        }

        // Route Optimization
        if ($all || $this->option('routes')) {
            $this->optimizeRoutes();
        }

        // View Optimization
        if ($all || $this->option('views')) {
            $this->optimizeViews();
        }

        // Show Redis stats
        if ($all || $this->option('cache')) {
            $this->showRedisStats();
        }

        $this->newLine();
        $this->info('✅ Performance optimization completed!');
        $this->newLine();

        $this->displayOptimizationTips();

        return Command::SUCCESS;
    }

    /**
     * Optimize cache
     */
    protected function optimizeCache(): void
    {
        $this->info('📦 Optimizing Cache...');

        // Clear existing cache
        $this->call('cache:clear');
        $this->line('  ✓ Cache cleared');

        // Clear config cache
        $this->call('config:clear');
        $this->line('  ✓ Config cache cleared');

        // Recache config
        $this->call('config:cache');
        $this->line('  ✓ Config cached');

        // Warm up cache
        $this->warmCache();

        $this->info('  ✅ Cache optimization complete');
        $this->newLine();
    }

    /**
     * Optimize configuration
     */
    protected function optimizeConfig(): void
    {
        $this->info('⚙️  Optimizing Configuration...');

        $this->call('config:cache');
        $this->line('  ✓ Configuration cached');

        $this->info('  ✅ Configuration optimization complete');
        $this->newLine();
    }

    /**
     * Optimize routes
     */
    protected function optimizeRoutes(): void
    {
        $this->info('🛣️  Optimizing Routes...');

        $this->call('route:cache');
        $this->line('  ✓ Routes cached');

        $this->info('  ✅ Route optimization complete');
        $this->newLine();
    }

    /**
     * Optimize views
     */
    protected function optimizeViews(): void
    {
        $this->info('👁️  Optimizing Views...');

        $this->call('view:cache');
        $this->line('  ✓ Views cached');

        $this->info('  ✅ View optimization complete');
        $this->newLine();
    }

    /**
     * Warm up cache with common data
     */
    protected function warmCache(): void
    {
        $this->line('  🔥 Warming up cache...');

        try {
            // Call cache warming function
            if (function_exists('cache_warm')) {
                cache_warm();
                $this->line('  ✓ Cache warmed successfully');
            } else {
                $this->warn('  ⚠ cache_warm() function not found');
            }
        } catch (\Exception $e) {
            $this->error('  ✗ Cache warming failed: ' . $e->getMessage());
        }
    }

    /**
     * Show Redis statistics
     */
    protected function showRedisStats(): void
    {
        $this->info('📊 Redis Cache Statistics:');

        try {
            $stats = cache_stats();

            $this->table(
                ['Metric', 'Value'],
                [
                    ['Keyspace Hits', number_format($stats['keyspace_hits'] ?? 0)],
                    ['Keyspace Misses', number_format($stats['keyspace_misses'] ?? 0)],
                    ['Hit Rate', $stats['hit_rate'] ?? 'N/A'],
                    ['Memory Used', $stats['memory_used'] ?? 'N/A'],
                ]
            );

            $this->newLine();
        } catch (\Exception $e) {
            $this->error('  ✗ Unable to fetch Redis stats: ' . $e->getMessage());
        }
    }

    /**
     * Display optimization tips
     */
    protected function displayOptimizationTips(): void
    {
        $this->info('💡 Optimization Tips:');
        $this->newLine();

        $tips = [
            '1. Run this command after every deployment',
            '2. Use Redis for cache, session, and queue in production',
            '3. Enable OPcache in php.ini',
            '4. Use Laravel Octane for 3-4x performance boost',
            '5. Monitor cache hit rate (aim for >90%)',
            '6. Use eager loading to prevent N+1 queries',
            '7. Compress assets with Gzip/Brotli',
            '8. Use CDN for static assets',
            '9. Optimize images (WebP format)',
            '10. Monitor application with Laravel Telescope',
        ];

        foreach ($tips as $tip) {
            $this->line('  ' . $tip);
        }

        $this->newLine();

        // Octane specific tips
        if (app()->bound('octane')) {
            $this->info('🚀 Octane Detected:');
            $this->line('  • Reload Octane after code changes: php artisan octane:reload');
            $this->line('  • Check Octane status: php artisan octane:status');
            $this->newLine();
        } else {
            $this->comment('💡 Consider using Laravel Octane for better performance:');
            $this->line('  ./install-octane-redis.sh');
            $this->newLine();
        }

        // Performance benchmarks
        $this->info('📈 Expected Performance Improvements:');
        $this->table(
            ['Optimization', 'Speed Increase'],
            [
                ['OPcache', '20-30%'],
                ['Redis Cache', '10-15x queries'],
                ['Config/Route Cache', '5-10%'],
                ['Laravel Octane', '3-4x overall'],
                ['Combined', '5-10x overall'],
            ]
        );
    }
}

