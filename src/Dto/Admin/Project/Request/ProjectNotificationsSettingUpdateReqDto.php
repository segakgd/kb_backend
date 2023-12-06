<?php

namespace App\Dto\Admin\Project\Request;

class ProjectNotificationsSettingUpdateReqDto
{
    private ProjectNotificationSettingUpdateReqDto $newLead;

    private ProjectNotificationSettingUpdateReqDto $changesStatusLead;

    public function getNewLead(): ProjectNotificationSettingUpdateReqDto
    {
        return $this->newLead;
    }

    public function setNewLead(ProjectNotificationSettingUpdateReqDto $newLead): void
    {
        $this->newLead = $newLead;
    }

    public function getChangesStatusLead(): ProjectNotificationSettingUpdateReqDto
    {
        return $this->changesStatusLead;
    }

    public function setChangesStatusLead(ProjectNotificationSettingUpdateReqDto $changesStatusLead): void
    {
        $this->changesStatusLead = $changesStatusLead;
    }
}
