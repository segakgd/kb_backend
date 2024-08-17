<?php

namespace App\Controller\Admin\Project\DTO\Response\Setting;

use App\Controller\AbstractResponse;
use Exception;

class ProjectMainSettingRespDto extends AbstractResponse
{
    public ?string $name;

    public ?string $country;

    public ?string $timeZone;

    public ?string $language;

    public ?string $currency;

    /**
     * @throws Exception
     */
    public static function mapFromArray(array $data): static
    {
        $response = new static();

        $response->name = $data['name'];
        $response->country = $data['country'];
        $response->timeZone = $data['timeZone'];
        $response->language = $data['language'];
        $response->currency = $data['currency'];

        return $response;
    }
}
