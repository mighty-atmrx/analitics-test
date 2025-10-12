<?php

namespace App\Http\Services;

abstract class BaseCreateService
{
    abstract protected function getRepository();
    abstract protected function getExistsExceptionClass(): string;

    public function create(array $data)
    {
        if ($this->getRepository()->exists($data)) {
            $exceptionClass = $this->getExistsExceptionClass();
            throw new $exceptionClass();
        }

        return $this->getRepository()->create($data);
    }
}
