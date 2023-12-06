<?php

namespace App\Dto\Admin\Project\Request;

class ProjectSettingUpdateReqDto
{
    private ProjectMainSettingUpdateReqDto $mainSettings;

    private ProjectNotificationsSettingUpdateReqDto $notificationSettings;

    public function getMainSettings(): ProjectMainSettingUpdateReqDto
    {
        return $this->mainSettings;
    }

    public function setMainSettings(ProjectMainSettingUpdateReqDto $mainSettings): void
    {
        $this->mainSettings = $mainSettings;
    }

    public function getNotificationSettings(): ProjectNotificationsSettingUpdateReqDto
    {
        return $this->notificationSettings;
    }

    public function setNotificationSettings(ProjectNotificationsSettingUpdateReqDto $notificationSettings): void
    {
        $this->notificationSettings = $notificationSettings;
    }
}
