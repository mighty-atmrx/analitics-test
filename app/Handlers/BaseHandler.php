<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Dto\BaseDto;
use App\Enum\SyncEndpointEnum;

interface BaseHandler
{
    public function getModelClass(): string;

    // mapping other fields for updateOrCreate
    public function getValues(BaseDto $dto): array;

    // get needed handler for endpoint
    public function supports(SyncEndpointEnum $endpoint): bool;
}
