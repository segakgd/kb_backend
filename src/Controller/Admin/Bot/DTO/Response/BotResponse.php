<?php

namespace App\Controller\Admin\Bot\DTO\Response;

use App\Entity\User\Bot;

class BotResponse
{
    public int $id;

    public string $name;

    public string $type;

    public static function mapFromEntity(Bot $bot): static
    {
        $response = new static();

        $response->id = $bot->getId();
        $response->name = $bot->getName();
        $response->type = $bot->getType()->value;

        return $response;
    }
}
