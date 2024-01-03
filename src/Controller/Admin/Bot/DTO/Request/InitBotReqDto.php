<?php

namespace App\Controller\Admin\Bot\DTO\Request;

class InitBotReqDto
{
    protected bool $active;

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
