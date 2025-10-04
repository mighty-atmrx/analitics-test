<?php

namespace App\Handlers;

use App\Dto\BaseDto;

interface BaseHandler
{
    public function getModelClass(): string;

    // mapping other fields for updateOrCreate
    public function getValues(BaseDto $dto): array;
}
