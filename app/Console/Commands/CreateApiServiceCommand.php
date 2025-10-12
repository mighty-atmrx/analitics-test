<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Services\ApiServiceManager;
use InvalidArgumentException;

class CreateApiServiceCommand extends BaseCreateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:api-service
                            {name : Api service name}
                            {code : Api service code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new api service';

    public function __construct(
        private readonly ApiServiceManager $service
    ){
        parent::__construct();
    }

    protected function getService(): ApiServiceManager
    {
        return $this->service;
    }

    protected function getCreationData(): array
    {
        return [
            'name' => (string) $this->argument('name'),
            'code' => (string) $this->argument('code'),
        ];
    }

    protected function getEntityType(): string
    {
        return 'ApiService';
    }

    protected function getSpecificExceptions(): array
    {
        return [
            InvalidArgumentException::class
        ];
    }
}
