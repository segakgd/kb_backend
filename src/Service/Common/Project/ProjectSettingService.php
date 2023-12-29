<?php

namespace App\Service\Common\Project;

use App\Controller\Admin\Project\DTO\Request\ProjectSettingReqDto;
use App\Controller\Admin\Project\DTO\Request\Setting\Notification\ProjectNotificationSettingReqDto;
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
    public function getSettingForProject(int $projectId): ProjectSetting
    {
        $projectSetting = $this->projectSettingRepository->findOneBy(
            [
                'projectId' => $projectId,
            ]
        );

        if (!$projectSetting){
            throw new Exception('Настроек заданного проекта не существует');
        }

        return $projectSetting;
    }

    /**
     * @throws Exception
     */
    public function updateSetting(int $projectId, ProjectSettingReqDto $projectSettingReq): ProjectSetting
    {
        $notificationSettings = $projectSettingReq->getNotificationSettings();
        $mainSettings = $projectSettingReq->getMainSettings();

        $currency = $mainSettings->getCurrency();
        $country = $mainSettings->getCountry();
        $language = $mainSettings->getLanguage();
        $timeZone = $mainSettings->getTimeZone();

        $newLead = $notificationSettings?->getNewLead() ?? new ProjectNotificationSettingReqDto();
        $changesStatusLead = $notificationSettings?->getChangesStatusLead() ?? new ProjectNotificationSettingReqDto();

        $projectSetting = $this->getSettingForProject($projectId);

        $notification = $projectSetting->getNotification();
        $basic = $projectSetting->getBasic();

        // todo убрать дублирование + подчистить

        if (null !== $currency){
            $basic['currency'] = $currency;
        }

        if (null !== $country){
            $basic['country'] = $country;
        }

        if (null !== $language){
            $basic['language'] = $language;
        }

        if (null !== $timeZone){
            $basic['timeZone'] = $timeZone;
        }

        if (null !== $newLead->getSystem()){
            $notification['aboutNewLead']['system'] = $newLead->getSystem();
        }

        if (null !== $newLead->getSms()){
            $notification['aboutNewLead']['mail'] = $newLead->getSms();
        }

        if (null !== $newLead->getMail()){
            $notification['aboutNewLead']['sms'] = $newLead->getMail();
        }

        if (null !== $newLead->getTelegram()){
            $notification['aboutNewLead']['telegram'] = $newLead->getTelegram();
        }

        if (null !== $changesStatusLead->getSystem()){
            $notification['aboutChangesStatusLead']['system'] = $changesStatusLead->getSystem();
        }

        if (null !== $changesStatusLead->getSms()){
            $notification['aboutChangesStatusLead']['mail'] = $changesStatusLead->getSms();
        }

        if (null !== $changesStatusLead->getMail()){
            $notification['aboutChangesStatusLead']['sms'] = $changesStatusLead->getMail();
        }

        if (null !== $changesStatusLead->getTelegram()){
            $notification['aboutChangesStatusLead']['telegram'] = $changesStatusLead->getTelegram();
        }

        $projectSetting->setNotification($notification);
        $projectSetting->setBasic($basic);

        $this->projectSettingRepository->saveAndFlush($projectSetting);

        return $projectSetting;
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
