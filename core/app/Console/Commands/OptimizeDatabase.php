<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class OptimizeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:optimize-database 
                            {--analyze : Analyze tables}
                            {--indexes : Check and suggest indexes}
                            {--slow-queries : Show slow queries}
                            {--all : Run all optimizations}';

    /**
     * The console command description.
     */
    protected $description = '🗄️ Optimize database performance - analyze tables, check indexes, find slow queries';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🗄️ Database Optimization Tool');
        $this->newLine();

        $all = $this->option('all');

        if ($all || $this->option('analyze')) {
            $this->analyzeTables();
        }

        if ($all || $this->option('indexes')) {
            $this->checkIndexes();
        }

        if ($all || $this->option('slow-queries')) {
            $this->showSlowQueries();
        }

        $this->newLine();
        $this->info('✅ Database optimization complete!');

        return Command::SUCCESS;
    }

    /**
     * Analyze and optimize tables
     */
    protected function analyzeTables(): void
    {
        $this->info('📊 Analyzing Tables...');
        $this->newLine();

        $tables = $this->getTables();

        $bar = $this->output->createProgressBar(count($tables));
        $bar->start();

        foreach ($tables as $table) {
            // Analyze table
            DB::statement("ANALYZE TABLE {$table}");
            
            // Optimize table (only for MyISAM and InnoDB)
            try {
                DB::statement("OPTIMIZE TABLE {$table}");
            } catch (\Exception $e) {
                // Some tables can't be optimized
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->line('  ✓ Analyzed ' . count($tables) . ' tables');
        $this->newLine();
    }

    /**
     * Check and suggest indexes
     */
    protected function checkIndexes(): void
    {
        $this->info('🔍 Checking Indexes...');
        $this->newLine();

        $suggestions = [];

        // Check common patterns
        $tables = $this->getTables();

        foreach ($tables as $table) {
            $columns = $this->getTableColumns($table);
            $indexes = $this->getTableIndexes($table);

            // Check for foreign key columns without indexes
            foreach ($columns as $column) {
                if ($this->isForeignKeyColumn($column) && !$this->hasIndex($indexes, $column)) {
                    $suggestions[] = [
                        'table' => $table,
                        'column' => $column,
                        'type' => 'Foreign Key',
                        'reason' => 'Foreign key without index',
                        'sql' => "ALTER TABLE `{$table}` ADD INDEX `idx_{$column}` (`{$column}`);",
                    ];
                }
            }

            // Check for timestamp columns
            if (in_array('created_at', $columns) && !$this->hasIndex($indexes, 'created_at')) {
                $suggestions[] = [
                    'table' => $table,
                    'column' => 'created_at',
                    'type' => 'Timestamp',
                    'reason' => 'Frequently used in ORDER BY',
                    'sql' => "ALTER TABLE `{$table}` ADD INDEX `idx_created_at` (`created_at`);",
                ];
            }

            // Check for status columns
            if (in_array('status', $columns) && !$this->hasIndex($indexes, 'status')) {
                $suggestions[] = [
                    'table' => $table,
                    'column' => 'status',
                    'type' => 'Status',
                    'reason' => 'Frequently used in WHERE clauses',
                    'sql' => "ALTER TABLE `{$table}` ADD INDEX `idx_status` (`status`);",
                ];
            }
        }

        if (empty($suggestions)) {
            $this->line('  ✓ All tables have appropriate indexes');
        } else {
            $this->warn('  Found ' . count($suggestions) . ' index suggestions:');
            $this->newLine();

            $this->table(
                ['Table', 'Column', 'Type', 'Reason'],
                array_map(function ($s) {
                    return [$s['table'], $s['column'], $s['type'], $s['reason']];
                }, $suggestions)
            );

            $this->newLine();
            $this->comment('💡 To add these indexes, run:');
            $this->newLine();

            foreach ($suggestions as $suggestion) {
                $this->line('  ' . $suggestion['sql']);
            }
        }

        $this->newLine();
    }

    /**
     * Show slow queries
     */
    protected function showSlowQueries(): void
    {
        $this->info('🐌 Checking Slow Queries...');
        $this->newLine();

        // Enable query log temporarily
        DB::enableQueryLog();

        // Check if slow query log is enabled
        try {
            $slowLog = DB::select("SHOW VARIABLES LIKE 'slow_query_log'");
            $slowLogFile = DB::select("SHOW VARIABLES LIKE 'slow_query_log_file'");
            $longQueryTime = DB::select("SHOW VARIABLES LIKE 'long_query_time'");

            $this->table(
                ['Variable', 'Value'],
                [
                    ['Slow Query Log', $slowLog[0]->Value ?? 'N/A'],
                    ['Slow Query Log File', $slowLogFile[0]->Value ?? 'N/A'],
                    ['Long Query Time', $longQueryTime[0]->Value ?? 'N/A' . 's'],
                ]
            );

            if (($slowLog[0]->Value ?? 'OFF') === 'OFF') {
                $this->newLine();
                $this->warn('💡 Slow query log is disabled. To enable:');
                $this->line('  SET GLOBAL slow_query_log = \'ON\';');
                $this->line('  SET GLOBAL long_query_time = 2;');
            }
        } catch (\Exception $e) {
            $this->error('  ✗ Unable to check slow query log: ' . $e->getMessage());
        }

        $this->newLine();

        // Show optimization tips
        $this->info('💡 Database Optimization Tips:');
        $this->newLine();

        $tips = [
            '1. Use indexes on columns in WHERE, ORDER BY, and JOIN clauses',
            '2. Use EXPLAIN to analyze query performance',
            '3. Avoid SELECT * - only fetch needed columns',
            '4. Use pagination for large result sets',
            '5. Use eager loading to prevent N+1 queries',
            '6. Cache frequently accessed data with Redis',
            '7. Use database connection pooling',
            '8. Optimize table structure (normalize when needed)',
            '9. Use appropriate column types (INT vs VARCHAR)',
            '10. Regular ANALYZE and OPTIMIZE TABLE maintenance',
        ];

        foreach ($tips as $tip) {
            $this->line('  ' . $tip);
        }

        $this->newLine();
    }

    /**
     * Get all tables in database
     */
    protected function getTables(): array
    {
        $database = config('database.connections.mysql.database');
        
        $tables = DB::select("
            SELECT TABLE_NAME 
            FROM information_schema.TABLES 
            WHERE TABLE_SCHEMA = ? 
            AND TABLE_TYPE = 'BASE TABLE'
        ", [$database]);

        return array_map(fn($t) => $t->TABLE_NAME, $tables);
    }

    /**
     * Get table columns
     */
    protected function getTableColumns(string $table): array
    {
        $database = config('database.connections.mysql.database');

        $columns = DB::select("
            SELECT COLUMN_NAME 
            FROM information_schema.COLUMNS 
            WHERE TABLE_SCHEMA = ? 
            AND TABLE_NAME = ?
        ", [$database, $table]);

        return array_map(fn($c) => $c->COLUMN_NAME, $columns);
    }

    /**
     * Get table indexes
     */
    protected function getTableIndexes(string $table): array
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}`");

        return array_map(fn($i) => $i->Column_name, $indexes);
    }

    /**
     * Check if column is likely a foreign key
     */
    protected function isForeignKeyColumn(string $column): bool
    {
        return str_ends_with($column, '_id') && $column !== 'id';
    }

    /**
     * Check if column has an index
     */
    protected function hasIndex(array $indexes, string $column): bool
    {
        return in_array($column, $indexes);
    }
}

