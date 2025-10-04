<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\SyncEndpointEnum;

interface BaseDto
{
    public function toArray(): array;

    public static function fromArray(array $item): self;
}
