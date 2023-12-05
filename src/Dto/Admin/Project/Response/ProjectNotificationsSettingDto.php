<?php

namespace App\Dto\Admin\Project\Response;

class ProjectNotificationsSettingDto
{
    private ProjectNotificationSettingDto $newLead;

    private ProjectNotificationSettingDto $changesStatusLead;

    public function getNewLead(): ProjectNotificationSettingDto
    {
        return $this->newLead;
    }

    public function setNewLead(ProjectNotificationSettingDto $newLead): void
    {
        $this->newLead = $newLead;
    }

    public function getChangesStatusLead(): ProjectNotificationSettingDto
    {
        return $this->changesStatusLead;
    }

    public function setChangesStatusLead(ProjectNotificationSettingDto $changesStatusLead): void
    {
        $this->changesStatusLead = $changesStatusLead;
    }
}
