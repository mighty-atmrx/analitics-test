<?php

namespace App\Http\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiClientService
{
    private string $token;
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('wb.url');
        $this->token = config('wb.key');
    }

    /**
     * @throws Exception
     */
    public function get(string $endpoint, array $params = []): array
    {
        $params['key'] = $this->token;
        $url = "{$this->baseUrl}/api/{$endpoint}";

        Log::info("Fetching: {$url}?" . http_build_query($params));

        try {
            $response = Http::withOptions([
                'decode_content' => false,
                'timeout' => 30, // увеличиваем таймаут
            ])
                ->timeout(30)
                ->retry(3, 2000) // увеличиваем паузу между ретраями
                ->get($url, $params);

            Log::info("Response status: " . $response->status());

        } catch (Exception $e) {
            Log::error("Http request failed: " . $e->getMessage());
            throw new Exception("Http get error: " . $e->getMessage());
        }

        if ($response->failed()) {
            Log::error("API error (failed response): " . $response->body());
            throw new Exception("API error: " . $response->body());
        }

        $decoded = json_decode($response->body(), true, 512, JSON_BIGINT_AS_STRING);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("JSON decode error on {$url}: " . json_last_error_msg());
            Log::error("Raw response: " . $response->body());
            throw new Exception("JSON decode error: " . json_last_error_msg());
        }

        if (!is_array($decoded)) {
            Log::error("Unexpected response format: " . $response->body());
            throw new Exception("Unexpected response format from API");
        }

        return $decoded;
    }

    /**
     * @throws Exception
     */
    public function fetchData(string $endpoint): array
    {
        $all = [];
        $page = 1;
        $limit = 500;
        $from = '2000-01-01';
        $to = (new \DateTimeImmutable())->format('Y-m-d');
        $maxPages = 1500;
        $consecutiveErrors = 0;
        $maxConsecutiveErrors = 3;

        do {
            try {
                Log::info("Fetching page {$page} for {$endpoint}");

                $response = $this->get($endpoint, [
                    'dateFrom' => $from,
                    'dateTo'   => $to,
                    'page'     => $page,
                    'limit'    => $limit,
                ]);

                $items = $response['data'] ?? [];
                $count = count($items);

                if ($count === 0) {
                    Log::info("API returned empty page {$page}, stopping");
                    break;
                }

                $all = array_merge($all, $items);
                Log::info("Loaded page {$page}, items: {$count}, total: " . count($all));

                $page++;
                $consecutiveErrors = 0;

                usleep(500_000);

            } catch (\Throwable $e) {
                Log::error("Error fetching page {$page}: " . $e->getMessage());
                $consecutiveErrors++;

                if ($consecutiveErrors >= $maxConsecutiveErrors) {
                    Log::error("Too many consecutive errors ({$consecutiveErrors}), stopping");
                    break;
                }

                sleep(2);
                continue;
            }

        } while ($count === $limit && $page <= $maxPages);

        Log::info("Finished fetching {$endpoint}. Total pages: {$page}, total items: " . count($all));
        return $all;
    }
}
