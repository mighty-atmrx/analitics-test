<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Exceptions\AccountAlreadyExistsException;
use App\Http\Services\AccountService;
use InvalidArgumentException;

class CreateAccountCommand extends BaseCreateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:account
                            {company_id : company ID}
                            {name : Account name}
                            {description? : Account description}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new account';

    public function __construct(
        private readonly AccountService $service
    ){
        parent::__construct();
    }

    protected function getService(): AccountService
    {
        return $this->service;
    }

    protected function getCreationData(): array
    {
        return [
            'company_id' => (int) $this->argument('company_id'),
            'name' => (string) $this->argument('name'),
            'description' => (string) $this->argument('description') ?? null,
        ];
    }

    protected function getEntityType(): string
    {
        return 'Account';
    }

    protected function getSpecificExceptions(): array
    {
        return [
            AccountAlreadyExistsException::class,
            InvalidArgumentException::class,
        ];
    }
}
