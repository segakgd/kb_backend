<?php

namespace App\Controller\Admin\Project\Response\Setting;

use App\Controller\AbstractResponse;
use Exception;

class ProjectNotificationsSettingRespDto extends AbstractResponse
{
    public ProjectNotificationSettingRespDto $newLead;

    public ProjectNotificationSettingRespDto $changesStatusLead;

    /**
     * @throws Exception
     */
    public static function mapFromArray(array $data): static
    {
        $response = new static();

        $response->newLead = ProjectNotificationSettingRespDto::mapFromArray($response['newLead']);
        $response->changesStatusLead = ProjectNotificationSettingRespDto::mapFromArray($response['changesStatusLead']);

        return $response;
    }
}
