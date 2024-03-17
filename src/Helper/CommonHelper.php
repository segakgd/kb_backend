<?php

namespace App\Helper;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Service\System\Contract;
use Exception;

class CommonHelper
{
    public static function createDefaultContract(): Contract
    {
        return (new Contract());
    }


    public static function createSessionCache(): CacheDto
    {
        return (new CacheDto);
    }

    /**
     * @throws Exception
     */
    public static function buildPaginate(int $page, int $maxPage): array
    {
        if ($maxPage < $page) {
            throw new Exception('max page < page');
        }

        $prevPage = ($page > 1) ? $page - 1 : null;
        $nextPage = ($page < $maxPage) ? $page + 1 : null;

        return [
            'prev' => $prevPage,
            'now' => $page,
            'next' => $nextPage,
            'total' => $maxPage,
        ];
    }

    public static function translate(string $key): string
    {
        return match ($key) {
            'show.shop.products.category' => 'show.shop.products.category',
            'shop.products.category' => 'shop.products.category',
            'shop.products' => 'shop.products',
            default => $key,
        };
    }
}
