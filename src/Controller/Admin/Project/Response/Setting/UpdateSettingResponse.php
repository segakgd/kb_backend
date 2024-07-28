<?php

namespace App\Controller\Admin\Project\Response\Setting;

use App\Controller\Admin\Project\DTO\Response\Setting\ProjectMainSettingRespDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectNotificationSettingRespDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectNotificationsSettingRespDto;
use App\Controller\Admin\Project\DTO\Response\Setting\ProjectSettingRespDto;
use App\Entity\User\ProjectSetting;

class UpdateSettingResponse
{
    public function mapResponse(ProjectSetting $projectSetting): ProjectSettingRespDto
    {
        // todo дублирование
        $basic = $projectSetting->getBasic();
        $notifications = $projectSetting->getNotification();

        $aboutNewLead = $notifications['aboutNewLead'] ?? null;
        $aboutChangesStatusLead = $notifications['aboutChangesStatusLead'] ?? null;

        $fakeNotificationAboutNewLead = (new ProjectNotificationSettingRespDto())
            ->setSystem($aboutNewLead['system'] ?? false)
            ->setMail($aboutNewLead['mail'] ?? false)
            ->setSms($aboutNewLead['sms'] ?? false)
            ->setTelegram($aboutNewLead['telegram'] ?? false);

        $fakeNotificationAboutChangesStatusLead = (new ProjectNotificationSettingRespDto())
            ->setSystem($aboutChangesStatusLead['system'] ?? false)
            ->setMail($aboutChangesStatusLead['mail'] ?? false)
            ->setSms($aboutChangesStatusLead['sms'] ?? false)
            ->setTelegram($aboutChangesStatusLead['telegram'] ?? false);

        $fakeNotificationSetting = (new ProjectNotificationsSettingRespDto())
            ->setNewLead($fakeNotificationAboutNewLead)
            ->setChangesStatusLead($fakeNotificationAboutChangesStatusLead);

        $fakeMainSetting = (new ProjectMainSettingRespDto())
            ->setCountry($basic['country'] ?? 'russia')
            ->setLanguage($basic['language'] ?? 'ru')
            ->setTimeZone($basic['timeZone'] ?? 'Europe/Moscow')
            ->setCurrency($basic['currency'] ?? 'RUB');

        return (new ProjectSettingRespDto())
            ->setId($projectSetting->getId())
            ->setMainSettings($fakeMainSetting)
            ->setNotificationSetting($fakeNotificationSetting);
    }
}
