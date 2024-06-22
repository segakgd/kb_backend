<?php

namespace App\Helper;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\TargetEnum;
use App\Service\Constructor\Core\Dto\Responsible;
use Exception;

class CommonHelper
{
    public static function createDefaultResponsible(): Responsible
    {
        return new Responsible();
    }

    public static function createSessionCache(): CacheDto
    {
        return new CacheDto();
    }

    /**
     * @throws Exception
     */
    public static function buildPaginate(int $page, int $maxPage): array
    {
        if ($maxPage < $page) {
            throw new Exception('max page < page');
        }

        $prevPage = ($page > 1) ? $page - 1 : $maxPage;
        $nextPage = ($page < $maxPage) ? $page + 1 : 1;

        return [
            'prev'  => $prevPage,
            'now'   => $page,
            'next'  => $nextPage,
            'total' => $maxPage,
        ];
    }
}
