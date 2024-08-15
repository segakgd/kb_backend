<?php

namespace App\Controller\Admin\Scenario\Response;

use App\Controller\AbstractResponse;
use App\Entity\Scenario\Scenario;
use Exception;

class ScenarioResponse extends AbstractResponse
{
    public ?int $id;

    public string $name;

    /**
     * @throws Exception
     */
    public static function mapFromEntity(object $entity): static
    {
        if (!$entity instanceof Scenario) {
            throw new Exception('Entity with undefined type.');
        }

        $response = new static();

        $response->id = $entity->getId();
        $response->name = $entity->getName();

        return $response;
    }
}
