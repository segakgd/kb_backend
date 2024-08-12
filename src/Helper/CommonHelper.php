<?php

namespace App\Helper;

use App\Entity\Visitor\Session;
use App\Service\Constructor\Core\Dto\BotDto;
use App\Service\Constructor\Core\Dto\Responsible;
use Exception;

class CommonHelper
{
    public static function createDefaultResponsible(Session $session): Responsible
    {
        $sessionCache = $session->getCache();

        return (new Responsible())
            ->setEvent($sessionCache->getEvent())
            ->setCart($sessionCache->getCart())
            ->setContent($sessionCache->getContent())
            ->setBot(
                bot: static::createBotBto($session)
            );
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

    private static function createBotBto(Session $visitorSession): BotDto
    {
        $bot = $visitorSession->getBot();

        return (new BotDto())
            ->setType($bot->getType())
            ->setToken($bot->getToken())
            ->setChatId($visitorSession->getChatId());
    }
}
