<?php

namespace App\Controller\Admin\Project\Response\Tariff;

use App\Controller\Admin\Project\DTO\Response\TariffSettingRespDto;
use App\Entity\User\Tariff;

class ViewAllTariffResponse
{
    public function mapResponse(array $tariffs): array
    {
        $result = [];

        /** @var Tariff $tariff */
        foreach ($tariffs as $tariff) {
            $result[] = (new TariffSettingRespDto())
                ->setId($tariff->getId())
                ->setName($tariff->getName())
                ->setPrice($tariff->getPrice())
                ->setPriceWF($tariff->getPriceWF())
                ->setDescription($tariff->getDescription())
                ->setCode($tariff->getCode())
                ->setActive($tariff->isActive());
        }

        return $result;
    }
}