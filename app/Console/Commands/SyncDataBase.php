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

        $choices = [];
        foreach ($accounts as $index => $account) {
            $choices[$index] = $account->name;
        }

        $selectedIndex = (int) $this->choice('Select an account to sync', $choices, 0);
        $account = $accounts[$selectedIndex] ?? null;

        if (!$account) {
            $this->error('Incorrect choice!');
            return;
        }

        $this->info("Account selected: {$account->name}");

        $this->syncService->setAccount($account);

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

    private function printProgress(int $current, int $total): void
    {
        echo "- Completed {$current} of {$total} steps\n";
    }
}
