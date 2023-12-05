<?php

namespace App\Dto\Admin\Project\Request;

class ProjectSettingUpdateDto
{
    private ProjectMainSettingUpdateDto $mainSettings;

    private ProjectNotificationsSettingUpdateDto $notificationSettings;

    public function getMainSettings(): ProjectMainSettingUpdateDto
    {
        return $this->mainSettings;
    }

    public function setMainSettings(ProjectMainSettingUpdateDto $mainSettings): void
    {
        $this->mainSettings = $mainSettings;
    }

    public function getNotificationSettings(): ProjectNotificationsSettingUpdateDto
    {
        return $this->notificationSettings;
    }

    public function setNotificationSettings(ProjectNotificationsSettingUpdateDto $notificationSettings): void
    {
        $this->notificationSettings = $notificationSettings;
    }
}
