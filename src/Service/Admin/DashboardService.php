<?php

namespace App\Service\Admin;

use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Helper\CommonHelper;
use App\Repository\Lead\DealEntityRepository;
use App\Repository\MessageHistoryRepository;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Admin\Bot\BotServiceInterface;
use App\Service\Admin\Scenario\ScenarioTemplateService;
use App\Service\Integration\Telegram\TelegramService;
use App\Service\Visitor\Session\VisitorSessionService;
use Symfony\Component\Serializer\SerializerInterface;

class DashboardService
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly BotServiceInterface $botService,
        private readonly VisitorSessionService $visitorSessionService,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly VisitorEventRepository $visitorEventRepository,
        private readonly ScenarioTemplateService $scenarioTemplateService,
        private readonly MessageHistoryRepository $historyRepository,
        private readonly DealEntityRepository $dealEntityRepository,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function getDashboardSessionData(VisitorSession $visitorSession): array
    {
        $events = $this->visitorEventRepository->findAllByProjectId($visitorSession->getProjectId());

        return [
            'botId' => $visitorSession->getBotId(),
            'projectId' => $visitorSession->getProjectId(),
            'events' => $this->prepareEvents($events),
            'event' => $this->getLastEvent($visitorSession->getProjectId()),
            'messages' => $this->getMessageHistory(),
            'commands' => $this->getCommands(),
            'session' => $this->prepareSession($visitorSession),
            'deals' => $this->getDeals(),
        ];
    }

    private function prepareEvents(array $events): array
    {
        $prepareEvents = [];

        /** @var VisitorEvent $event */
        foreach ($events as $event) {
            $prepareEvents[] = $this->prepareEvent($event);
        }

        return array_reverse($prepareEvents); // todo не очень норм использовать array_reverse
    }

    private function prepareEvent(VisitorEvent $event): array
    {
        $visitorSession = $this->visitorSessionRepository->findOneBy(
            [
                'visitorEvent' => $event->getId()
            ]
        );

        $steps = [];

        if ($visitorSession) {
            $cache = $visitorSession->getCache();
            $cacheEvent = $cache->getEvent();

            $cacheSteps = $cacheEvent->getSteps();

            foreach ($cacheSteps as $key => $cacheStep) {
                $cacheChains = $cacheStep->getChains();
                $chains = [];

                foreach ($cacheChains as $cacheChain) {
                    $chains[] = [
                        'name' => CommonHelper::translate($cacheChain->getTarget()),
                        'status' => $cacheChain->isFinished(),
                    ];
                }

                $steps[] = [
                    'number' => $key + 1,
                    'chains' => $chains,
                    'finished' => $cacheStep->isFinished(),
                ];
            }
        }

        return [
            'id' => $event->getId(),
            'type' => $event->getType(),
            'status' => $event->getStatus()->value,
            'createdAt' => $event->getCreatedAt(),
            'steps' => $steps,
            'error' => $event->getError(),
            'contract' => $event->getContract(),
        ];
    }

    private function getLastEvent(int $projectId): array
    {
        $event = $this->visitorEventRepository->getLastByProjectId($projectId);

        if (!$event) {
            return [];
        }

        return $this->prepareEvent($event);
    }

    private function getMessageHistory(): array
    {
        return $this->historyRepository->findAll();
    }

    private function getCommands(): array
    {
        return [
            [
                'commandName' => '⛳️️ Обработать события',
                'commandCode' => 'kb:tg:handler_events',
                'commandDescription' => 'Обрабатывает события находящиеся в очереди со статусом new',
            ],
            [
                'commandName' => '🚨 Отчистить кэш',
                'commandCode' => 'cache:clear',
                'commandDescription' => 'Чистим кеш в проде',
            ],
        ];
    }

    private function prepareSession(VisitorSession $session): array
    {
        $visitorEvent = null;

        if ($session->getVisitorEvent()) {
            $visitorEvent = $this->visitorEventRepository->findOneById($session->getVisitorEvent());
        }

        $cache = $session->getCache();

        $prepareSession = [
            'id' => $session->getId(),
            'sessionName' => $session->getName(),
            'sessionChannel' => $session->getChannel(),
            'cache' => [
                'content' => $cache->getContent() ?? null
            ]
        ];

        if ($visitorEvent) {
            $prepareSession['sessionVisitorEvent'] = [
                'type' => $visitorEvent->getType(),
                'status' => $visitorEvent->getStatus()->value,
            ];
        }

        return $prepareSession;
    }

    private function getDeals(): array
    {
        $deals = $this->dealEntityRepository->findAll();

        return $this->serializer->normalize(array_reverse($deals)); // todo array_reverse = костыль
    }

    public function getDashboardForProject(Project $project): array
    {
        $projectId = $project->getId();

        $bots = $this->botService->findAll($projectId);
        $sessions = $this->visitorSessionService->findAll($projectId);
        $events = $this->visitorEventRepository->findAllByProjectId($projectId);
        $scenarioTemplate = $this->scenarioTemplateService->getAllByProjectId($projectId);

        return [
            'projectId' => $projectId,
            'bots' => $this->prepareBots($bots, $project),
            'scenario' => $this->prepareScenario($scenarioTemplate),
            'commands' => $this->getCommands(),
            'sessions' => $this->prepareSessions($sessions),
            'events' => $this->prepareEvents($events),
            'messages' => $this->getMessageHistory(),
        ];
    }

    private function prepareBots(array $bots, Project $project): array
    {
        $prepareBots = [];

        $projectName = $project->getName();

        /** @var Bot $bot */
        foreach ($bots as $bot) {
            $webhookBotInfo = $this->telegramService->getWebhookInfo($bot->getToken());

            $prepareBot = [
                'projectName' => $projectName,
                'botId' => $bot->getId(),
                'botName' => $bot->getName(),
                'botType' => $bot->getType(),
                'botToken' => $bot->getToken(),
                'botActive' => $bot->isActive(),
                'webhookUri' => $bot->getWebhookUri() ?? '',
                'webhookInfo' => [
                    'pendingUpdateCount' => $webhookBotInfo->getPendingUpdateCount() ?? 0,
                    'lastErrorDate' => $webhookBotInfo->getLastErrorDate() ?? null,
                    'lastErrorMessage' => $webhookBotInfo->getLastErrorMessage() ?? null,
                ],
            ];

            $prepareBots[] = $prepareBot;
        }

        return $prepareBots;
    }

    private function prepareScenario(array $scenarios): array
    {
        $prepareScenarios = [];

        /** @var ScenarioTemplate $scenario */
        foreach ($scenarios as $scenario) {
            $prepareScenario = [
                'id' => $scenario->getId(),
                'name' => $scenario->getName(),
            ];

            $prepareScenarios[] = $prepareScenario;
        }

        return $prepareScenarios;
    }

    private function prepareSessions(array $sessions): array
    {
        $prepareSessions = [];

        /** @var VisitorSession $session */
        foreach ($sessions as $session) {
            $prepareSessions[] = $this->prepareSession($session);
        }

        return $prepareSessions;
    }
}
