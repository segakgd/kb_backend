<?php

namespace App\Service\Admin\Bot;

use App\Controller\Admin\Bot\DTO\Request\BotReqDto;
use App\Controller\Admin\Bot\DTO\Request\UpdateBotReqDto;
use App\Entity\User\Bot;
use App\Repository\User\BotRepository;
use Exception;

class BotService implements BotServiceInterface
{
    public function __construct(
        private readonly BotRepository $botRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function findAll(int $projectId): array
    {
        return $this->botRepository->findBy(
            [
                'projectId' => $projectId
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function findOne(int $botId, int $projectId): ?Bot
    {
        return $this->botRepository->findOneBy(
            [
                'id' => $botId,
                'projectId' => $projectId,
            ]
        );
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

    public function update(UpdateBotReqDto $botSettingDto, int $botId, int $projectId): Bot
    {
        $bot = $this->botRepository->findOneBy(
            [
                'id' => $botId,
                'projectId' => $projectId,
            ]
        );

        if ($botSettingDto->getName()){
            $bot->setName($botSettingDto->getName());
        }

        if ($botSettingDto->getType()){
            $bot->setType($botSettingDto->getType());
        }

        if ($botSettingDto->getToken()){
            $bot->setToken($botSettingDto->getToken());
        }

        $this->botRepository->saveAndFlush($bot);

        return $bot;
    }

    /**
     * @throws Exception
     */
    public function remove(int $botId, int $projectId): void
    {
        $bot = $this->botRepository->find($botId);

        if (null === $bot){
            throw new Exception('Бот не найден');
        }

        $projectIdInBot = $bot->getProjectId();

        if ($projectIdInBot !== $projectId){
            throw new Exception('Бот не от выбранного проекта');
        }

        $this->botRepository->removeAndFlush($bot);
    }
}
