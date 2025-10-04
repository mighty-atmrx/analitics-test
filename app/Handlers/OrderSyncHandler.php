<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Dto\BaseDto;
use App\Enum\SyncEndpointEnum;

class OrderSyncHandler implements BaseHandler
{

    public function getModelClass(): string
    {
        return \App\Models\Order::class;
    }

    public function getValues(BaseDto $dto): array
    {
        return $dto->toArray();
    }

    public function supports(SyncEndpointEnum $endpoint): bool
    {
        return $endpoint === SyncEndpointEnum::ORDERS;
    }
}
