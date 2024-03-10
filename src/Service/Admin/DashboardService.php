<?php

namespace App\Service\Admin;

use App\Entity\History\History;
use App\Entity\Scenario\Scenario;
use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Helper\CommonHelper;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Admin\Bot\BotServiceInterface;
use App\Service\Admin\History\HistoryService;
use App\Service\Admin\History\HistoryServiceInterface;
use App\Service\Admin\Scenario\ScenarioTemplateService;
use App\Service\Integration\Telegram\TelegramService;
use App\Service\Visitor\Scenario\ScenarioService;
use App\Service\Visitor\Session\VisitorSessionService;

class DashboardService
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly HistoryServiceInterface $historyService,
        private readonly BotServiceInterface $botService,
        private readonly VisitorSessionService $visitorSessionService,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly VisitorEventRepository $visitorEventRepository,
        private readonly ScenarioTemplateService $scenarioTemplateService,
        private readonly ScenarioService $scenarioService,
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
            'botSteps' => $this->prepareBotSteps($project),
        ];
    }

    private function prepareHistory(array $histories): array
    {
        $prepareHistories = [];

        /** @var History $history */
        foreach ($histories as $history) {
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

            if ($history->getStatus() === HistoryService::HISTORY_STATUS_ERROR) {
                $prepareHistory['errorMessage'] = $this->getNormalizedErrorMessage($history->getError());
            }

            $prepareHistories[] = $prepareHistory;
        }

        return $prepareHistories;
    }

    private function getNormalizedType(string $type): string
    {
        return match ($type) {
            HistoryService::HISTORY_TYPE_NEW_LEAD => 'новая заявка',
            HistoryService::HISTORY_TYPE_SEND_MESSAGE_TO_CHANNEL => 'отправка данных в сторонний сервис (интеграции)',
            HistoryService::HISTORY_TYPE_SEND_MESSAGE_TO_TELEGRAM_CHANNEL => 'отправка уведомлений в telegram',
            HistoryService::HISTORY_TYPE_LOGIN => 'вход в систему',
            HistoryService::HISTORY_TYPE_WEBHOOK => 'Вебхук',
        };
    }

    private function getIconUri($name): string
    {
        return match ($name) {
            'whatsapp' => '/assets/images/icons/whatsapp-svgrepo-com.svg',
            'telegram' => '/assets/images/icons/telegram-svgrepo-com.svg',
            'vk' => '/assets/images/icons/vk-svgrepo-com.svg',
            'bitrix' => '/assets/images/icons/bitrix24-svgrepo-com.svg',
            default => '/assets/images/icons/no-photo-svgrepo-com.svg',
        };
    }

    private function getNormalizedErrorMessage(array $error): string
    {
        return $error['context'][0]['message'] ?? ''; // todo колхоз
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

    private function prepareSessions(array $sessions): array
    {
        $prepareSessions = [];

        /** @var VisitorSession $session */
        foreach ($sessions as $session) {
            $visitorEvent = null;

            if ($session->getVisitorEvent()) {
                $visitorEvent = $this->visitorEventRepository->findOneById($session->getVisitorEvent());
            }

            $prepareSession = [
                'sessionName' => $session->getName(),
                'sessionChannel' => $session->getChannel(),
            ];

            if ($visitorEvent) {
                $prepareSession['sessionVisitorEvent'] = [
                    'type' => $visitorEvent->getType(),
                    'status' => $visitorEvent->getStatus(),
                ];
            }

            $prepareSessions[] = $prepareSession;
        }

        return $prepareSessions;
    }

    private function prepareEvents(array $events): array
    {
        $prepareEvents = [];

        /** @var VisitorEvent $event */
        foreach ($events as $event) {
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
                        'name' => CommonHelper::translate($cacheChain['target']),
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

    private function prepareBotSteps(Project $project): array
    {
        $botSteps = [];

        $scenarios = $this->scenarioService->getAllByProjectId($project->getId());

        /** @var Scenario $scenario */
        foreach ($scenarios as $scenario) {
            $stepsKeyboard = [];
            $chains = [];

            foreach ($scenario->getSteps() as $step) {
                if (isset($step['chain'])) {
                    $chains = array_merge($chains, $step['chain']);
                }

                if ($step['keyboard']) {
                    foreach ($step['keyboard']['replyMarkup'] as $replyMarkups) {
                        foreach ($replyMarkups as $replyMarkup) {
                            $stepsKeyboard[] = [
                                'name' => $replyMarkup['text'],
                                'target' => $replyMarkup['target'],
                            ];
                        }
                    }
                }
            }

            $botSteps[$scenario->getUUID()] = [
                'name' => $scenario->getName(),
                'type' => $scenario->getType(),
                'UUID' => $scenario->getUUID(),
                'keyboard' => $stepsKeyboard,
                'chains' => $chains,
            ];
        }

        $session = $this->visitorSessionService->findAll(4842);
        $session = $session[0];

        if (!$session) {
            return [];
        }

        $cache = $session->getCache();

        if (isset($cache['eventUUID'])) {
            $botSteps[$cache['eventUUID']]['status'] = 'await';

            foreach ($cache['event']['chains'] as $chainCache) {
                foreach ($botSteps[$cache['eventUUID']]['chains'] as $key => $chain) {
                    if ($chainCache['target'] === $chain['target']) {
                        $botSteps[$cache['eventUUID']]['chains'][$key]['finish'] = $chainCache['finished'];
                    }
                }
            }
        }

        return $this->sub($botSteps);
    }

    private function sub(array $botSteps): array
    {
        $result = [];

        foreach ($botSteps as $botStep) {
            foreach ($botStep['keyboard'] as $keyboard) {
                if (isset($botSteps[$keyboard['target']])) {
                    $result[] = $this->step($botStep, $botSteps);
                }
            }
        }

        return $result;
    }

    private function step(array $botStep, array &$botSteps): array
    {
        $resultSub = [];

        $chains = $botStep['chains'];
        $status = 'not-process';

        if (isset($botStep['status'])) {
            $status = $botStep['status'];
        }

        foreach ($botStep['keyboard'] as $keyboard) {
            if ($botSteps[$keyboard['target']]) {
                $resultSub[] = $this->step($botSteps[$keyboard['target']], $botSteps);

                unset($botSteps[$keyboard['target']]);
            }
        }

        return [
            'name' => $botStep['name'],
            'sub' => $resultSub,
            'chain' => $chains,
            'status' => $status,
        ];
    }
}
