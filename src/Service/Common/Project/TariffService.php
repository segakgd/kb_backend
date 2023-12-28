<?php

namespace App\Service\Common\Project;


use App\Entity\User\Project;
use App\Repository\User\TariffRepository;
use Exception;

class TariffService implements TariffServiceInterface
{
    public const DEFAULT_TARIFF_CODE = 'TRIAL';

    public function __construct(
        private readonly TariffRepository $tariffRepository,
        private readonly ProjectSettingServiceInterface $projectSettingServiceInterface,
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

    /**
     * @throws Exception
     */
    public function applyTariff(Project $project, string $code): bool
    {
        $tariff = $this->tariffRepository->findOneBy(
            [
                'code' => $code,
                'active' => true,
            ]
        );

        if (!$tariff){
            throw new Exception('Тарифа не существует или он не активный');
        }

        $projectId = $project->getId();

        return $this->projectSettingServiceInterface->updateTariff($projectId, $tariff);
    }
}
