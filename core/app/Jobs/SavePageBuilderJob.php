<?php

namespace App\Jobs;

use App\Models\PageBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class SavePageBuilderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $widget_id,
        public array $data,
        public ?int $tenant_id = null
    ) {
        // Set queue name
        $this->onQueue('pagebuilder');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Initialize tenant context if tenant_id is provided
            if ($this->tenant_id) {
                $tenant = \App\Models\Tenant::find($this->tenant_id);
                if ($tenant) {
                    tenancy()->initialize($tenant);
                }
            }

            // Find or create widget
            $widget = PageBuilder::find($this->widget_id);

            if (!$widget) {
                Log::warning('PageBuilder widget not found', [
                    'widget_id' => $this->widget_id,
                    'tenant_id' => $this->tenant_id,
                ]);
                return;
            }

            // Prepare update data
            $updateData = [
                'addon_type' => $this->data['addon_type'] ?? $widget->addon_type,
                'addon_location' => $this->data['addon_location'] ?? $widget->addon_location,
                'addon_name' => $this->data['addon_name'] ?? $widget->addon_name,
                'addon_namespace' => $this->data['addon_namespace'] ?? $widget->addon_namespace,
                'addon_order' => $this->data['addon_order'] ?? $widget->addon_order,
                'addon_page_id' => $this->data['addon_page_id'] ?? $widget->addon_page_id,
                'addon_page_type' => $this->data['addon_page_type'] ?? $widget->addon_page_type,
                'addon_settings' => json_encode($this->data),
            ];

            // Update widget in database
            $widget->update($updateData);

            Log::info('PageBuilder widget saved to database', [
                'widget_id' => $this->widget_id,
                'tenant_id' => $this->tenant_id,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to save PageBuilder widget to database', [
                'widget_id' => $this->widget_id,
                'tenant_id' => $this->tenant_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw to trigger retry
            throw $e;
        } finally {
            // Clean up tenant context
            if ($this->tenant_id && tenancy()->initialized) {
                tenancy()->end();
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('PageBuilder save job failed permanently', [
            'widget_id' => $this->widget_id,
            'tenant_id' => $this->tenant_id,
            'error' => $exception->getMessage(),
        ]);
    }
}
