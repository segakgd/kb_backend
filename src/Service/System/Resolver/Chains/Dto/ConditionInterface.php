<?php

namespace App\Service\System\Resolver\Chains\Dto;

interface ConditionInterface
{
    public function getKeyBoard(): ?array;

    public function setKeyBoard(?array $keyBoard): void;
}
