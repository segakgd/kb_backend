<?php

namespace App\Controller\Admin\Project\Response\Setting;

use App\Controller\AbstractResponse;
use Exception;

class ProjectNotificationsSettingResponse extends AbstractResponse
{
    public ProjectNotificationSettingResponse $newLead;

    public ProjectNotificationSettingResponse $changesStatusLead;

    /**
     * @throws Exception
     */
    public static function mapFromArray(array $data): static
    {
        $response = new static();

        $response->newLead = ProjectNotificationSettingResponse::mapFromArray($response['newLead']);
        $response->changesStatusLead = ProjectNotificationSettingResponse::mapFromArray($response['changesStatusLead']);

        return $response;
    }
}
