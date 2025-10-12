<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command as CommandAlias;

abstract class BaseCreateCommand extends Command
{
    abstract protected function getService();
    abstract protected function getCreationData(): array;
    abstract protected function getEntityType(): string;
    abstract protected function getSpecificExceptions(): array;

    public function handle(): int
    {
        try {
            $data = $this->getCreationData();
            $this->validateData($data);

            $entity = $this->getService()->create($data);

            $this->info($this->getSuccessMessage($entity));
            return CommandAlias::SUCCESS;

        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    protected function getSuccessMessage($entity): string
    {
        $entityType = $this->getEntityType();
        return "{$entityType} was created successfully! ID: {$entity->id}";
    }

    protected function handleException(Exception $e): int
    {
        $exceptions = $this->getSpecificExceptions();

        foreach ($exceptions as $exceptionClass) {
            if ($e instanceof $exceptionClass) {
                $this->error($e->getMessage());
                return CommandAlias::FAILURE;
            }
        }

        $this->error("Unexpected error: {$e->getMessage()}");
        return CommandAlias::FAILURE;
    }

    protected function validateData(array $data): void
    {
        if (empty($data)) {
            throw new InvalidArgumentException('No data provided for creation');
        }

        $this->info("Creating {$this->getEntityType()} with data: " . json_encode($data));
    }
}
