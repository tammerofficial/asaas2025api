<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\StaticOptionCentral;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\CpanelAutomation\Http\Services\CpanelHelper;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Events\DatabaseDeleted;
use Stancl\Tenancy\Events\DeletingDatabase;
use Stancl\Tenancy\Jobs\DeleteDatabase;

class DeleteDatabaseWithFallback extends DeleteDatabase
{


    public function handle()
    {
        event(new DeletingDatabase($this->tenant));

        try {
            // Try the default database deletion first
            $this->tenant->database()->manager()->deleteDatabase($this->tenant);
        } catch (\Exception $e) {
            Log::warning('Default database deletion failed: ' . $e->getMessage());

            // Get CPanel automation status
            $cpanelAutomationStatus = StaticOptionCentral::where('option_name', '_cpanel_automation_status')
                ->first()?->option_value;

            if ($cpanelAutomationStatus) {
                try {
                    // Get CPanel configuration from StaticOptionCentral
                    $cpanelHost = StaticOptionCentral::where('option_name', '_cpanel_url')->first()?->option_value;
                    $cpanelUsername = StaticOptionCentral::where('option_name', '_cpanel_username')->first()?->option_value;
                    $cpanelPassword = StaticOptionCentral::where('option_name', '_cpanel_access_token')->first()?->option_value;

                    $cpanel = new CpanelHelper(
                        cpanelUrl: $cpanelHost,
                        cpanelToken: $cpanelPassword,
                        cpanelUser: $cpanelUsername
                    );

                    // Get database information from tenant data
                    $tenantData = DB::table('tenants')->where('id', $this->tenant->id)->first();
                    $data = json_decode($tenantData->data ?? '{}', true);

                    $dbName = $data['tenancy_db_name'] ?? 'db_' . $this->tenant->database;
                    $userName = $data['tenancy_db_username'] ?? 'db_user_' . $this->tenant->database;

                    // Delete database user
                    $cpanel->deleteDatabaseUser($userName);

                    // Delete database
                    $cpanel->deleteDatabase($this->tenant->database);

                    return; // Successfully deleted database using CPanel
                } catch (\Exception $cpanelError) {
                    Log::error('CPanel database deletion also failed: ' . $cpanelError->getMessage());
                    // Both methods failed, throw the CPanel error
                    throw $cpanelError;
                }
            }
        }

        event(new DatabaseDeleted($this->tenant));
    }
}
