<?php

namespace App\Controller\Dev;

use App\Converter\ScenarioConverter;
use App\Dto\Scenario\ScenarioCollection;
use App\Dto\Webhook\Telegram\TelegramWebhookDto;
use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Event\InitWebhookBotEvent;
use App\Message\TelegramMessage;
use App\Repository\Scenario\ScenarioTemplateRepository;
use App\Service\Admin\Bot\BotServiceInterface;
use App\Service\Constructor\Visitor\EventManager;
use App\Service\Constructor\Visitor\Session\SessionService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class EventsDashboardController extends AbstractController
{
    public function __construct(
        private readonly BotServiceInterface $botService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly ScenarioConverter $settingConverter,
        private readonly SerializerInterface $serializer,
        private readonly SessionService $sessionService,
        private readonly ScenarioTemplateRepository $scenarioTemplateRepository,
        private readonly EventManager $visitorEventService,
        private readonly MessageBusInterface $bus,
    ) {}

    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    #[Route('/dev/project/{project}/bot/{botId}/fake_message/', name: 'dev_bot_fake_message', methods: ['POST'])]
    public function sendFakeMessage(Request $request, Project $project, int $botId): RedirectResponse
    {
        $messageText = $request->request->get('message') ?? throw new Exception();

        if (str_contains($messageText, '/')) {
            $message = [
                'update_id' => 321408479,
                'message'   => [
                    'message_id' => 508,
                    'from'       => [
                        'id'            => 873817360,
                        'is_bot'        => false,
                        'first_name'    => 'Sega',
                        'username'      => 'sega_kgd',
                        'language_code' => 'ru',
                        'is_premium'    => true,
                    ],
                    'chat' => [
                        'id'         => 873817360,
                        'first_name' => 'Sega',
                        'username'   => 'sega_kgd',
                        'type'       => 'private',
                    ],
                    'date'     => 1706982783,
                    'entities' => [
                        [
                            'type' => 'bot_command',
                        ],
                    ],
                    'text' => $messageText,
                ],
            ];
        } else {
            $message = [
                'update_id' => 321408479,
                'message'   => [
                    'message_id' => 508,
                    'from'       => [
                        'id'            => 873817360,
                        'is_bot'        => false,
                        'first_name'    => 'Sega',
                        'username'      => 'sega_kgd',
                        'language_code' => 'ru',
                        'is_premium'    => true,
                    ],
                    'chat' => [
                        'id'         => 873817360,
                        'first_name' => 'Sega',
                        'username'   => 'sega_kgd',
                        'type'       => 'private',
                    ],
                    'date' => 1706982783,
                    'text' => $messageText,
                ],
            ];
        }

        $webhookData = $this->serializer->deserialize(
            json_encode($message),
            TelegramWebhookDto::class,
            'json'
        );

        if (!$this->botService->isActive($botId)) {
            throw new Exception('Не активный бот');
        }

        $bot = $this->botService->findOne($botId, $project->getId());

        $chatId = $webhookData->getWebhookChatId();
        $visitorName = $webhookData->getVisitorName();

        $visitorSession = $this->sessionService->findByChannel($chatId, $bot->getId(), 'telegram');

        if (null === $visitorSession) {
            $visitorSession = $this->sessionService->createSession(
                visitorName: $visitorName,
                chatId: $chatId,
                bot: $bot,
                chanel: 'telegram',
                projectId: $project->getId(),
            );
        }

        // определяем событие
        $visitorEvent = $this->visitorEventService->createVisitorEventForSession(
            visitorSession: $visitorSession,
            type: $webhookData->getWebhookType(),
            content: $webhookData->getWebhookContent(),
        );

        $this->bus->dispatch(new TelegramMessage($visitorEvent->getId()));

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
     * @throws Exception
     * @throws Throwable
     */
    #[Route('/dev/project/{project}/bot/{bot}/apply-scenario/', name: 'apply_scenario_to_bot', methods: ['POST'])]
    public function applyScenarioToBot(
        Request $request,
        Project $project,
        Bot $bot,
    ): RedirectResponse {
        $scenarioId = $request->request->get('scenario') ?? throw new Exception('Сценарий не выбран');

        $scenarioTemplate = $this->scenarioTemplateRepository->find($scenarioId);

        $scenarios = $this->serializer->denormalize(
            [
                'scenarios' => $scenarioTemplate->getScenario(),
            ],
            ScenarioCollection::class
        );

        $this->settingConverter->convert($scenarios, $bot);

        return new RedirectResponse("/admin/projects/{$project->getId()}/dashboard/");
    }
}
