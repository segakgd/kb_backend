<?php

namespace App\Controller\Admin\Bot\Request;

class BotSearchRequest
{
    public function __construct(
        private ?int $page = null,
        private ?string $active = null,
    ) {}

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(null|int|string $page): static
    {
        if (is_string($page)) {
            $page = (int) $page;
        }

        $this->page = $page;

        return $this;
    }

    public function getActive(): ?string
    {
        return $this->active;
    }

    public function setActive(?string $active): static
    {
        $this->active = $active;

        return $this;
    }
}
