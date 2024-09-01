<?php

namespace App\Controller\Admin\ScenarioTemplate\Response;

use App\Controller\AbstractResponse;
use App\Entity\Scenario\ScenarioTemplate;
use Exception;

class ScenarioTemplateResponse extends AbstractResponse
{
    public int $id;

    public string $name;

    public bool $active;

    public string $template;

    public string $createdAt;

    public string $updatedAt;

    /**
     * @throws Exception
     */
    public static function mapFromEntity(object $entity): static
    {
        if (!$entity instanceof ScenarioTemplate) {
            throw new Exception('Entity with undefined type.');
        }

        $response = new static();

        $response->id = $entity->getId();
        $response->name = $entity->getName();
        $response->active = true;
        $response->template = 'Пользовательский';
        $response->createdAt = '2024-10-23';
        $response->updatedAt = '2024-10-23';

        return $response;
    }
}
