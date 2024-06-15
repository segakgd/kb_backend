<?php

namespace App\Service\Constructor\Core\Dto;

interface ConditionInterface
{
    public function getKeyBoard(): ?array;

    public function setKeyBoard(?array $keyBoard): void;
}
