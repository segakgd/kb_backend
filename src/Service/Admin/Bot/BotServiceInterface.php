<?php

namespace App\Service\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Request\BotReqDto;

interface BotServiceInterface
{
    public function add(BotReqDto $botSettingDto, int $projectId): void;
}
