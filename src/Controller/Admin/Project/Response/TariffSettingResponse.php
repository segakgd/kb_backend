<?php

namespace App\Controller\Admin\Project\Response;

use App\Controller\AbstractResponse;
use App\Entity\User\Tariff;
use App\Service\Common\Project\Enum\TariffCodeEnum;
use Exception;

class TariffSettingResponse extends AbstractResponse
{
    public int $id;

    public string $name;

    public TariffCodeEnum $code;

    public int $price;

    public string $priceWF;

    public string $description;

    public bool $active;

    /**
     * @throws Exception
     */
    public static function mapFromEntity(object $entity): static
    {
        if (!$entity instanceof Tariff) {
            throw new Exception('Entity with undefined type.');
        }

        $response = new static();

        $response->id = $entity->getId();
        $response->name = $entity->getName();
        $response->price = $entity->getPrice();
        $response->priceWF = $entity->getPriceWF();
        $response->description = $entity->getDescription();
        $response->code = $entity->getCode();
        $response->active = $entity->isActive();

        return $response;
    }
}
