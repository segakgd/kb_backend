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
            'show.shop.products.category' => 'Приветственное сообщение, показываем доступные категории',
            'shop.products.category' => 'Показываем товары по выбранной категории',
            'shop.products' => 'Товары по выбранной катигории',
            'shop.product' => 'Просмотр конкретного продукта',
            default => $key,
        };
    }
}
