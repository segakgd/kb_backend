<?php

namespace App\Controller\Admin\Project\Response\Setting;

use App\Controller\AbstractResponse;
use App\Entity\User\ProjectSetting;
use Exception;

class ProjectSettingResponse extends AbstractResponse
{
    public ?int $id = null;

    public ProjectMainSettingResponse $mainSettings;

    public ProjectNotificationsSettingResponse $notificationSetting;

    /**
     * @throws Exception
     */
    public static function mapFromEntity(object $entity): static
    {
        $response = new static();

        if (!$entity instanceof ProjectSetting) {
            throw new Exception('Entity with undefined type.');
        }

        $response->id = $entity->getId();
        $response->mainSettings = ProjectMainSettingResponse::mapFromArray($entity->getBasic());
        $response->notificationSetting = ProjectNotificationsSettingResponse::mapFromArray($entity->getNotification());

        return $response;
    }
}
