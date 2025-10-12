<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Exceptions\CompanyNameIsTakenException;
use App\Http\Services\CompanyService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateCompanyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "make:company {name : Company name}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new company';

    public function __construct(
        private readonly CompanyService $companyService
    ){
        parent::__construct();
    }

    /**
     * @throws CompanyNameIsTakenException
     */
    public function handle(): int
    {
        $name = $this->argument('name');

        $company = $this->companyService->create($name);

        $this->info("Company was created successfully! ID: {$company->id}, Name: {$company->name}");

        return CommandAlias::SUCCESS;
    }
}
