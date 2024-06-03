<?php

namespace App\Controller\Dev;

use App\Converter\ScenarioConverter;
use App\Dto\Scenario\WrapperScenarioDto;
use App\Dto\Webhook\Telegram\TelegramWebhookDto;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Event\InitWebhookBotEvent;
use App\Repository\Scenario\ScenarioTemplateRepository;
use App\Service\Admin\Bot\BotServiceInterface;
use App\Service\System\Common\MessageHistoryService;
use App\Service\Visitor\Event\VisitorEventService;
use App\Service\Visitor\Session\VisitorSessionService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class EventsDashboardController extends AbstractController
{
    public function __construct(
        private readonly BotServiceInterface $botService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly KernelInterface $kernel,
        private readonly ScenarioConverter $settingConverter,
        private readonly SerializerInterface $serializer,
        private readonly VisitorSessionService $visitorSessionService,
        private readonly VisitorEventService $visitorEventService,
        private readonly MessageHistoryService $messageHistoryService,
        private readonly ScenarioTemplateRepository $scenarioTemplateRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route('/dev/project/{project}/bot/{botId}/fake_message/', name: 'dev_bot_fake_message', methods: ['POST'])]
    public function sendFakeMessage(Request $request, Project $project, int $botId): RedirectResponse
    {
        $messageText = $request->request->get('message') ?? throw new Exception();

        $message = [
            "update_id" => 321408479,
            "message" => [
                "message_id" => 508,
                "from" => [
                    "id" => 873817360,
                    "is_bot" => false,
                    "first_name" => "Sega",
                    "username" => "sega_kgd",
                    "language_code" => "ru",
                    "is_premium" => true
                ],
                "chat" => [
                    "id" => 873817360,
                    "first_name" => "Sega",
                    "username" => "sega_kgd",
                    "type" => "private"
                ],
                "date" => 1706982783,
                "text" => $messageText
            ]
        ];

        $webhookData = $this->serializer->deserialize(
            json_encode($message),
            TelegramWebhookDto::class,
            'json'
        );

        if (!$this->botService->isActive($botId)) {
            throw new Exception('Не активный бот');
        }

        $chatId = $webhookData->getWebhookChatId();
        $visitorName = $webhookData->getVisitorName();

        // todo проверить на IS_DEV
        $this->messageHistoryService->create(
            message: $webhookData->getWebhookContent(),
            type: MessageHistoryService::OUTGOING,
        );

        $visitorSession = $this->visitorSessionService->identifyByChannel($chatId, $botId, 'telegram');

        if (!$visitorSession) {
            $visitorSession = $this->visitorSessionService->createVisitorSession(
                $visitorName,
                $chatId,
                $botId,
                'telegram',
                $project->getId()
            );
        }

        // определяем событие
        $this->visitorEventService->createVisitorEventForSession(
            $visitorSession,
            $webhookData->getWebhookType(),
            $webhookData->getWebhookContent(),
        );

        // запускаем команду для обработки
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(
            [
                'command' => 'kb:tg:handler_events',
            ]
        );

        $application->run($input);

        return new RedirectResponse("/admin/projects/{$project->getId()}/sessions/{$visitorSession->getId()}/");
    }

    #[Route('/dev/project/{project}/bot/{botId}/activate/', name: 'dev_bot_activate', methods: ['GET'])]
    public function botActivate(Project $project, int $botId): RedirectResponse
    {
        $this->botService->updateStatus($botId, $project->getId(), true);

        return new RedirectResponse("/admin/projects/{$project->getId()}/dashboard/");
    }

    #[Route('/dev/project/{project}/bot/{botId}/deactivate/', name: 'dev_bot_deactivate', methods: ['GET'])]
    public function botDeActivate(Project $project, int $botId): RedirectResponse
    {
        $this->botService->updateStatus($botId, $project->getId(), false);

        return new RedirectResponse("/admin/projects/{$project->getId()}/dashboard/");
    }

    /**
     * @throws Exception
     */
    #[Route('/dev/project/{project}/bot/{botId}/webhook/activate/', name: 'dev_bot_webhook_activate', methods: ['GET'])]
    public function botWebhookActivate(Project $project, int $botId): RedirectResponse
    {
        $bot = $this->botService->findOne($botId, $project->getId());

        if (!$bot) {
            throw new Exception('Бота не существует');
        }

        $this->eventDispatcher->dispatch(new InitWebhookBotEvent($bot));

        return new RedirectResponse("/admin/projects/{$project->getId()}/dashboard/");
    }

    /**
     * Command start @throws Exception
     * @see TgGoCommand
     */
    #[Route('/dev/project/{project}/event/{event}/restart/', name: 'restart_one_fail_event', methods: ['GET'])]
    public function restartOneFailEvent(Project $project, VisitorEvent $event): RedirectResponse
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(
            [
                'command' => 'kb:tg:handler_events',
                'visitorEventId' => $event->getId(),
            ]
        );

        $application->run($input);

        return new RedirectResponse("/admin/projects/{$project->getId()}/dashboard/");
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    #[Route('/dev/project/{project}/bot/{bot}/apply-scenario/', name: 'apply_scenario_to_bot', methods: ['POST'])]
    public function applyScenarioToBot(
        Request $request,
        Project $project,
        Bot $bot,
    ): RedirectResponse {
        $scenarioId = $request->request->get('scenario') ?? throw new Exception('Нет параметра scenario');

        $scenarioTemplate = $this->scenarioTemplateRepository->find($scenarioId);

        $scenarios = $scenarioTemplate->getScenario()[0];
        $scenarios = [
            'scenarios' => $scenarios,
        ];

        $scenario = $this->serializer->denormalize(
            $scenarios,
            WrapperScenarioDto::class
        );

        $this->settingConverter->convert($scenario, $project->getId(), $bot->getId());

        return new RedirectResponse("/admin/projects/{$project->getId()}/dashboard/");
    }

    /**
     * @throws Exception
     */
    #[Route('/dev/project/{project}/command/{command}/start/', name: 'dev_command_start', methods: ['GET'])]
    public function commandStart(Project $project, string $command): RedirectResponse
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(
            [
                'command' => $command,
            ]
        );

        $application->run($input);

        return new RedirectResponse("/admin/projects/{$project->getId()}/dashboard/");
    }

    /**
     * @throws Exception
     */
    #[Route('/dev/project/{project}/command/{command}/start/{session}', name: 'dev_command_start', methods: ['GET'])]
    public function commandStartForSession(Project $project, string $command, VisitorSession $session): RedirectResponse
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(
            [
                'command' => $command,
            ]
        );

        $application->run($input);

        return new RedirectResponse("/admin/projects/{$project->getId()}/sessions/{$session->getId()}/");
    }
}
