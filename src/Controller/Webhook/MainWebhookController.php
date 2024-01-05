<?php

namespace App\Controller\Webhook;

use App\Dto\Webhook\Telegram\TelegramWebhookDto;
use App\Repository\User\ProjectEntityRepository;
use App\Service\Visitor\Event\VisitorEventService;
use App\Service\Visitor\Session\VisitorSessionService;
use App\Service\Visitor\VisitorServiceInterface;
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
        private readonly VisitorSessionService $visitorSessionService,
        private readonly VisitorEventService $chatEventService,
        private readonly VisitorServiceInterface $visitorService,
        private readonly ProjectEntityRepository $projectEntityRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route('/webhook/{projectId}/{channel}/', name: 'app_webhook_d', methods: ['POST'])]
    public function addWebhookAction(Request $request, int $projectId, string $channel): JsonResponse
    {
        $project = $this->projectEntityRepository->find($projectId);

        if (!$project){
            return new JsonResponse();
        }

        $webhookData = $this->serializer->deserialize(
            $request->getContent(),
            TelegramWebhookDto::class,
            'json'
        );

        // получаем визитёра
        $visitor = $this->visitorService->identifyUser($webhookData->getWebhookChatId(), $channel);

        // инитим сессию если нету, возвращаем
        $chatSession = $this->visitorSessionService->getOrCreateSession($visitor);

        // определяем событие
        $this->chatEventService->createChatEventForSession(
            $chatSession,
            $webhookData->getWebhookType(),
            $webhookData->getWebhookContent()
        );

        return new JsonResponse();
    }
}
