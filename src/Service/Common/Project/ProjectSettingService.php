<?php

namespace App\Service\Common\Project;

use App\Entity\User\ProjectSetting;
use App\Entity\User\Tariff;
use App\Repository\User\ProjectSettingRepository;
use App\Repository\User\TariffRepository;
use Exception;

class ProjectSettingService implements ProjectSettingServiceInterface
{
    public function __construct(
        private readonly ProjectSettingRepository $projectSettingRepository,
        private readonly TariffRepository $tariffRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function updateTariff(int $projectId, Tariff $tariff): bool
    {
        $projectSetting = $this->projectSettingRepository->findOneBy(
            [
                'projectId' => $projectId,
            ]
        );

        if (!$projectSetting){
            throw new Exception('Настроек заданного проекта не существует');
        }

        $projectSetting->setTariffId($tariff->getId());

        $this->projectSettingRepository->saveAndFlush($projectSetting);

        return true;
    }

    /**
     * @throws Exception
     */
    public function initSetting(int $projectId): void
    {
        $tariff = $this->tariffRepository->findOneBy(
            [
                'code' => TariffService::DEFAULT_TARIFF_CODE
            ]
        );

        if (!$tariff){
            throw new Exception('Базового тарифа не существет');
        }

        $projectSetting = (new ProjectSetting())
            ->setTariffId($tariff->getId())
            ->setProjectId($projectId)
            ->setBasic(
                [
                    'country' => 'russia',
                    'language' => 'ru',
                    'timeZone' => 'Europe/Moscow',
                    'currency' => 'RUB',
                ]
            )
            ->setNotification(
                [
                    'aboutNewLead' => [
                        'system' => true,
                        'mail' => false,
                        'sms' => false,
                        'telegram' => false,
                    ],
                    'aboutChangesStatusLead' => [
                        'system' => true,
                        'mail' => false,
                        'sms' => false,
                        'telegram' => false,
                    ]
                ]
            )
        ;

        $this->projectSettingRepository->saveAndFlush($projectSetting);
    }
}
