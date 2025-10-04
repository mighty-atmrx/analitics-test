<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enum\SyncEndpointEnum;
use Exception;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;

class ApiClientService
{
    private string $token;
    private string $baseUrl;

    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
        $this->baseUrl = config('wb.url');
        $this->token = config('wb.key');
    }

    /**
     * @throws Exception
     */
    public function get(SyncEndpointEnum $endpoint, array $params = []): array
    {
        $params['key'] = $this->token;
        $url = "{$this->baseUrl}/api/{$endpoint->value}";

        $this->logger->info("Fetching: {$url}?" . http_build_query($params));

        try {
            $response = Http::withOptions([
                'decode_content' => false,
                'timeout' => 30,
            ])
                ->timeout(30)
                ->retry(3, 2000)
                ->get($url, $params);

            $this->logger->info("Response status: " . $response->status());
        } catch (Exception $e) {
            $this->logger->error("Http request failed: " . $e->getMessage());
            throw new Exception("Http get error: " . $e->getMessage());
        }

        if ($response->failed()) {
            $this->logger->error("API error (failed response): " . $response->body());
            throw new Exception("API error: " . $response->body());
        }

        $decoded = json_decode($response->body(), true, 512, JSON_BIGINT_AS_STRING);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->error("JSON decode error on {$url}: " . json_last_error_msg());
            $this->logger->error("Raw response: " . $response->body());
            throw new Exception("JSON decode error: " . json_last_error_msg());
        }

        if (!is_array($decoded)) {
            $this->logger->error("Unexpected response format: " . $response->body());
            throw new Exception("Unexpected response format from API");
        }

        return $decoded;
    }

    /**
     * @throws Exception
     */
    public function fetchData(SyncEndpointEnum $endpoint): array
    {
        $all = [];
        $page = 1;
        $limit = 500;
        $dateParams = $this->getDateParamsForEndpoint($endpoint);
        $from = $dateParams['from'];
        $to = $dateParams['to'];

        $consecutiveErrors = 0;
        $maxConsecutiveErrors = 3;

        $count = 0;
        $lastPage = 0;

        do {
            try {
                $this->logger->info("Fetching page {$page} for {$endpoint->value}");

                $response = $this->get($endpoint, [
                    'dateFrom' => $from,
                    'dateTo'   => $to,
                    'page'     => $page,
                    'limit'    => $limit,
                ]);

                $items = $response['data'] ?? [];
                $count = count($items);

                $lastPage = $response['meta']['last_page'];

                if ($count === 0) {
                    $this->logger->info("API returned empty page {$page}, stopping");
                    break;
                }

                $all = array_merge($all, $items);
                $this->logger->info("Loaded page {$page}, items: {$count}, total: " . count($all));

                if ($page === $lastPage) {
                    $this->logger->info("Reached last page {$page}" .
                        ($lastPage ? " (of {$lastPage})" : ""));
                    break;
                }

                $page++;
                $consecutiveErrors = 0;

                usleep(500_000);

            } catch (\Throwable $e) {
                $this->logger->error("Error fetching page {$page}: " . $e->getMessage());
                $consecutiveErrors++;

                if ($consecutiveErrors >= $maxConsecutiveErrors) {
                    $this->logger->error("Too many consecutive errors ({$consecutiveErrors}), stopping");
                    break;
                }

                sleep(2);
                continue;
            }

        } while ($count === $limit && $page <= $lastPage);

        $this->logger->info("Finished fetching {$endpoint->value}. Total pages: {$page}, total items: " . count($all));
        return $all;
    }

    private function getDateParamsForEndpoint(SyncEndpointEnum $endpoint): array
    {
        $today = date('Y-m-d');

        if ($endpoint->value === 'stocks') {
            return [
                'from' => $today,
                'to' => $today
            ];
        }

        return [
            'from' => '2000-01-01',
            'to' => $today
        ];
    }
}
