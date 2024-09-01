<?php

namespace App\Controller\Admin\Bot\Request;

class BotSearchRequest
{
    public function __construct(
        private ?int $page = null,
        private ?string $status = null,
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
