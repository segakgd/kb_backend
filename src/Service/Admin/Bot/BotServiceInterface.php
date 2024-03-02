<?php

namespace App\Service\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Request\BotReqDto;
use App\Controller\Admin\Bot\DTO\Request\UpdateBotReqDto;
use App\Entity\User\Bot;

interface BotServiceInterface
{
    public function findAll(int $projectId): array;

    public function findOne(int $botId, int $projectId): ?Bot;

    public function add(BotReqDto $botSettingDto, int $projectId): Bot;

    public function update(UpdateBotReqDto $botSettingDto, int $botId, int $projectId): Bot;

    public function updateStatus(int $botId, int $projectId, bool $status): Bot;

    public function remove(int $botId, int $projectId): void;

    public function isActive(int $botId): bool;
}
