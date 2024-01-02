<?php

namespace App\Service\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Request\BotReqDto;
use App\Entity\User\Bot;

interface BotServiceInterface
{
    public function add(BotReqDto $botSettingDto, int $projectId): Bot;
}
