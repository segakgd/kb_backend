<?php

namespace App\Dto\Admin\Project\Request\Setting\Notification;

class ProjectNotificationsSettingReqDto
{
    private ProjectNotificationSettingReqDto $newLead;

    private ProjectNotificationSettingReqDto $changesStatusLead;

    public function getNewLead(): ProjectNotificationSettingReqDto
    {
        return $this->newLead;
    }

    public function setNewLead(ProjectNotificationSettingReqDto $newLead): void
    {
        $this->newLead = $newLead;
    }

    public function getChangesStatusLead(): ProjectNotificationSettingReqDto
    {
        return $this->changesStatusLead;
    }

    public function setChangesStatusLead(ProjectNotificationSettingReqDto $changesStatusLead): void
    {
        $this->changesStatusLead = $changesStatusLead;
    }
}
