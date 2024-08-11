<?php

namespace App\Doctrine;

interface DoctrineMappingInterface
{
    public function toArray(): array;

    public static function fromArray(array $data): static;
}