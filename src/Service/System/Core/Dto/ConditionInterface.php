<?php

namespace App\Service\System\Core\Dto;

interface ConditionInterface
{
    public function getKeyBoard(): ?array;

    public function setKeyBoard(?array $keyBoard): void;
}
