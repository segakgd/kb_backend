<?php

namespace App\Dto\Admin\Project\Request;

use App\Dto\Admin\Project\Request\Setting\Main\ProjectMainSettingReqDto;
use App\Dto\Admin\Project\Request\Setting\Notification\ProjectNotificationsSettingReqDto;

class ProjectSettingReqDto
{
    private ProjectMainSettingReqDto $mainSettings;

    private ProjectNotificationsSettingReqDto $notificationSettings;

    public function getMainSettings(): ProjectMainSettingReqDto
    {
        return $this->mainSettings;
    }

    public function setMainSettings(ProjectMainSettingReqDto $mainSettings): void
    {
        $this->mainSettings = $mainSettings;
    }

    public function getNotificationSettings(): ProjectNotificationsSettingReqDto
    {
        return $this->notificationSettings;
    }

    public function setNotificationSettings(ProjectNotificationsSettingReqDto $notificationSettings): void
    {
        $this->notificationSettings = $notificationSettings;
    }
}
