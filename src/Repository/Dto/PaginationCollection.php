<?php

namespace App\Repository\Dto;

class PaginationCollection
{
    private array $items;
    private PaginateDto $paginate;

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): static
    {
        $this->items = $items;

        return $this;
    }

    public function getPaginate(): PaginateDto
    {
        return $this->paginate;
    }

    public function setPaginate(PaginateDto $paginate): static
    {
        $this->paginate = $paginate;

        return $this;
    }
}
