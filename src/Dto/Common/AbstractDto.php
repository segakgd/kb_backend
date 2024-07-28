<?php

namespace App\Dto\Common;

abstract class AbstractDto
{
    abstract public static function fromArray(array $data): self;

    abstract public function toArray(): array;
}
