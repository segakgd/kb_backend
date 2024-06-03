<?php

namespace App\Dto\Common;

abstract class AbstractDto
{
    public abstract static function fromArray(array $data): self;

    public abstract function toArray(): array;
}
