<?php

namespace App\Controller\Admin\Project\Request\Setting\Notification;

class ProjectNotificationsSettingRequest
{
    private ProjectNotificationSettingRequest $newLead;

    private ProjectNotificationSettingRequest $changesStatusLead;

    public function getNewLead(): ProjectNotificationSettingRequest
    {
        return $this->newLead;
    }

    public function setNewLead(ProjectNotificationSettingRequest $newLead): void
    {
        $this->newLead = $newLead;
    }

    public function getChangesStatusLead(): ProjectNotificationSettingRequest
    {
        return $this->changesStatusLead;
    }

    public function setChangesStatusLead(ProjectNotificationSettingRequest $changesStatusLead): void
    {
        $this->changesStatusLead = $changesStatusLead;
    }
}
