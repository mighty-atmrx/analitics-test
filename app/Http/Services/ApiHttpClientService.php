<?php

namespace App\Http\Services;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

readonly class ApiHttpClientService
{
    public function __construct(
        private DebugService $debugService,
        private string $baseUrl,
        private string $token,
        private int $timeout = 30,
        private int $retries = 3
    ) {}

    /**
     * @throws Exception
     */
    public function get(string $endpoint, array $params = []): array
    {
        $url = "{$this->baseUrl}/api/{$endpoint}";
        $params['key'] = $this->token;

        $this->debugService->info("Fetching: {$url}?" . http_build_query($params));

        try {
            $response = $this->createHttpClient()->get($url, $params);

            if ($response->status() === Response::HTTP_TOO_MANY_REQUESTS) {
                $response = $this->handleTooManyRequests($response, $url, $params);
            }

            $this->debugService->info("Response status: " . $response->status());

            if ($response->failed()) {
                $this->handlerFailedResponse($response, $url);
            }

            return $this->decodeResponse($response, $url);

        } catch (Exception $e) {
            $this->debugService->error("Http request failed: " . $e->getMessage());
            throw new Exception("Http get error: " . $e->getMessage());
        }
    }

    private function createHttpClient(): PendingRequest
    {
        return Http::withOptions([
            'decode_content' => false,
            'timeout' => $this->timeout,
        ])
            ->timeout($this->timeout)
            ->retry($this->retries, 2000, function ($exception) {
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
}
