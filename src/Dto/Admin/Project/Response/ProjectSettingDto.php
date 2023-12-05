<?php

namespace App\Dto\Admin\Project\Response;

class ProjectSettingDto
{
    private ProjectMainSettingDto $mainSettings;

    private ProjectNotificationsSettingDto $notificationSetting;

    public function getMainSettings(): ProjectMainSettingDto
    {
        return $this->mainSettings;
    }

    public function setMainSettings(ProjectMainSettingDto $mainSettings): void
    {
        $this->mainSettings = $mainSettings;
    }

    public function getNotificationSetting(): ProjectNotificationsSettingDto
    {
        return $this->notificationSetting;
    }

    public function setNotificationSetting(ProjectNotificationsSettingDto $notificationSetting): void
    {
        $this->notificationSetting = $notificationSetting;
    }
}
