<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Product\Entities\Product;
use App\Models\Slug;

class ProcessProductImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600;
    public $tries = 1;

    protected $filePath;
    protected $mapping;
    protected $userId;
    protected $tenantId;

    public function __construct($filePath, $mapping, $userId, $tenantId)
    {
        $this->filePath = $filePath;
        $this->mapping = $mapping;
        $this->userId = $userId;
        $this->tenantId = $tenantId;
    }

    public function handle()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $fullPath = Storage::disk("tenant{$this->tenantId}")->path($this->filePath);

        if (!file_exists($fullPath)) {
            $this->notifyUser(0, 0, [], "Import failed: File not found");
            return;
        }

        $delimiter = $this->detectDelimiter($fullPath);
        $handle = fopen($fullPath, "r");

        if (!$handle) {
            $this->notifyUser(0, 0, [], "Import failed: Unable to open file");
            return;
        }

        $header = fgetcsv($handle, 0, $delimiter);
        $header = array_map(function ($value) {
            return preg_replace('/^[\x{200B}\x{FEFF}]/u', '', $value);
        }, $header);

        $imported = 0;
        $skipped = 0;
        $skipReasons = []; // Store reasons for skipped rows
        $batchSize = 1000;
        $processedCount = 0;

        while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
            try {
                $row = array_combine($header, $data);
                $productData = $this->processRow($row);

                if (!$productData) {
                    $skipped++;
                    $skipReasons[] = "Row " . ($processedCount + 1) . ": Missing or empty product name";
                    continue;
                }

                if ($this->createProduct($productData)) {
                    $imported++;
                } else {
                    $skipped++;
                    $skipReasons[] = "Row " . ($processedCount + 1) . ": Product with name '{$productData['name']}' already exists or invalid data";
                }

                $processedCount++;

                if ($processedCount % $batchSize === 0) {
                    $this->updateProgress($processedCount);
                    if ($processedCount % 1000 === 0) {
                        gc_collect_cycles();
                    }
                }

            } catch (\Exception $e) {
                $skipped++;
                $skipReasons[] = "Row " . ($processedCount + 1) . ": Error - " . $e->getMessage();
            }
        }

        fclose($handle);
        Storage::disk("tenant{$this->tenantId}")->delete($this->filePath);
        Log::info('Mapping: ' . json_encode($this->mapping));
        $this->notifyUser($imported, $skipped, $skipReasons);
    }

    private function createProduct($productData)
    {
        try {
            $exists = Product::where('name', $productData['name'])->exists();
            if ($exists) {
                return false;
            }

            $stockCount = $productData['stock_count'] ?? null;
            $sku = $productData['sku'] ?? null;
            $uom = $productData['uom'] ?? null;
            $slug = $productData['slug'];

            unset($productData['stock_count'], $productData['sku'], $productData['uom']);

            DB::transaction(function () use ($productData, $slug, $stockCount, $sku, $uom) {
                $product = Product::create($productData);

                DB::table('slugs')->insert([
                    'morphable_type' => Product::class,
                    'morphable_id' => $product->id,
                    'slug' => $slug,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($stockCount !== null || $sku !== null) {
                    DB::table('product_inventories')->insert([
                        'product_id' => $product->id,
                        'sku' => $sku ?? Str::upper(Str::random(8)),
                        'stock_count' => $stockCount ?? 0,
                    ]);
                }

                if ($uom) {
                    DB::table('product_uom')->insert([
                        'product_id' => $product->id,
                        'quantity' => floatval($uom),
                    ]);
                }
            });

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    private function processRow($row)
    {
        $productData = [];

        foreach ($this->mapping as $dbField => $csvField) {
            if (empty($csvField) || !isset($row[$csvField])) {
                continue;
            }

            $value = $row[$csvField];

            if ($value === '' || $value === null) {
                continue;
            }

            if (in_array($dbField, ['price', 'sale_price', 'cost'])) {
                $productData[$dbField] = floatval($value);
            } elseif (in_array($dbField, ['badge_id', 'brand_id', 'status_id', 'product_type', 'sold_count', 'min_purchase', 'max_purchase'])) {
                $productData[$dbField] = $value !== '' ? intval($value) : null;
            } elseif (in_array($dbField, ['is_refundable', 'is_in_house', 'is_inventory_warn_able'])) {
                $productData[$dbField] = intval($value);
            } else {
                $productData[$dbField] = trim($value);
            }
        }

        if (empty($productData['name'])) {
            return null;
        }

        $productData['status_id'] = $productData['status_id'] ?? 1;
        $productData['product_type'] = $productData['product_type'] ?? 1;
        $productData['is_in_house'] = $productData['is_in_house'] ?? 1;

        $slug = !empty($productData['slug'])
            ? Str::slug($productData['slug'])
            : Str::slug($productData['name']);

        $productData['slug'] = $slug;

        return $productData;
    }

    private function detectDelimiter($file)
    {
        if (!file_exists($file) || !is_file($file)) {
            return ',';
        }

        $delimiters = [",", ";", "\t", "|"];
        $results = [];

        $handle = fopen($file, "r");
        if (!$handle) {
            return ',';
        }

        $line = fgets($handle);
        fclose($handle);

        if (empty($line)) {
            return ',';
        }

        foreach ($delimiters as $delimiter) {
            $results[$delimiter] = count(str_getcsv($line, $delimiter));
        }

        return array_search(max($results), $results);
    }

    private function updateProgress($count)
    {
        cache()->put("import_progress_{$this->userId}_{$this->tenantId}", $count, 3600);
    }

    private function notifyUser($imported, $skipped, $skipReasons = [], $error = null)
    {
        if ($error) {
            Log::error("Import error: {$error}");
            cache()->put("import_result_{$this->userId}_{$this->tenantId}", [
                'status' => 'error',
                'message' => $error,
                'imported' => 0,
                'skipped' => 0,
                'reasons' => [],
            ], 3600);
        } else {
            $status = ($skipped == 0 && $imported > 0) ? 'success' : 'failed';
            $message = ($skipped == 0 && $imported > 0)
                ? "Successfully imported {$imported} products."
                : "Import failed: Imported {$imported}, Skipped {$skipped}.";

            Log::info($message, ['reasons' => $skipReasons]);
            cache()->put("import_result_{$this->userId}_{$this->tenantId}", [
                'status' => $status,
                'message' => $message,
                'imported' => $imported,
                'skipped' => $skipped,
                'reasons' => $skipReasons,
            ], 3600);
        }
    }
}
