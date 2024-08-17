<?php

namespace App\Controller\Admin\Project\Request;

use App\Controller\Admin\Project\Request\Setting\Main\ProjectMainSettingRequest;
use App\Controller\Admin\Project\Request\Setting\Notification\ProjectNotificationsSettingRequest;

class ProjectSettingRequest
{
    private ?ProjectMainSettingRequest $mainSettings = null;

    private ?ProjectNotificationsSettingRequest $notificationSettings = null;

    public function getMainSettings(): ?ProjectMainSettingRequest
    {
        return $this->mainSettings;
    }

    public function setMainSettings(?ProjectMainSettingRequest $mainSettings): void
    {
        $this->mainSettings = $mainSettings;
    }

    public function getNotificationSettings(): ?ProjectNotificationsSettingRequest
    {
        return $this->notificationSettings;
    }

    public function setNotificationSettings(?ProjectNotificationsSettingRequest $notificationSettings): void
    {
        $this->notificationSettings = $notificationSettings;
    }
}
