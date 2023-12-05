<?php

namespace App\Dto\Admin\Project\Request;

class ProjectNotificationsSettingUpdateDto
{
    private ProjectNotificationSettingUpdateDto $newLead;

    private ProjectNotificationSettingUpdateDto $changesStatusLead;

    public function getNewLead(): ProjectNotificationSettingUpdateDto
    {
        return $this->newLead;
    }

    public function setNewLead(ProjectNotificationSettingUpdateDto $newLead): void
    {
        $this->newLead = $newLead;
    }

    public function getChangesStatusLead(): ProjectNotificationSettingUpdateDto
    {
        return $this->changesStatusLead;
    }

    public function setChangesStatusLead(ProjectNotificationSettingUpdateDto $changesStatusLead): void
    {
        $this->changesStatusLead = $changesStatusLead;
    }
}
