<?php

namespace App\Controller\Admin\Project\DTO\Response\Setting;

class ProjectNotificationsSettingRespDto
{
    private ProjectNotificationSettingRespDto $newLead;

    private ProjectNotificationSettingRespDto $changesStatusLead;

    public function getNewLead(): ProjectNotificationSettingRespDto
    {
        return $this->newLead;
    }

    public function setNewLead(ProjectNotificationSettingRespDto $newLead): void
    {
        $this->newLead = $newLead;
    }

    public function getChangesStatusLead(): ProjectNotificationSettingRespDto
    {
        return $this->changesStatusLead;
    }

    public function setChangesStatusLead(ProjectNotificationSettingRespDto $changesStatusLead): void
    {
        $this->changesStatusLead = $changesStatusLead;
    }
}
