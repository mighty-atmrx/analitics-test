<?php

declare(strict_types=1);

namespace App\Http\Services;

use Psr\Log\LoggerInterface;

class DebugService
{
    private ?\Closure $afterWriteCallback = null;

    public function __construct(
        private readonly LoggerInterface $logger
    ){
    }

    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
        if (PHP_SAPI === 'cli') {
            $this->writeLine("[INFO] {$message}");
        }
    }

    public function error(string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
        if (PHP_SAPI === 'cli') {
            $this->writeLine("[ERROR] {$message}");
        }
    }

    public function warning(string $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
        if (PHP_SAPI === 'cli') {
            $this->writeLine("[WARNING] {$message}");
        }
    }

    public function debug(string $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
        if (PHP_SAPI === 'cli') {
            $this->writeLine("[DEBUG] {$message}");
        }
    }

    public function progress(string $message): void
    {
        $this->writeLine("- {$message}");
    }

    private function writeLine(string $text): void
    {
        echo $text . PHP_EOL;

        if ($this->afterWriteCallback) {
            ($this->afterWriteCallback)();
        }
    }
}
