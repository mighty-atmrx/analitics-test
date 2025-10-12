<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Exceptions\TokenTypeAlreadyExistsException;
use App\Http\Services\TokenTypeService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateTokenTypeCommand extends Command
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

    /**
     * @throws TokenTypeAlreadyExistsException
     */
    public function handle(): int
    {
        $tokenType = $this->service->create([
            'type' => $this->argument('type'),
            'code' => $this->argument('code'),
        ]);

        $this->info("Token type was created successfully! ID: {$tokenType->id}, Type: {$tokenType->type}");

        return CommandAlias::SUCCESS;
    }
}
