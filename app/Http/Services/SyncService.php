<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Dto\AccountDto;
use App\Dto\BaseDto;
use App\Enum\SyncEndpointEnum;
use App\Handlers\BaseHandler;
use App\Http\Exceptions\AccountNotFoundException;
use App\Http\Exceptions\DtoNotFoundException;
use App\Http\Exceptions\HandlerNotFoundException;
use App\Models\Account;
use App\Repositories\AccountRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SyncService
{
    public Account $account;

    /**
     * @param BaseHandler[] $handlers
     */
    public function __construct(
        private readonly ApiClientService $apiClient,
        private readonly DebugService $debugService,
        private readonly iterable $handlers,
        private readonly AccountRepository $accountRepository
    ) {
    }

    /**
     * @throws ModelNotFoundException
     * @throws Exception
     * */
    public function sync(SyncEndpointEnum $endpoint): void
    {
        $handler = $this->getHandler($endpoint);

        /** @var class-string<BaseDto> $dtoClass */
        $dtoClass = $this->makeDto($endpoint);

        if (gc_enabled()) {
            gc_collect_cycles();
        }

        $items = $this->apiClient->fetchData($endpoint);
        if (empty($items)) {
            $this->debugService->warning("SyncService: no items fetched for endpoint={$endpoint->value}");
            return;
        }

        $this->debugService->info("SyncService: endpoint={$endpoint->value}, fetched " . count($items) . " items total");

        $model = $handler->getModelClass();
        if (!class_exists($model)) {
            throw new ModelNotFoundException("Model class {$model} does not exist");
        }

        $model::truncate();

        $chunkSize = 1000;
        $chunks = array_chunk($items, $chunkSize);

        foreach ($chunks as $chunkIndex => $chunk) {
            $this->debugService->info("Processing chunk {$chunkIndex} of " . count($chunks) . " for {$endpoint->value}");

            foreach ($chunk as $itemIndex => $item) {
                try {
                    $dto = $dtoClass::fromArray($item);
                    $model::create($handler->getValues($dto));
                } catch (\Throwable $e) {
                    $this->debugService->error("Error processing item {$chunkIndex}-{$itemIndex}: " . $e->getMessage());
                    $this->debugService->debug("Problematic item data: " . json_encode($item, JSON_UNESCAPED_UNICODE));
                    continue;
                }
            }

            if (gc_enabled()) {
                gc_collect_cycles();
            }

            $this->debugService->info("Memory usage: " . round(memory_get_usage(true) / 1024 / 1024, 2) . "MB");
        }
    }

    /**
     * @throws HandlerNotFoundException
     */
    private function getHandler(SyncEndpointEnum $endpoint): BaseHandler
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($endpoint)) {
                return $handler;
            }
        }

        throw new HandlerNotFoundException($endpoint->value);
    }

    /**
     * @throws DtoNotFoundException
     */
    private function makeDto(SyncEndpointEnum $endpoint): string
    {
        return match($endpoint) {
            SyncEndpointEnum::ORDERS => \App\Dto\OrderDto::class,
            SyncEndpointEnum::SALES => \App\Dto\SaleDto::class,
            SyncEndpointEnum::INCOMES => \App\Dto\IncomeDto::class,
            SyncEndpointEnum::STOCKS => \App\Dto\StockDto::class,
            default => throw new DtoNotFoundException("Dto not found for endpoint {$endpoint->value}"),
        };
    }

    public function getAccounts(): array
    {
        return $this->accountRepository->all();
    }

    /**
     * @throws AccountNotFoundException
     */
    public function setAccount(AccountDto $accountDto): void
    {
        $account = $this->accountRepository->getById($accountDto->id);
        if (!$account) {
            throw new AccountNotFoundException($accountDto->id);
        }

        $this->account = $account;
    }
}
