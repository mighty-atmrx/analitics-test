<?php

namespace App\Handlers;

use App\Dto\BaseDto;
use App\Dto\StockDto;

class StockSyncHandler implements BaseHandler
{

    public function getModelClass(): string
    {
        return \App\Models\Stock::class;
    }
    public function getValues(BaseDto $dto): array
    {
        return $dto->toArray();
    }
}
