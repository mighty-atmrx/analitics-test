<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enum\SyncEndpointEnum;
use App\Http\Exceptions\AccountNotFoundException;
use App\Http\Services\DebugService;
use App\Http\Services\SyncService;
use Exception;
use Illuminate\Console\Command;

class SyncDataBase extends Command
{
    protected $signature = 'sync:data';
    protected $description = 'Command for import data from wb-api';

    public function __construct(
        private readonly SyncService $syncService,
        private readonly DebugService $debugService
    ){
        parent::__construct();
    }

    /**
     * @throws AccountNotFoundException
     */
    public function handle(): void
    {
        set_time_limit(36000);
        ini_set('memory_limit', '512M');

        $this->debugService->info("Sync data from wb-api started...");

        $accounts = $this->syncService->getAccounts();
        if (empty($accounts)) {
            $this->error('There are no accounts available for syncing.!');
            return;
        }

        if ($this->isRunningFromCron()) {
            $this->syncAllAccounts($accounts);
            return;
        }

        $this->interactiveSync($accounts);
    }

    private function syncAllAccounts(array $accounts): void
    {
        $this->info("Starting sync for all accounts...");

        foreach ($accounts as $account) {
            $this->info("Syncing account: {$account->name} (ID: {$account->id})");

            try {
                $this->syncService->setAccount($account);
                $this->syncStages();
                $this->info("✓ Account {$account->name} synced successfully");
            } catch (Exception $e) {
                $this->error("✗ Failed to sync account {$account->name}: " . $e->getMessage());
            }
        }

        $this->info("All accounts sync completed!");
    }

    /**
     * @throws AccountNotFoundException
     */
    private function interactiveSync(array $accounts): void
    {
        $choices = array_map(function ($account) {
            return 'name: ' . $account->name . '. account_id: ' . $account->id;
        }, $accounts);

        $selectedValue = $this->choice('Select an account to sync. Default: ', $choices, 0);
        $selectedIndex = array_search($selectedValue, $choices, true);
        $account = $accounts[$selectedIndex] ?? null;

        if (!$account) {
            $this->error('Incorrect choice!');
            return;
        }

        $this->info("Account selected: {$account->name}");
        $this->syncService->setAccount($account);
        $this->syncStages();
    }

    private function syncStages(): void
    {
        $stages = [
            SyncEndpointEnum::ORDERS,
            SyncEndpointEnum::SALES,
            SyncEndpointEnum::INCOMES,
            SyncEndpointEnum::STOCKS,
        ];

        $total = count($stages);
        $current = 0;

        $this->printProgress($current, $total);

        try {
            foreach ($stages as $stage) {
                $current++;
                $this->debugService->info("\nSyncing {$stage->value}... ({$current}/{$total})");

                $this->syncService->sync($stage);

                $this->printProgress($current, $total);
            }
        } catch (Exception $e) {
            $this->debugService->error("Sync data error: " . $e->getMessage());
            $this->printProgress($current, $total);
            return;
        }

        $this->debugService->info("Sync completed!");
        $this->printProgress($total, $total);
    }

    private function isRunningFromCron(): bool
    {
        return !$this->input->isInteractive();
    }

    private function printProgress(int $current, int $total): void
    {
        echo "- Completed {$current} of {$total} steps\n";
    }
}
