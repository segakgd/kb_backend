<?php

namespace App\Service\System\Resolver\Dto;

class Condition implements ConditionInterface
{
    public ?array $keyBoard = null;

    public function getKeyBoard(): ?array
    {
        return $this->keyBoard;
    }

    public function setKeyBoard(?array $keyBoard): void
    {
        $this->keyBoard = $keyBoard;
    }
}
