<?php

namespace App\Service\Admin;

use App\Entity\History\History;
use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Helper;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Admin\Bot\BotServiceInterface;
use App\Service\Admin\History\HistoryService;
use App\Service\Admin\History\HistoryServiceInterface;
use App\Service\Admin\Scenario\ScenarioTemplateService;
use App\Service\Integration\Telegram\TelegramService;
use App\Service\Visitor\Session\VisitorSessionServiceInterface;

class DashboardService
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly HistoryServiceInterface $historyService,
        private readonly BotServiceInterface $botService,
        private readonly VisitorSessionServiceInterface $visitorSessionService,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly VisitorEventRepository $visitorEventRepository,
        private readonly ScenarioTemplateService $scenarioTemplateService,
    ) {
    }

    public function getDashboardForProject(Project $project): array
    {
        $projectId = $project->getId();

        $histories = $this->historyService->findAll($projectId, 10);
        $bots = $this->botService->findAll($projectId);
        $sessions = $this->visitorSessionService->findAll($projectId);
        $events = $this->visitorEventRepository->findAllByProjectId($projectId);
        $scenarioTemplate = $this->scenarioTemplateService->getAllByProjectId($projectId);

        return [
            'projectId' => $projectId,
            'histories' => $this->prepareHistory($histories),
            'bots' => $this->prepareBots($bots, $project),
            'scenario' => $this->prepareScenario($scenarioTemplate),
            'commands' => $this->getCommands(),
            'sessions' => $this->prepareSessions($sessions),
            'events' => $this->prepareEvents($events),
        ];
    }

    private function prepareScenario(array $scenarios): array
    {
        $prepareScenarios = [];

        /** @var ScenarioTemplate $scenario */
        foreach ($scenarios as $scenario){
            $prepareScenario = [
                'id' => $scenario->getId(),
                'name' => $scenario->getName(),
            ];

            $prepareScenarios[] = $prepareScenario;
        }

        return $prepareScenarios;
    }

    private function prepareEvents(array $events): array
    {
        $prepareEvents = [];

        /** @var VisitorEvent $event */
        foreach ($events as $event){
            $visitorSession = $this->visitorSessionRepository->findOneBy(
                [
                    'visitorEvent' => $event->getId()
                ]
            );

            $chains = [];

            if ($visitorSession) {
                $cache = $visitorSession->getCache();
                $cacheEvent = $cache['event'];
                $cacheChains = $cacheEvent['chains'];

                foreach ($cacheChains as $cacheChain) {
                    $chains[] = [
                        'name' => Helper::translate($cacheChain['target']),
                        'status' => $cacheChain['finished'],
                    ];
                }
            }

            $prepareEvent = [
                'id' => $event->getId(),
                'type' => $event->getType(),
                'status' => $event->getStatus(),
                'createdAt' => $event->getCreatedAt(),
                'chains' => $chains,
                'error' => $event->getError(),
            ];

            $prepareEvents[] = $prepareEvent;
        }

        return array_reverse($prepareEvents); // todo не очень норм использовать array_reverse
    }

    private function prepareSessions(array $sessions): array
    {
        $prepareSessions = [];

        /** @var VisitorSession $session */
        foreach ($sessions as $session){
            $visitorEvent = null;

            if ($session->getVisitorEvent()){
                $visitorEvent = $this->visitorEventRepository->findOneById($session->getVisitorEvent());
            }

            $prepareSession = [
                'sessionName' => $session->getName(),
                'sessionChannel' => $session->getChannel(),
            ];

            if ($visitorEvent){
                $prepareSession['sessionVisitorEvent'] = [
                    'type' => $visitorEvent->getType(),
                    'status' => $visitorEvent->getStatus(),
                ];
            }

            $prepareSessions[] = $prepareSession;
        }

        return $prepareSessions;
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

    private function prepareBots(array $bots, Project $project): array
    {
        $prepareBots = [];

        $projectName = $project->getName();

        /** @var Bot $bot */
        foreach ($bots as $bot){
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

    private function prepareHistory(array $histories): array
    {
        $prepareHistories = [];

        /** @var History $history */
        foreach ($histories as $history){
            $prepareHistory = [
                'createdAt' => $history->getCreatedAt(),
                'status' => $history->getStatus(),
                'type' => $this->getNormalizedType($history->getType()),
                'sender' => [
                    'name' => $history->getSender(),
                    'icon' => $this->getIconUri($history->getSender()),
                ],
                'recipient' => [
                    'name' => $history->getRecipient(),
                    'icon' => $this->getIconUri($history->getRecipient()),
                ],
            ];

            if ($history->getStatus() === HistoryService::HISTORY_STATUS_ERROR){
                $prepareHistory['errorMessage'] = $this->getNormalizedErrorMessage($history->getError());
            }

            $prepareHistories[] = $prepareHistory;
        }

        return $prepareHistories;
    }

    private function getNormalizedType(string $type): string
    {
        return match ($type){
            HistoryService::HISTORY_TYPE_NEW_LEAD => 'новая заявка',
            HistoryService::HISTORY_TYPE_SEND_MESSAGE_TO_CHANNEL => 'отправка данных в сторонний сервис (интеграции)',
            HistoryService::HISTORY_TYPE_SEND_MESSAGE_TO_TELEGRAM_CHANNEL => 'отправка уведомлений в telegram',
            HistoryService::HISTORY_TYPE_LOGIN => 'вход в систему',
            HistoryService::HISTORY_TYPE_WEBHOOK => 'Вебхук',
        };
    }

    private function getNormalizedErrorMessage(array $error): string
    {
        return $error['context'][0]['message'] ?? ''; // todo колхоз
    }

    private function getIconUri($name): string
    {
        return match ($name){
            'whatsapp' => '/assets/images/icons/whatsapp-svgrepo-com.svg',
            'telegram' => '/assets/images/icons/telegram-svgrepo-com.svg',
            'vk' => '/assets/images/icons/vk-svgrepo-com.svg',
            'bitrix' => '/assets/images/icons/bitrix24-svgrepo-com.svg',
            default => '/assets/images/icons/no-photo-svgrepo-com.svg',
        };
    }
}
