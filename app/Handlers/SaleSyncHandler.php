<?php

namespace App\Handlers;

use App\Dto\BaseDto;
use App\Dto\SaleDto;

class SaleSyncHandler implements BaseHandler
{

    public function getModelClass(): string
    {
        return \App\Models\Sale::class;
    }

    public function getValues(BaseDto $dto): array
    {
        return $dto->toArray();
    }
}
