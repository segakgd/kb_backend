<?php

namespace App\Service\Common\Project;


use App\Repository\User\TariffRepository;

class TariffService implements TariffServiceInterface
{
    public function __construct(
        private readonly TariffRepository $tariffRepository,
    ) {
    }

    public function getAllTariff(): array
    {
        return $this->tariffRepository->findBy(
            [
                'active' => true,
            ]
        );
    }
}
