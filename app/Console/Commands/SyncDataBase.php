<?php

namespace App\Console\Commands;

use App\Http\Services\SyncService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
        private readonly SyncService $syncService
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
            $progressBar->setMessage("Syncing orders...");// $this->info вместо $progressBar->setMessage
            $this->syncService->sync('orders');
            $progressBar->advance();

            $progressBar->setMessage("Syncing sales...");
            $this->syncService->sync('sales');
            $progressBar->advance();

            $progressBar->setMessage("Syncing incomes...");
            $this->syncService->sync('incomes');
            $progressBar->advance();

            $progressBar->setMessage("Syncing stocks...");
            $this->syncService->sync('stocks');
            $progressBar->advance();

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }


        $progressBar->finish();
        $this->newLine();

        $this->info("Sync completed!");
    }
}
