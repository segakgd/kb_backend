<?php

namespace App\Repository;

use App\Repository\Dto\PaginateDto;
use App\Repository\Dto\PaginationCollection;

trait PaginationTrait
{
    protected static function makePaginate(array $items, int $page, int $limit, ?int $totalItems): PaginationCollection
    {
        $collection = new PaginationCollection();

        $totalItems = $totalItems ?? count($items);
        $totalPages = ceil($totalItems / $limit);
        $lastPage = $page === 1 ? null : $page - 1;
        $nextPage = $totalPages < $page + 1 ? null : $page + 1;

        $paginate = (new PaginateDto())
            ->setCurrentPage($page)
            ->setLastPage($lastPage)
            ->setNextPage($nextPage)
            ->setTotalItems($totalItems)
            ->setTotalPages($totalPages);

        $collection->setItems($items);
        $collection->setPaginate($paginate);

        return $collection;
    }
}