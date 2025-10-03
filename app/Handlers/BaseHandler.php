<?php

namespace App\Handlers;

use App\Dto\BaseDto;

interface BaseHandler
{
    public function getModelClass(): string;

    // unique fields for updateOrCreate
    public function getUniqueBy(BaseDto $dto): array;

    // mapping other fields for updateOrCreate
    public function getValues(BaseDto $dto): array;
}
