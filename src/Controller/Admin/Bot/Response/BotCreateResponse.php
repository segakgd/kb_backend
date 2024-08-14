<?php

namespace App\Controller\Admin\Bot\Response;

use App\Controller\Admin\Bot\DTO\Response\BotResponse;
use App\Entity\User\Bot;

class BotCreateResponse
{
    public function mapToResponse(Bot $bot): BotResponse
    {
        return (new BotResponse())
            ->setId($bot->getId())
            ->setName($bot->getName())
            ->setType($bot->getType()->value);
    }
}
