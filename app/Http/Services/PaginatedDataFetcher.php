<?php

namespace App\Http\Services;

use App\Enum\SyncEndpointEnum;
use App\Models\Account;
use Exception;

readonly class PaginatedDataFetcher
{
    public function __construct(
        private ApiHttpClientService $apiHttpClient,
        private DateStrategyService $dateStrategy,
        private DebugService $debugService,
        private int $pageLimit = 500,
        private int $maxConsecutiveErrors = 3,
        private int $maxPages = 1000
    ) {}

    /**
     * @throws Exception
     */
    public function fetchData(SyncEndpointEnum $endpoint, string $model, Account $account): array
    {
        $allItems = [];
        $page = 1;
        $consecutiveErrors = 0;

        $dateParams = $this->dateStrategy->getDateRange($endpoint, $model, $account);

        do {
            try {
                $this->debugService->info("Fetching page {$page} for {$endpoint->value}");

                $response = $this->apiHttpClient->get($endpoint->value, [
                    'dateFrom' => $dateParams['from'],
                    'dateTo'   => $dateParams['to'],
                    'page'     => $page,
                    'limit'    => $this->pageLimit,
                ]);

                $items = $response['data'] ?? [];
                $currentPageCount = count($items);
                $lastPage = $response['meta']['last_page'] ?? null;

                if ($currentPageCount === 0) {
                    $this->debugService->info("API returned empty page {$page}, stopping");
                    break;
                }

                $allItems = array_merge($allItems, $items);
                $this->debugService->info("Loaded page {$page}, items: {$currentPageCount}, total: " . count($allItems));

                if ($currentPageCount < $this->pageLimit) {
                    $this->debugService->info("Page {$page} has only {$currentPageCount} items (less than limit {$this->pageLimit}), stopping");
                    break;
                }

                $page++;
                $consecutiveErrors = 0;

                usleep(500_000);

            } catch (\Throwable $e) {
                $this->debugService->error("Error fetching page {$page}: " . $e->getMessage());
                $consecutiveErrors++;

                if ($consecutiveErrors >= $this->maxConsecutiveErrors) {
                    $this->debugService->error("Too many consecutive errors ({$consecutiveErrors}), stopping");
                    break;
                }

                sleep(2);
                continue;
            }

        } while ($page <= $this->maxPages);

        $this->debugService->info("Finished fetching {$endpoint->value}. Total pages: {$page}, total items: " . count($allItems));
        return $allItems;
    }
}
