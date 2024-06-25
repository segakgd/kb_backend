<?php

namespace App\Service\Admin;

use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Repository\MessageHistoryRepository;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Admin\Bot\BotServiceInterface;
use App\Service\Admin\Scenario\ScenarioTemplateService;
use App\Service\Integration\Telegram\TelegramService;
use App\Service\Visitor\Session\VisitorSessionService;

readonly class DashboardService
{
    public function __construct(
        private TelegramService $telegramService,
        private BotServiceInterface $botService,
        private VisitorSessionService $visitorSessionService,
        private VisitorSessionRepository $visitorSessionRepository,
        private VisitorEventRepository $visitorEventRepository,
        private ScenarioTemplateService $scenarioTemplateService,
        private MessageHistoryRepository $historyRepository,
    ) {}

    public function prepareEvents(VisitorSession $visitorSession): array
    {
        $events = $this->visitorEventRepository->findAllByProjectId($visitorSession->getProjectId());

        $prepareEvents = [];

        foreach ($events as $event) {
            $prepareEvents[] = $this->prepareEvent($event);
        }

        return array_reverse($prepareEvents);
    }

    public function prepareEvent(VisitorEvent $event): array
    {
        $visitorSession = $this->visitorSessionRepository->find($event->getSessionId());

        $contract = [];

        if (null !== $visitorSession) {
            $cache = $visitorSession->getCache();
            $cacheEvent = $cache->getEvent();

            $cacheContract = $cacheEvent->getContract();

            $cacheChains = $cacheContract->getChains();
            $chains = [];

            foreach ($cacheChains as $cacheChain) {
                $chains[] = [
                    'name'   => $cacheChain->getTarget(),
                    'status' => $cacheChain->isFinished(),
                ];
            }

            $contract = [
                'chains'   => $chains,
                'finished' => $cacheContract->isFinished(),
            ];
        }

        return [
            'id'        => $event->getId(),
            'type'      => $event->getType(),
            'status'    => $event->getStatus()->value,
            'createdAt' => $event->getCreatedAt(),
            'contract'  => $contract,
            'error'     => $event->getError(),
            //            'responsible' => $event->getResponsible() ?? [],
        ];
    }

    public function getMessageHistory(): array
    {
        return $this->historyRepository->findAll();
    }

    public function prepareSession(VisitorSession $session): array
    {
        $cache = $session->getCache();

        return [
            'id'             => $session->getId(),
            'sessionName'    => $session->getName(),
            'sessionChannel' => $session->getChannel(),
            'cache'          => [
                'content' => $cache->getContent() ?? null,
            ],
        ];
    }

    public function prepareBots(Project $project): array
    {
        $prepareBots = [];

        $bots = $this->botService->findAll($project->getId());

        /** @var Bot $bot */
        foreach ($bots as $bot) {
            $prepareBots[] = $this->prepareBot($bot, $project);
        }

        return $prepareBots;
    }

    public function prepareBot(Bot $bot, Project $project): array
    {
        $webhookBotInfo = $this->telegramService->getWebhookInfo($bot->getToken());
        $projectName = $project->getName();

        return [
            'projectName' => $projectName,
            'botId'       => $bot->getId(),
            'botName'     => $bot->getName(),
            'projectId'   => $bot->getProjectId(),
            'botType'     => $bot->getType(),
            'botToken'    => $bot->getToken(),
            'botActive'   => $bot->isActive(),
            'webhookUri'  => $bot->getWebhookUri() ?? '',
            'webhookInfo' => [
                'pendingUpdateCount' => $webhookBotInfo->getPendingUpdateCount() ?? 0,
                'lastErrorDate'      => $webhookBotInfo->getLastErrorDate() ?? null,
                'lastErrorMessage'   => $webhookBotInfo->getLastErrorMessage() ?? null,
            ],
        ];
    }

    public function prepareScenario(Project $project): array
    {
        $scenarios = $this->scenarioTemplateService->getAllByProjectId($project->getId());

        $prepareScenarios = [];

        /** @var ScenarioTemplate $scenario */
        foreach ($scenarios as $scenario) {
            $prepareScenario = [
                'id'   => $scenario->getId(),
                'name' => $scenario->getName(),
            ];

            $prepareScenarios[] = $prepareScenario;
        }

        return $prepareScenarios;
    }

    public function getSessions(Bot $bot): array
    {
        $sessions = $this->visitorSessionService->findAllByBotId($bot->getId());

        $prepareSessions = [];

        /** @var VisitorSession $session */
        foreach ($sessions as $session) {
            $prepareSessions[] = $this->prepareSession($session);
        }

        return $prepareSessions;
    }
}
