<?php

namespace App\Service\Common\Project;


use App\Entity\User\Project;
use App\Entity\User\Tariff;
use App\Repository\User\TariffRepository;
use Exception;

class TariffService implements TariffServiceInterface
{
    // todo завести enum под тарифы
    public const DEFAULT_TARIFF_CODE = 'TRIAL'; // todo нужно запрещать переходить на такой тариф. так как он системный.

    public function __construct(
        private readonly TariffRepository $tariffRepository,
        private readonly ProjectSettingServiceInterface $projectSettingServiceInterface,
    ) {
    }

    public function getTariffById(int $tariffId): ?Tariff
    {
        return $this->tariffRepository->find($tariffId);
    }

    public function getTariffByCode(string $code): ?Tariff
    {
        return $this->tariffRepository->findOneBy(
            [
                'code' => $code,
            ]
        );
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
