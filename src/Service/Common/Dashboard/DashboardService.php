<?php

namespace App\Service\Common\Dashboard;

use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Entity\Visitor\Event;
use App\Entity\Visitor\Session;
use App\Repository\MessageHistoryRepository;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Common\Bot\BotServiceInterface;
use App\Service\Common\Dashboard\Dto\BotDto;
use App\Service\Common\Dashboard\Dto\WebhookInfoDto;
use App\Service\Common\Scenario\ScenarioTemplateService;
use App\Service\Constructor\Visitor\Session\SessionService;
use App\Service\Integration\Telegram\TelegramService;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @legacy Пока что пусть это живёт как живёт, но потом это стоит переделать. Не стал разносить по сервисам, т.к, эта реализация сама по себе не очень.
 */
readonly class DashboardService
{
    public function __construct(
        private TelegramService $telegramService,
        private BotServiceInterface $botService,
        private SessionService $sessionService,
        private VisitorSessionRepository $visitorSessionRepository,
        private VisitorEventRepository $visitorEventRepository,
        private ScenarioTemplateService $scenarioTemplateService,
        private MessageHistoryRepository $historyRepository,
        private KernelInterface $kernel,
    ) {}

    public function prepareEvents(Session $visitorSession): array
    {
        $events = $this->visitorEventRepository->findAllByProjectId($visitorSession->getProjectId());

        $prepareEvents = [];

        foreach ($events as $event) {
            $prepareEvents[] = $this->prepareEvent($event);
        }

        return array_reverse($prepareEvents);
    }

    public function prepareEvent(Event $event): array
    {
        $visitorSession = $this->visitorSessionRepository->find($event->getSessionId());

        $contract = [];

        if (!is_null($visitorSession)) {
            $cache = $visitorSession->getCache();

            if (!is_null($cache) && $cache->getEvent()) {
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
        }

        return [
            'id'        => $event->getId(),
            'type'      => $event->getType(),
            'status'    => $event->getStatus()->value,
            'createdAt' => $event->getCreatedAt(),
            'contract'  => $contract,
            'error'     => $event->getError(),
        ];
    }

    public function getMessageHistory(): array
    {
        return $this->historyRepository->findAll();
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

    public function prepareBot(Bot $bot, Project $project): BotDto
    {
        $environment = $this->kernel->getEnvironment();
        $webhookBotInfo = null;

        if ($environment !== 'dev') {
            $webhookBotInfo = $this->telegramService->getWebhookInfo($bot->getToken());
        }

        $webhookInfoDto = (new WebhookInfoDto())
            ->setLastErrorDate(
                $webhookBotInfo?->getLastErrorMessage() ?? null
            )
            ->setLastErrorMessage(
                $webhookBotInfo?->getLastErrorDate() ?? null
            )
            ->setPendingUpdateCount(
                $webhookBotInfo?->getPendingUpdateCount() ?? 0
            );

        return (new BotDto())
            ->setBotId($bot->getId())
            ->setBotName($bot->getName())
            ->setProjectId($project->getId())
            ->setProjectName($project->getName())
            ->setBotType($bot->getType())
            ->setBotToken($bot->getToken())
            ->setBotActive($bot->isActive())
            ->setWebhookUri($bot->getWebhookUri())
            ->setWebhookInfo($webhookInfoDto);
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
        $sessions = $this->sessionService->findByBot($bot);

        $prepareSessions = [];

        /** @var Session $session */
        foreach ($sessions as $session) {
            $prepareSessions[] = $this->prepareSession($session);
        }

        return $prepareSessions;
    }

    private function prepareSession(Session $session): array
    {
        $cache = $session->getCache();

        return [
            'id'             => $session->getId(),
            'sessionName'    => $session->getName(),
            'sessionChannel' => $session->getChannel(),
            'cache'          => [
                'content' => $cache?->getContent() ?? null,
            ],
        ];
    }
}
