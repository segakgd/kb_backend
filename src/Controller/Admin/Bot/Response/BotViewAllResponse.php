<?php

namespace App\Controller\Admin\Bot\Response;

use App\Controller\Admin\Bot\DTO\Response\BotResDto;
use App\Entity\User\Bot;

class BotViewAllResponse
{
    public function mapToResponse(array $bots): array
    {
        $result = [];

        /** @var Bot $bot */
        foreach ($bots as $bot) {
            $result[] = (new BotResDto())
                ->setId($bot->getId())
                ->setName($bot->getName())
                ->setType($bot->getType());
        }

        return $result;
    }
}