<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enum\SyncEndpointEnum;
use App\Http\Services\SyncService;
use Exception;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class SyncDataBase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for import data from wb-api';

    public function __construct(
        private readonly SyncService $syncService,
        private readonly LoggerInterface $logger
    ){
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(): void
    {
        set_time_limit(3600);
        ini_set('memory_limit', '512M');

        $this->info("Sync data from wb-api started...");
        $progressBar = $this->output->createProgressBar(4);
        $progressBar->setFormat('debug');
        $progressBar->start();

        try {
            $this->info("\nSyncing orders...");
            $this->syncService->sync(SyncEndpointEnum::ORDERS);
            $progressBar->advance();

            $this->info("\nSyncing sales...");
            $this->syncService->sync(SyncEndpointEnum::SALES);
            $progressBar->advance();

            $this->info("\nSyncing incomes...");
            $this->syncService->sync(SyncEndpointEnum::INCOMES);
            $progressBar->advance();

            $this->info("\nSyncing stocks...");
            $this->syncService->sync(SyncEndpointEnum::STOCKS);
            $progressBar->advance();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }


        $progressBar->finish();
        $this->newLine();

        $this->info("Sync completed!");
    }
}
