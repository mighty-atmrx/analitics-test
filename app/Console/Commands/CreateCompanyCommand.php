<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Exceptions\CompanyNameIsTakenException;
use App\Http\Services\CompanyService;

class CreateCompanyCommand extends BaseCreateCommand
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

    protected function getService(): CompanyService
    {
        return $this->companyService;
    }

    protected function getCreationData(): array
    {
        return [
            'name' => (string)$this->argument('name')
        ];
    }

    protected function getEntityType(): string
    {
        return 'Company';
    }

    protected function getSpecificExceptions(): array
    {
        return [
            CompanyNameIsTakenException::class,
        ];
    }
}
