<?php

namespace App\Controller\Admin\Bot\Response;

use App\Controller\Admin\Bot\DTO\Response\BotResDto;
use App\Entity\User\Bot;

class BotViewOneResponse
{
    public function mapToResponse(Bot $bot): BotResDto
    {
        return (new BotResDto())
            ->setId($bot->getId())
            ->setName($bot->getName())
            ->setType($bot->getType()->value);
    }
}
