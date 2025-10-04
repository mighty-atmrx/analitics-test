<?php

namespace App\Http\Services;

use App\Dto\BaseDto;
use App\Handlers\BaseHandler;
use App\Handlers\OrderSyncHandler;
use App\Handlers\SaleSyncHandler;
use App\Handlers\StockSyncHandler;
use App\Handlers\IncomeSyncHandler;
use Exception;
use Illuminate\Support\Facades\Log;

class SyncService
{
    private ApiClientService $apiClient;
    public function __construct(
    ) {
        $this->apiClient = new ApiClientService();
    }

    /**
     * @throws Exception
     * */
    public function sync(string $endpoint): void
    {
        $handler = $this->makeHandler($endpoint);

        /** @var class-string<BaseDto> $dtoClass */
        $dtoClass = $this->makeDto($endpoint);

        if (gc_enabled()) {
            gc_collect_cycles();
        }

        $items = $this->apiClient->fetchData($endpoint);

        Log::info("SyncService: endpoint={$endpoint}, fetched " . count($items) . " items total");

        if (empty($items)) {
            Log::warning("SyncService: no items fetched for endpoint={$endpoint}");
            return;
        }

        $model = $handler->getModelClass();
        if (!class_exists($model)) {
            throw new Exception("Model class {$model} does not exist");
        }

        $chunkSize = 1000;
        $chunks = array_chunk($items, $chunkSize);

        foreach ($chunks as $chunkIndex => $chunk) {
            Log::info("Processing chunk {$chunkIndex} of " . count($chunks) . " for {$endpoint}");

            foreach ($chunk as $itemIndex => $item) {
                try {
                    $dto = $dtoClass::fromArray($item);
                    $model::create($handler->getValues($dto));
                } catch (\Throwable $e) {
                    Log::error("Error processing item {$chunkIndex}-{$itemIndex}: " . $e->getMessage());
                    Log::debug("Problematic item data: " . json_encode($item, JSON_UNESCAPED_UNICODE));
                    continue;
                }
            }

            if (gc_enabled()) {
                gc_collect_cycles();
            }

            Log::info("Memory usage: " . round(memory_get_usage(true) / 1024 / 1024, 2) . "MB");
        }
    }

    /**
     * @throws Exception
     */
    private function makeHandler(string $endpoint): BaseHandler
    {
        return match($endpoint) {
            'orders' => app(OrderSyncHandler::class),
            'sales'  => app(SaleSyncHandler::class),
            'incomes'=> app(IncomeSyncHandler::class),
            'stocks' => app(StockSyncHandler::class),
            default  => throw new \Exception("No handler for {$endpoint}")
        };
    }

    /**
     * @throws Exception
     */
    private function makeDto(string $endpoint): string
    {
        return match($endpoint) {
            'orders' => \App\Dto\OrderDto::class,
            'sales'  => \App\Dto\SaleDto::class,
            'incomes'=> \App\Dto\IncomeDto::class,
            'stocks' => \App\Dto\StockDto::class,
            default  => throw new \Exception("No DTO for {$endpoint}")
        };
    }
}
