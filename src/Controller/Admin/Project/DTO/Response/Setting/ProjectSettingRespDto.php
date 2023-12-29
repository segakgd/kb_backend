<?php

namespace App\Controller\Admin\Project\DTO\Response\Setting;

class ProjectSettingRespDto
{
    private ?int $id = null;

    private ProjectMainSettingRespDto $mainSettings;

    private ProjectNotificationsSettingRespDto $notificationSetting;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getMainSettings(): ProjectMainSettingRespDto
    {
        return $this->mainSettings;
    }

    public function setMainSettings(ProjectMainSettingRespDto $mainSettings): self
    {
        $this->mainSettings = $mainSettings;

        return $this;
    }

    public function getNotificationSetting(): ProjectNotificationsSettingRespDto
    {
        return $this->notificationSetting;
    }

    public function setNotificationSetting(ProjectNotificationsSettingRespDto $notificationSetting): self
    {
        $this->notificationSetting = $notificationSetting;

        return $this;

    }
}
