<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Exceptions\ApiServiceTokenTypeAlreadyExistsException;
use App\Repositories\ApiServiceTokenTypeRepository;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateApiServiceTokenTypeCommand extends Command
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

    /**
     * Execute the console command.
     * @throws ApiServiceTokenTypeAlreadyExistsException
     */
    public function handle(): int
    {
        $apiServiceTokenType = $this->repository->create([
            'api_service_id' => $this->argument('api_service_id'),
            'token_type_id' => $this->argument('token_type_id'),
        ]);

        $this->info("Api service token type created");
        return CommandAlias::SUCCESS;
    }
}
