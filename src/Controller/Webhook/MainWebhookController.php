<?php

namespace App\Controller\Webhook;

use App\Dto\Webhook\Telegram\TelegramWebhookDto;
use App\Entity\User\Bot;
use App\Enum\Constructor\ChannelEnum;
use App\Message\TelegramMessage;
use App\Repository\User\ProjectRepository;
use App\Service\Common\Bot\BotServiceInterface;
use App\Service\Common\History\Enum\HistoryTypeEnum;
use App\Service\Common\History\MessageHistoryService;
use App\Service\Constructor\EventManager;
use App\Service\Constructor\Session\SessionService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class MainWebhookController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly SessionService $sessionService,
        private readonly EventManager $visitorEventService,
        private readonly ProjectRepository $projectEntityRepository,
        private readonly BotServiceInterface $botService,
        private readonly MessageHistoryService $messageHistoryService,
        private readonly MessageBusInterface $bus,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * Что происходит сейчас:
     *
     * 1. Приходит webhook, десериализуем его в объект
     * 2. Ищем сессию
     *  2.1 Если нет сессии:
     *  2.1.1 Создаём сессию на основе сессии и данных из вебхука
     * 3. Создаём событие на основе сессии, типа сообщения и его контента(createVisitorEventForSession):
     *  3.1 Получаем из сессии eventId
     *  3.2 Получаем event привязанный к сессии если он есть
     *      3.2.1 Если нет event-а.
     *          Создаём event на основе сессии, типа сообщения и его контента(createVisitorEventByScenario)
     *              Получаем сценарий, относительно типа сообщения и контента
     *                  Если нет сценария:
     *                      Получаем дефолтный сценарий
     *                          Если его нет - ошибка
     *          Создаём событие относительно сценария и типа.
     *          Записываем в сессию eventId.
     *          Обновляем сессию
     *  3.2.2 Если есть event:
     *      Перезаписываем существующий event - новым (rewriteChatEventByScenario)
     *          Получаем сценарий, относительно типа сообщения и контента
     *              Если нет сценария:
     *                  Получаем дефолтный сценарий
     *                      Если его нет - ошибка
     *          Перезаписываем event
     *
     * @throws Exception
     */
    #[Route('/webhook/{projectId}/{channel}/bot/{bot}', name: 'app_webhook_handler', methods: ['POST'])]
    public function addWebhookAction(Request $request, Bot $bot, int $projectId, string $channel): JsonResponse
    {
        $project = $this->projectEntityRepository->find($projectId);

        if (!$project) {
            return $this->json('ok');
        }

        if (ChannelEnum::isIn($channel)) {
            $this->logger->error("Channel $channel не валиден");

            return $this->json('ok');
        }

        $channel = ChannelEnum::from($channel);

        try {
            $webhookData = $this->serializer->deserialize(
                $request->getContent(),
                TelegramWebhookDto::class,
                'json'
            );

            if (!$this->botService->isActive($bot)) {
                throw new Exception('Не активный бот');
            }

            $chatId = $webhookData->getWebhookChatId();
            $visitorName = $webhookData->getVisitorName();

            $session = $this->sessionService->findByMainParams($bot, $chatId, $channel);

            if (isset($_SERVER['APP_ENV']) && $_SERVER['APP_ENV'] === 'dev') {
                $this->messageHistoryService->create(
                    session: $session,
                    message: $webhookData->getWebhookContent(),
                    type: HistoryTypeEnum::Incoming,
                );
            }

            if (!$session) {
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
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());

            return $this->json('ok');
        }

        return $this->json('ok');
    }
}
