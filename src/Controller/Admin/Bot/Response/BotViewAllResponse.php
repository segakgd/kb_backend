<?php

namespace App\Controller\Admin\Bot\Response;

use App\Controller\Admin\Bot\DTO\Response\BotResponse;
use App\Entity\User\Bot;

class BotViewAllResponse
{
    public function mapToResponse(array $bots): array
    {
        $result = [];

        /** @var Bot $bot */
        foreach ($bots as $bot) {
            $result[] = (new BotResponse())
                ->setId($bot->getId())
                ->setName($bot->getName())
                ->setType($bot->getType()->value);
        }

        return $result;
    }
}