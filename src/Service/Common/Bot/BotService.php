<?php

namespace App\Service\Common\Bot;

use App\Controller\Admin\Bot\Request\BotRequest;
use App\Controller\Admin\Bot\Request\BotSearchRequest;
use App\Controller\Admin\Bot\Request\InitBotRequest;
use App\Controller\Admin\Bot\Request\UpdateBotRequest;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Event\InitWebhookBotEvent;
use App\Repository\Dto\PaginationCollection;
use App\Repository\User\BotRepository;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;

readonly class BotService implements BotServiceInterface
{
    public function __construct(
        private BotRepository $botRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function search(Project $project, BotSearchRequest $requestDto): PaginationCollection
    {

    }

    /**
     * @throws Exception
     */
    public function init(InitBotRequest $requestDto, int $botId, int $projectId): void
    {
        $bot = $this->botRepository->findOneBy(
            [
                'id'        => $botId,
                'projectId' => $projectId,
            ]
        );

        if (null === $bot) {
            throw new Exception('Бот не найден');
        }

        $bot->setActive($requestDto->isActive());

        $this->botRepository->saveAndFlush($bot);

        if ($requestDto->isActive()) {
            $this->eventDispatcher->dispatch(new InitWebhookBotEvent($bot));
        }
    }

    /**
     * @throws Exception
     */
    public function findAll(int $projectId): array
    {
        return $this->botRepository->findBy(
            [
                'projectId' => $projectId,
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
                'id'        => $botId,
                'projectId' => $projectId,
            ]
        );
    }

    public function add(BotRequest $botSettingDto, int $projectId): Bot
    {
        $newBot = (new Bot())
            ->setName($botSettingDto->getName())
            ->setType($botSettingDto->getType())
            ->setToken($botSettingDto->getToken())
            ->setProjectId($projectId);

        $this->botRepository->saveAndFlush($newBot);

        return $newBot;
    }

    /**
     * @throws Exception
     */
    public function update(UpdateBotRequest $botSettingDto, int $botId, int $projectId): Bot
    {
        $bot = $this->botRepository->findOneBy(
            [
                'id'        => $botId,
                'projectId' => $projectId,
            ]
        );

        if (is_null($bot)) {
            throw new Exception('Бот не найден');
        }

        if ($botSettingDto->getName()) {
            $bot->setName($botSettingDto->getName());
        }

        if ($botSettingDto->getType()) {
            $bot->setType($botSettingDto->getType());
        }

        if ($botSettingDto->getToken()) {
            $bot->setToken($botSettingDto->getToken());
        }

        $this->botRepository->saveAndFlush($bot);

        return $bot;
    }

    public function updateStatus(int $botId, int $projectId, bool $status): Bot
    {
        $bot = $this->botRepository->findOneBy(
            [
                'id'        => $botId,
                'projectId' => $projectId,
            ]
        );

        $bot->setActive($status);

        $this->botRepository->saveAndFlush($bot);

        return $bot;
    }

    /**
     * @throws Exception
     */
    public function remove(int $botId, int $projectId): void
    {
        $bot = $this->botRepository->find($botId);

        if (null === $bot) {
            throw new Exception('Бот не найден');
        }

        $projectIdInBot = $bot->getProjectId();

        if ($projectIdInBot !== $projectId) {
            throw new Exception('Бот не от выбранного проекта');
        }

        $this->botRepository->removeAndFlush($bot);
    }

    public function isActive(Bot $bot): bool
    {
        return $bot?->isActive() ?? false;
    }
}
