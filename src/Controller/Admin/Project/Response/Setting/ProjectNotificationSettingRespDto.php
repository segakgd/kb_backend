<?php

namespace App\Controller\Admin\Project\Response\Setting;

use App\Controller\AbstractResponse;
use Exception;

class ProjectNotificationSettingRespDto extends AbstractResponse
{
    public ?bool $system = true;

    public ?bool $mail;

    public ?bool $telegram;

    public ?bool $sms;

    /**
     * @throws Exception
     */
    public static function mapFromArray(array $data): static
    {
        $response = new static();

        $response->system = $aboutNewLead['system'] ?? false;
        $response->mail = $aboutNewLead['mail'] ?? false;
        $response->sms = $aboutNewLead['sms'] ?? false;
        $response->telegram = $aboutNewLead['telegram'] ?? false;

        return $response;
    }
}
