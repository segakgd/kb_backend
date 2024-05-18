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
        private DealEntityRepository $dealEntityRepository,
        private SerializerInterface $serializer,
    ) {
    }

//    public function getDashboardSessionData(VisitorSession $visitorSession): array
//    {
//        return [
//            'botId' => $visitorSession->getBotId(),
//            'projectId' => $visitorSession->getProjectId(),
//            'events' => $this->prepareEvents($visitorSession),
//            'event' => $this->getLastEvent($visitorSession->getProjectId()),
//            'messages' => $this->getMessageHistory(),
//            'commands' => $this->getCommands(),
//            'session' => $this->prepareSession($visitorSession),
//            'deals' => $this->getDeals(),
//        ];
//    }

    public function prepareEvents(VisitorSession $visitorSession): array
    {
        $events = $this->visitorEventRepository->findAllByProjectId($visitorSession->getProjectId());

        $prepareEvents = [];

        foreach ($events as $event) {
            $prepareEvents[] = $this->prepareEvent($event);
        }

        return array_reverse($prepareEvents); // todo не очень норм использовать array_reverse
    }

    public function prepareEvent(VisitorEvent $event): array
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

    public function getLastEvent(int $projectId): array
    {
        $event = $this->visitorEventRepository->getLastByProjectId($projectId);

        if (!$event) {
            return [];
        }

        return $this->prepareEvent($event);
    }

    public function getMessageHistory(): array
    {
        return $this->historyRepository->findAll();
    }

    public function getCommands(): array
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

    public function prepareSession(VisitorSession $session): array
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

    public function getDeals(): array
    {
        $deals = $this->dealEntityRepository->findAll();

        return $this->serializer->normalize(array_reverse($deals)); // todo array_reverse = костыль
    }

//    public function getDashboardForProject(Project $project): array
//    {
//        $projectId = $project->getId();
//
//        $events = $this->visitorEventRepository->findAllByProjectId($projectId);
//
//        return [
//            'projectId' => $projectId,
//            'bots' => $this->prepareBots($project),
//            'scenario' => $this->prepareScenario($projectId),
//            'commands' => $this->getCommands(),
//            'sessions' => $this->getSessions($projectId),
//            'events' => $this->prepareEvents($events),
//            'messages' => $this->getMessageHistory(),
//        ];
//    }

    public function prepareBots(Project $project): array
    {
        $prepareBots = [];

        $bots = $this->botService->findAll($project->getId());
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

    public function prepareScenario(int $projectId): array
    {
        $scenarios = $this->scenarioTemplateService->getAllByProjectId($projectId);

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

    public function getSessions(int $projectId): array
    {
        $sessions = $this->visitorSessionService->findAll($projectId);

        $prepareSessions = [];

        /** @var VisitorSession $session */
        foreach ($sessions as $session) {
            $prepareSessions[] = $this->prepareSession($session);
        }

        return $prepareSessions;
    }
}
