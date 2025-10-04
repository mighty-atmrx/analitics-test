<?php

namespace App\Handlers;

use App\Dto\BaseDto;
use App\Dto\IncomeDto;

class IncomeSyncHandler implements BaseHandler
{
    public function getModelClass(): string
    {
        return \App\Models\Income::class;
    }

    public function getValues(BaseDto $dto): array
    {
        return $dto->toArray();
    }
}
