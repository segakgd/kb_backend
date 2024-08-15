<?php

namespace App\Controller\Admin\Bot\Response;

use App\Controller\AbstractResponse;
use App\Entity\User\Bot;
use Exception;

class BotResponse extends AbstractResponse
{
    public int $id;

    public string $name;

    public string $type;

    /**
     * @throws Exception
     */
    public static function mapFromEntity(object $entity): static
    {
        if (!$entity instanceof Bot) {
            throw new Exception('Entity with undefined type.');
        }

        $response = new static();

        $response->id = $entity->getId();
        $response->name = $entity->getName();
        $response->type = $entity->getType()->value;

        return $response;
    }
}
