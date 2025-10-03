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

    /** @var StockDto $dto */
    public function getUniqueBy(BaseDto $dto): array
    {
        return [
            'barcode'        => $dto->barcode,
            'warehouse_name' => $dto->warehouse_name,
        ];
    }

    public function getValues(BaseDto $dto): array
    {
        return $dto->toArray();
    }
}
