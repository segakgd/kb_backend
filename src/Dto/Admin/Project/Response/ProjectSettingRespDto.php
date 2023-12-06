<?php

namespace App\Dto\Admin\Project\Response;

class ProjectSettingRespDto
{
    private ProjectMainSettingRespDto $mainSettings;

    private ProjectNotificationsSettingRespDto $notificationSetting;

    public function getMainSettings(): ProjectMainSettingRespDto
    {
        return $this->mainSettings;
    }

    public function setMainSettings(ProjectMainSettingRespDto $mainSettings): void
    {
        $this->mainSettings = $mainSettings;
    }

    public function getNotificationSetting(): ProjectNotificationsSettingRespDto
    {
        return $this->notificationSetting;
    }

    public function setNotificationSetting(ProjectNotificationsSettingRespDto $notificationSetting): void
    {
        $this->notificationSetting = $notificationSetting;
    }
}
