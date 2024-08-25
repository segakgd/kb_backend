<?php

namespace App\Repository\Dto;

class PaginateCollection
{
    private array $items;
    private int $currentPage;
    private int $lastPage;
    private int $nextPage;
    private int $totalPages;
    private int $totalItems;

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): static
    {
        $this->items = $items;

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): static
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    public function setLastPage(int $lastPage): static
    {
        $this->lastPage = $lastPage;

        return $this;
    }

    public function getNextPage(): int
    {
        return $this->nextPage;
    }

    public function setNextPage(int $nextPage): static
    {
        $this->nextPage = $nextPage;

        return $this;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function setTotalItems(int $totalItems): static
    {
        $this->totalItems = $totalItems;

        return $this;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function setTotalPages(int $totalPages): static
    {
        $this->totalPages = $totalPages;

        return $this;
    }
}