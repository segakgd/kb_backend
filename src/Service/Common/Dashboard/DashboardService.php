<?php

namespace App\Service\Common\Dashboard;

use App\Entity\MessageHistory;
use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Entity\Visitor\Event;
use App\Entity\Visitor\Session;
use App\Repository\MessageHistoryRepository;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Common\Bot\BotServiceInterface;
use App\Service\Common\Dashboard\Dto\ActionDto;
use App\Service\Common\Dashboard\Dto\BotDto;
use App\Service\Common\Dashboard\Dto\ContractDto;
use App\Service\Common\Dashboard\Dto\EventDto;
use App\Service\Common\Dashboard\Dto\MessageDto;
use App\Service\Common\Dashboard\Dto\ScenarioDto;
use App\Service\Common\Dashboard\Dto\SessionCacheDto;
use App\Service\Common\Dashboard\Dto\SessionDto;
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

    public function getMessageHistory(Session $session): array
    {
        $histories = $this->historyRepository->findBy([
            'session' => $session,
        ]);

        $result = [];

        foreach ($histories as $history) {
            $result[] = (new MessageDto())
                ->setId($history->getId())
                ->setMessage($history->getMessage())
                ->setType($history->getType())
                ->setKeyboard($history->getKeyBoard())
                ->setImages($history->getImages());
        }

        return $result;
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

    /**
     * @return array<ScenarioDto>
     */
    public function prepareScenario(Project $project): array
    {
        $scenarios = $this->scenarioTemplateService->getAllByProjectId($project->getId());

        $prepareScenarios = [];

        /** @var ScenarioTemplate $scenario */
        foreach ($scenarios as $scenario) {
            $prepareScenarios[] = (new ScenarioDto())
                ->setId($scenario->getId())
                ->setName($scenario->getName());
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

    private function prepareEvent(Event $event): EventDto
    {
        $visitorSession = $this->visitorSessionRepository->find($event->getSessionId());

        $contract = new ContractDto();

        if (!is_null($visitorSession)) {
            $cache = $visitorSession->getCache();

            if (!is_null($cache) && $cache->getEvent()) {
                $cacheEvent = $cache->getEvent();

                $cacheContract = $cacheEvent->getContract();

                $cacheActions = $cacheContract->getChains();

                foreach ($cacheActions as $cacheAction) {
                    $contract->addChain(
                        (new ActionDto())
                        ->setName($cacheAction->getTarget())
                        ->setStatus($cacheAction->isFinished())
                    );
                }

                $contract->setFinished($cacheContract->isFinished());
            }
        }

        return (new EventDto())
            ->setId($event->getId())
            ->setType($event->getType())
            ->setStatus($event->getStatus())
            ->setContract($contract)
            ->setError($event->getError())
            ->setCreatedAt($event->getCreatedAt());
    }

    private function prepareSession(Session $session): SessionDto
    {
        $cache = $session->getCache();

        return (new SessionDto())
            ->setId($session->getId())
            ->setSessionName($session->getName())
            ->setSessionChannel($session->getChannel())
            ->setCache(
                (new SessionCacheDto())
                    ->setContent($cache?->getContent() ?? null)
            );
    }
}
