<?php

namespace App\Dto;

interface BaseDto
{
    public function toArray(): array;

    public static function fromArray(array $item): self;
}
