<?php

namespace App\Controller\Webhook;

use App\Dto\Webhook\Telegram\TelegramWebhookDto;
use App\Repository\User\ProjectEntityRepository;
use App\Service\Admin\History\HistoryService;
use App\Service\Common\History\HistoryEventService;
use App\Service\Visitor\Event\VisitorEventService;
use App\Service\Visitor\Session\VisitorSessionService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class MainWebhookController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly VisitorSessionService $visitorSessionService,
        private readonly VisitorEventService $visitorEventService,
        private readonly ProjectEntityRepository $projectEntityRepository,
        private readonly HistoryEventService $historyEventService,
    ) {
    }

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
     *      3.2.1 Если нет event-а
     *          Создаём event на основе сессии, типа сообщения и его контента(createVisitorEventByScenario)
     *              Получаем сценарий, относительно типа сообщения и контента
     *                  Если нет сценария
     *                      Получаем дефолтный сценарий
     *                          Если его нет - ошибка
     *          Создаём событие относительно сценария и типа
     *          Записываем в сесиию eventId
     *          Обновляем сессию
     *  3.2.2 Если есть event
     *      Перезаписываем существующий event - новым (rewriteChatEventByScenario)
     *          Получаем сценарий, относительно типа сообщения и контента
     *              Если нет сценария
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

        $project = $this->projectEntityRepository->find($projectId);

        if (!$project){
            return new JsonResponse();
        }

        try {
            $webhookData = $this->serializer->deserialize(
                $request->getContent(),
                TelegramWebhookDto::class,
                'json'
            );

            $chatId = $webhookData->getWebhookChatId();
            $visitorName = $webhookData->getVisitorName();

            $visitorSession = $this->visitorSessionService->identifyByChannel($chatId, $channel);

            if (!$visitorSession){
                $visitorSession = $this->visitorSessionService->createVisitorSession(
                    $visitorName,
                    $chatId,
                    'telegram',
                    $projectId
                );
            }

            // писать в кеш uuid

            // определяем событие
            $this->visitorEventService->createVisitorEventForSession(
                $visitorSession,
                $webhookData->getWebhookType(),
                $webhookData->getWebhookContent(),
            );
        } catch (Throwable $exception) {
            $this->historyEventService->errorSystem(
                $exception->getMessage(),
                $projectId,
                HistoryService::HISTORY_TYPE_WEBHOOK,
            );

            return new JsonResponse('ok', 200);
        }

        $this->historyEventService->webhookSuccess($projectId);

        return new JsonResponse('ok', 200);
    }
}
