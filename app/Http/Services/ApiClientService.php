<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enum\SyncEndpointEnum;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class ApiClientService
{
    private string $token;
    private string $baseUrl;

    public function __construct(
        private readonly DebugService $debugService,
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

        $this->debugService->info("Fetching: {$url}?" . http_build_query($params));

        try {
            $response = $this->createHttpClient()->get($url, $params);

            if ($response->status() === Response::HTTP_TOO_MANY_REQUESTS) {
                $response = $this->handleTooManyRequests($response, $url, $params);
            }

            $this->debugService->info("Response status: " . $response->status());
        } catch (Exception $e) {
            $this->debugService->error("Http request failed: " . $e->getMessage());
            throw new Exception("Http get error: " . $e->getMessage());
        }

        if ($response->failed()) {
            $this->handlerFailedResponse($response, $url);
        }

        return $this->decodeResponse($response, $url);
    }

    private function createHttpClient(): PendingRequest
    {
        return Http::withOptions([
            'decode_content' => false,
            'timeout' => 30,
        ])
            ->timeout(30)
            ->retry(3, 2000, function ($exception) {
                return $exception instanceof \Illuminate\Http\Client\ConnectionException;
            });
    }

    /**
     * @throws Exception
     */
    private function handlerFailedResponse(\Illuminate\Http\Client\Response $response, string $url): void
    {
        $status = $response->status();
        $body = $response->body();

        if ($status === Response::HTTP_FORBIDDEN) {
            throw new Exception("Access forbidden for {$url}. Check API key.");
        } else if ($status === Response::HTTP_INTERNAL_SERVER_ERROR) {
            throw new Exception("Server error ({$status}) for url {$url}");
        } else {
            throw new Exception("API error ({$status}) for url {$url}. Body: {$body}");
        }
    }

    /**
     * @throws Exception
     */
    private function handleTooManyRequests(\Illuminate\Http\Client\Response $response, string $url, array $params): \Illuminate\Http\Client\Response
    {
        $retryAfter = $response->header('Retry-After') ?? 60;
        $this->debugService->warning("Too many requests, retrying after {$retryAfter} seconds");
        sleep($retryAfter);

        return $this->createHttpClient()->get($url, $params);
    }

    /**
     * @throws Exception
     */
    private function decodeResponse(\Illuminate\Http\Client\Response $response, string $url): array
    {
        $decoded = json_decode($response->body(), true, 512, JSON_BIGINT_AS_STRING);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->debugService->error("JSON decode error on {$url}: " . json_last_error_msg());
            $this->debugService->error("Raw response: " . $response->body());
            throw new Exception("JSON decode error: " . json_last_error_msg());
        }

        if (!is_array($decoded)) {
            $this->debugService->error("Unexpected response format: " . $response->body());
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

        $consecutiveErrors = 0;
        $maxConsecutiveErrors = 3;

        $count = 0;
        $lastPage = 0;

        do {
            try {
                $this->debugService->info("Fetching page {$page} for {$endpoint->value}");

                $response = $this->get($endpoint, [
                    'dateFrom' => $dateParams['from'],
                    'dateTo'   => $dateParams['to'],
                    'page'     => $page,
                    'limit'    => $limit,
                ]);

                $items = $response['data'] ?? [];
                $count = count($items);
                $lastPage = $response['meta']['last_page'];

                if ($count === 0) {
                    $this->debugService->info("API returned empty page {$page}, stopping");
                    break;
                }

                $all = array_merge($all, $items);
                $this->debugService->info("Loaded page {$page}, items: {$count}, total: " . count($all));

                if ($page === $lastPage) {
                    $this->debugService->info("Reached last page {$page}" .
                        ($lastPage ? " (of {$lastPage})" : ""));
                    break;
                }

                $page++;
                $consecutiveErrors = 0;

                usleep(500_000);

            } catch (\Throwable $e) {
                $this->debugService->error("Error fetching page {$page}: " . $e->getMessage());
                $consecutiveErrors++;

                if ($consecutiveErrors >= $maxConsecutiveErrors) {
                    $this->debugService->error("Too many consecutive errors ({$consecutiveErrors}), stopping");
                    break;
                }

                sleep(2);
                continue;
            }

        } while ($count === $limit && $page <= $lastPage);

        $this->debugService->info("Finished fetching {$endpoint->value}. Total pages: {$page}, total items: " . count($all));
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
