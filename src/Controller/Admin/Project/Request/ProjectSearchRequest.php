<?php

namespace App\Controller\Admin\Project\Request;

class ProjectSearchRequest
{
    public function __construct(
        private int $page,
        private ?string $status = null,
    ) {}

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): static
    {
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
