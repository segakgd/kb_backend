<?php

namespace App\Service\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Request\BotReqDto;
use App\Entity\User\Bot;
use App\Repository\User\BotRepository;

class BotService implements BotServiceInterface
{
    public function __construct(
        private readonly BotRepository $botRepository,
    ) {
    }

    public function add(BotReqDto $botSettingDto, int $projectId): Bot
    {
        $newBot = (new Bot())
            ->setName($botSettingDto->getName())
            ->setType($botSettingDto->getType())
            ->setToken($botSettingDto->getToken())
            ->setProjectId($projectId)
        ;

        $this->botRepository->saveAndFlush($newBot);

        return $newBot;
    }
}
