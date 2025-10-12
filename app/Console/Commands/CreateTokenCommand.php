<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Exceptions\LoginPasswordRequiredException;
use App\Http\Exceptions\ServiceNotSupportTokenException;
use App\Http\Requests\Token\StoreRequest;
use App\Http\Services\TokenService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Random\RandomException;

class CreateTokenCommand extends BaseCreateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:token
                            {account_id : account ID}
                            {api_service_id : service ID}
                            {token_type_id : token type ID}
                            {login? : login}
                            {password? : password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new token';

    public function __construct(
        private readonly TokenService $service
    ) {
        parent::__construct();
    }

    protected function getService(): TokenService
    {
        return $this->service;
    }

    protected function getCreationData(): array
    {
        return [
            'account_id' => (int) $this->argument('account_id'),
            'api_service_id' => (int) $this->argument('api_service_id'),
            'token_type_id' => (int) $this->argument('token_type_id'),
            'login' => (string) $this->argument('login'),
            'password' => (string) $this->argument('password'),
        ];
    }

    protected function getEntityType(): string
    {
        return 'Token';
    }

    protected function getSpecificExceptions(): array
    {
        return [
            ServiceNotSupportTokenException::class,
            RandomException::class,
            ValidationException::class,
            LoginPasswordRequiredException::class
        ];
    }

    /**
     * @throws ValidationException
     */
    protected function validateData(array $data): void
    {
        $rules = (new StoreRequest())->rules();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
