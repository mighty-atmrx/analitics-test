<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Exceptions\TokenTypeAlreadyExistsException;
use App\Http\Services\TokenTypeService;

class CreateTokenTypeCommand extends BaseCreateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:token-type
                            {type : Type title}
                            {code : Type code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new token type';

    public function __construct(
        private readonly TokenTypeService $service
    ){
        parent::__construct();
    }

    protected function getService(): TokenTypeService
    {
        return $this->service;
    }

    protected function getCreationData(): array
    {
        return [
            'type' => (string)$this->argument('type'),
            'code' => (string)$this->argument('code'),
        ];
    }

    protected function getEntityType(): string
    {
        return 'TokenType';
    }

    protected function getSpecificExceptions(): array
    {
        return [
            TokenTypeAlreadyExistsException::class
        ];
    }
}
