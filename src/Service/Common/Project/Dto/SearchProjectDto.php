<?php

namespace App\Service\Common\Project\Dto;

class SearchProjectDto
{
    public function __construct(
        private string $status,
        private int $page,
    ) {}

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): static
    {
        $this->page = $page;
        return $this;
    }
}
