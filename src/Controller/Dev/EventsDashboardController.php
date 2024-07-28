<?php

namespace App\Controller\Dev;

use App\Dto\Webhook\Telegram\TelegramWebhookDto;
use App\Entity\User\Project;
use App\Enum\Constructor\ChannelEnum;
use App\Message\TelegramMessage;
use App\Service\Admin\Bot\BotServiceInterface;
use App\Service\Constructor\Visitor\EventManager;
use App\Service\Constructor\Visitor\Session\SessionService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class EventsDashboardController extends AbstractController
{
    public function __construct(
        private readonly BotServiceInterface $botService,
        private readonly SerializerInterface $serializer,
        private readonly SessionService $sessionService,
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
            $webhookData = $this->makeCommandWebhookDto($messageText);
        } else {
            $webhookData = $this->makeMessageWebhookDto($messageText);
        }

        $channel = ChannelEnum::from('telegram');

        if (!$this->botService->isActive($botId)) {
            throw new Exception('Не активный бот');
        }

        $bot = $this->botService->findOne($botId, $project->getId());

        $chatId = $webhookData->getWebhookChatId();
        $visitorName = $webhookData->getVisitorName();

        $session = $this->sessionService->findByMainParams($bot, $chatId, $channel);

        if (null === $session) {
            $session = $this->sessionService->createSession(
                bot: $bot,
                visitorName: $visitorName,
                chatId: $chatId,
                chanel: $channel,
            );
        }

        // определяем событие
        $visitorEvent = $this->visitorEventService->createVisitorEventForSession(
            session: $session,
            type: $webhookData->getWebhookType(),
            content: $webhookData->getWebhookContent(),
        );

        $this->bus->dispatch(new TelegramMessage($visitorEvent->getId()));

        return new RedirectResponse("/admin/projects/{$project->getId()}/sessions/{$session->getId()}/");
    }

    private function makeMessageWebhookDto(string $messageText): TelegramWebhookDto
    {
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

        return $this->serializer->deserialize(
            json_encode($message),
            TelegramWebhookDto::class,
            'json'
        );
    }

    private function makeCommandWebhookDto(string $messageText)
    {
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

        return $this->serializer->deserialize(
            json_encode($message),
            TelegramWebhookDto::class,
            'json'
        );
    }
}
