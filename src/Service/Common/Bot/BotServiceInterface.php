<?php

namespace App\Service\Common\Bot;

use App\Controller\Admin\Bot\Request\BotRequest;
use App\Controller\Admin\Bot\Request\UpdateBotRequest;
use App\Entity\User\Bot;

interface BotServiceInterface
{
    public function findAll(int $projectId): array;

    public function findOne(int $botId, int $projectId): ?Bot;

    public function add(BotRequest $botSettingDto, int $projectId): Bot;

    public function update(UpdateBotRequest $botSettingDto, int $botId, int $projectId): Bot;

    public function updateStatus(int $botId, int $projectId, bool $status): Bot;

    public function remove(int $botId, int $projectId): void;

    public function isActive(Bot $bot): bool;
}
