<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Exceptions\AccountAlreadyExistsException;
use App\Http\Services\AccountService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateAccountCommand extends Command
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

    /**
     * @throws AccountAlreadyExistsException
     */
    public function handle(): int
    {
        $account = $this->service->create([
            'company_id' => $this->argument('company_id'),
            'name' => $this->argument('name'),
            'description' => $this->argument('description') ?? null,
        ]);

        $this->info("Account was created successfully! ID: {$account->id}, Name: {$account->name}");

        return CommandAlias::SUCCESS;
    }
}
