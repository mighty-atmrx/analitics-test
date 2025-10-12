<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Exceptions\ApiServiceAlreadyExistsException;
use App\Http\Services\ApiServiceService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateApiServiceCommand extends Command
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
        private readonly ApiServiceService $service
    ){
        parent::__construct();
    }

    /**
     * @throws ApiServiceAlreadyExistsException
     */
    public function handle(): int
    {
        $service = $this->service->create([
            'name' => $this->argument('name'),
            'code' => $this->argument('code'),
        ]);

        $this->info("Api service was created successfully! ID: {$service->id}, Name: {$service->name}");

        return CommandAlias::SUCCESS;
    }
}
