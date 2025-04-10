<?php

namespace App\Service\Common\Project;

use App\Entity\User\Project;
use App\Entity\User\Tariff;
use App\Repository\User\TariffRepository;
use App\Service\Common\Project\Enum\TariffCodeEnum;
use Exception;

readonly class TariffService implements TariffServiceInterface
{
    public function __construct(
        private TariffRepository $tariffRepository,
        private ProjectSettingServiceInterface $projectSettingServiceInterface,
    ) {}

    public function getTariffById(int $tariffId): ?Tariff
    {
        return $this->tariffRepository->find($tariffId);
    }

    public function getTariffByCode(TariffCodeEnum $code): ?Tariff
    {
        return $this->tariffRepository->findOneBy(
            [
                'code' => $code->value,
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
                'code'   => $code,
                'active' => true,
            ]
        );

        if (!$tariff) {
            throw new Exception('Тарифа не существует или он не активный');
        }

        $projectId = $project->getId();

        return $this->projectSettingServiceInterface->updateTariff($projectId, $tariff);
    }
}
