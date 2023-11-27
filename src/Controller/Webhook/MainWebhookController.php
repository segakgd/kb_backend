<?php

namespace App\Controller\Webhook;

use App\Dto\Webhook\Telegram\TelegramWebhookDto;
use App\Entity\User\Project;
use App\Service\Visitor\Event\VisitorEventService;
use App\Service\Visitor\Session\VisitorSessionService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MainWebhookController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly VisitorSessionService $chatSessionService,
        private readonly VisitorEventService $chatEventService,
    ) {
    }

    //method connected:
    //- ЮKassa Test: 381764678:TEST:60367 2023-06-27 18:42

    //Принято! Ваши тестовые настройки:
    //shopId 506751
    //shopArticleId 538350
    //Для оплаты используйте данные тестовой карты: 1111 1111 1111 1026, 12/22, CVC 000.

    /**
     * @throws Exception
     */
    #[Route('/webhook/{project}/{channel}/', name: 'app_webhook', methods: ['POST'])]
    public function addWebhookAction(Request $request, Project $project, string $channel): JsonResponse
    {

        // todo учитывать $project

        $webhookData = $this->serializer->deserialize(
            $request->getContent(),
            TelegramWebhookDto::class,
            'json'
        );

        // это сессия пользователя из определённого канала
        $chatSession = $this->chatSessionService->getOrCreateChatSession($webhookData->getWebhookChatId(), $channel);

        $this->chatEventService->createChatEventForSession(
            $chatSession,
            $webhookData->getWebhookType(),
            $webhookData->getWebhookContent()
        );

        return new JsonResponse();
    }
}
