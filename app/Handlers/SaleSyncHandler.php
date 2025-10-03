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

    /** @var SaleDto $dto */
    public function getUniqueBy(BaseDto $dto): array
    {
        return [
            'sale_id' => $dto->sale_id,
        ];
    }

    public function getValues(BaseDto $dto): array
    {
        return $dto->toArray();
    }
}
