<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Exceptions\ApiServiceTokenTypeAlreadyExistsException;
use App\Repositories\ApiServiceTokenTypeRepository;

class CreateApiServiceTokenTypeCommand extends BaseCreateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:api-service-token-type
                                {api_service_id : ApiService ID}
                                {token_type_id : TokenType ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new api service token type';

    public function __construct(
        private readonly ApiServiceTokenTypeRepository $repository
    ) {
        parent::__construct();
    }

    protected function getService(): ApiServiceTokenTypeRepository
    {
        return $this->repository;
    }

    protected function getCreationData(): array
    {
        return [
            'api_service_id' => (int)$this->argument('api_service_id'),
            'token_type_id' => (int)$this->argument('token_type_id'),
        ];
    }

    protected function getEntityType(): string
    {
        return 'ApiServiceTokenType';
    }

    protected function getSpecificExceptions(): array
    {
        return [
            ApiServiceTokenTypeAlreadyExistsException::class
        ];
    }
}
