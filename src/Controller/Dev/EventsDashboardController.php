<?php

namespace App\Controller\Dev;

use App\Converter\SettingConverter;
use App\Dto\Scenario\WrapperScenarioDto;
use App\Dto\Webhook\Telegram\TelegramWebhookDto;
use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Project;
use App\Entity\Visitor\VisitorEvent;
use App\Event\InitWebhookBotEvent;
use App\Service\Admin\Bot\BotServiceInterface;
use App\Service\Visitor\Event\VisitorEventService;
use App\Service\Visitor\Session\VisitorSessionService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class EventsDashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly BotServiceInterface $botService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly KernelInterface $kernel,
        private readonly SettingConverter $settingConverter,
        private readonly SerializerInterface $serializer,
        private readonly VisitorSessionService $visitorSessionService,
        private readonly VisitorEventService $visitorEventService,
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

        $chatId = $webhookData->getWebhookChatId();
        $visitorName = $webhookData->getVisitorName();

        $visitorSession = $this->visitorSessionService->identifyByChannel($chatId, 'telegram');

        if (!$visitorSession) {
            $visitorSession = $this->visitorSessionService->createVisitorSession(
                $visitorName,
                $chatId,
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

        return new RedirectResponse('/admin');
    }

    #[Route('/dev/project/{project}/bot/{botId}/activate/', name: 'dev_bot_activate', methods: ['GET'])]
    public function botActivate(Project $project, int $botId): RedirectResponse
    {
        $this->botService->updateStatus($botId, $project->getId(), true);

        return new RedirectResponse('/admin');
    }

    #[Route('/dev/project/{project}/bot/{botId}/deactivate/', name: 'dev_bot_deactivate', methods: ['GET'])]
    public function botDeActivate(Project $project, int $botId): RedirectResponse
    {
        $this->botService->updateStatus($botId, $project->getId(), false);

        return new RedirectResponse('/admin');
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

        return new RedirectResponse('/admin');
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

        return new RedirectResponse('/admin');
    }

    /**
     * @throws Exception
     */
    #[Route('/dev/project/{project}/scenario/{scenarioTemplate}/apply/', name: 'apply_scenario_to_bot', methods: ['GET'])]
    public function applyScenarioToBot(
        Request $request,
        Project $project,
        ScenarioTemplate $scenarioTemplate,
    ): RedirectResponse {
        $botId = $request->query->get('botId') ?? throw new Exception('Нет параметра botId');

        $scenarios = $scenarioTemplate->getScenario()[0];
        $scenarios = [
            'scenarios' => $scenarios,
        ];

        $scenario = $this->serializer->denormalize(
            $scenarios,
            WrapperScenarioDto::class
        );

        $this->settingConverter->convert($scenario, $project->getId(), $botId);

        return new RedirectResponse('/admin');
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

        return new RedirectResponse('/admin');
    }
}
