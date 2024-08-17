<?php

namespace App\Controller\Admin\Project\Response;

use App\Controller\AbstractResponse;
use App\Entity\User\Tariff;
use Exception;

class ProjectTariffSettingRespDto extends AbstractResponse
{
    public string $name;

    public int $price;

    public string $priceWF;

    /**
     * @throws Exception
     */
    public static function mapFromEntity(object $entity): static
    {
        if (!$entity instanceof Tariff) {
            throw new Exception('Entity with undefined type.');
        }

        $response = new static();

        $response->name = $entity->getName();
        $response->price = $entity->getPrice();
        $response->priceWF = $entity->getPriceWF();

        return $response;
    }
}
