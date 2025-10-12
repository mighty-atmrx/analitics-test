<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Exceptions\LoginPasswordRequiredException;
use App\Http\Exceptions\ServiceNotSupportTokenException;
use App\Http\Requests\Token\StoreRequest;
use App\Http\Services\TokenService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Random\RandomException;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateTokenCommand extends Command
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

    /**
     * @throws ServiceNotSupportTokenException
     * @throws RandomException
     * @throws ValidationException
     * @throws LoginPasswordRequiredException
     */
    public function handle(): int
    {
        $data = [
            'account_id' => $this->argument('account_id'),
            'api_service_id' => $this->argument('api_service_id'),
            'token_type_id' => $this->argument('token_type_id'),
            'login' => $this->argument('login'),
            'password' => $this->argument('password'),
        ];

        $rules = (new StoreRequest())->rules();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $this->error(json_encode($validator->errors()->all()));
            return CommandAlias::FAILURE;
        }

        $validated = $validator->validated();

        $this->service->create($validated);

        $this->info("Токен успешно создан!");

        return CommandAlias::SUCCESS;
    }
}
