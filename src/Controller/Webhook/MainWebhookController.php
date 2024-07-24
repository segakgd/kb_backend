<?php

namespace App\Controller\Webhook;

use App\Dto\Webhook\Telegram\TelegramWebhookDto;
use App\Message\TelegramMessage;
use App\Repository\User\ProjectRepository;
use App\Service\Admin\Bot\BotServiceInterface;
use App\Service\Common\MessageHistoryService;
use App\Service\Visitor\Event\VisitorEventService;
use App\Service\Visitor\Session\VisitorSessionService;
use Exception;
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
        private readonly VisitorSessionService $visitorSessionService,
        private readonly VisitorEventService $visitorEventService,
        private readonly ProjectRepository $projectEntityRepository,
        private readonly BotServiceInterface $botService,
        private readonly MessageHistoryService $messageHistoryService,
        private readonly MessageBusInterface $bus,
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
    #[Route('/webhook/{projectId}/{channel}/', name: 'app_webhook_handler', methods: ['POST'])]
    public function addWebhookAction(Request $request, int $projectId, string $channel): JsonResponse
    {
        // todo нужно прокинуть в запрос botId
        $botId = 10;

        $project = $this->projectEntityRepository->find($projectId);

        if (!$project) {
            return new JsonResponse();
        }

        try {
            $webhookData = $this->serializer->deserialize(
                $request->getContent(),
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
                    visitorName: $visitorName,
                    chatId: $chatId,
                    botId: $botId,
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
        } catch (Throwable $exception) {
            return new JsonResponse('ok', 200);
        }

        return new JsonResponse('ok', 200);
    }
}
