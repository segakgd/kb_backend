<?php

namespace App\Service\System\Resolver\Dto;

interface ConditionInterface
{
    public function getKeyBoard(): ?array;

    public function setKeyBoard(?array $keyBoard): void;
}
