<?php

namespace App\Controller\Admin\Project\DTO\Request;

use App\Controller\Admin\Project\DTO\Request\Setting\Main\ProjectMainSettingReqDto;
use App\Controller\Admin\Project\DTO\Request\Setting\Notification\ProjectNotificationsSettingReqDto;

class ProjectSettingReqDto
{
    private ?ProjectMainSettingReqDto $mainSettings = null;

    private ?ProjectNotificationsSettingReqDto $notificationSettings = null;

    public function getMainSettings(): ?ProjectMainSettingReqDto
    {
        return $this->mainSettings;
    }

    public function setMainSettings(?ProjectMainSettingReqDto $mainSettings): void
    {
        $this->mainSettings = $mainSettings;
    }

    public function getNotificationSettings(): ?ProjectNotificationsSettingReqDto
    {
        return $this->notificationSettings;
    }

    public function setNotificationSettings(?ProjectNotificationsSettingReqDto $notificationSettings): void
    {
        $this->notificationSettings = $notificationSettings;
    }
}
