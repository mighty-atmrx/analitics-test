<?php

namespace App\Handlers;

use App\Dto\BaseDto;
use App\Dto\OrderDto;

class OrderSyncHandler implements BaseHandler
{

    public function getModelClass(): string
    {
        return \App\Models\Order::class;
    }

    /** @var OrderDto $dto */
    public function getUniqueBy(BaseDto $dto): array
    {
        return [
            'odid' => $dto->odid,
        ];
    }

    public function getValues(BaseDto $dto): array
    {
        return $dto->toArray();
    }
}
