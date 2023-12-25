<?php

namespace App\Controller\Webhook;

use App\Dto\Webhook\Telegram\TelegramWebhookDto;
use App\Entity\User\Project;
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
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route('/webhook/{project}/{channel}/', name: 'app_webhook_d', methods: ['POST'])]
    public function addWebhookAction(Request $request, Project $project, string $channel): JsonResponse
    {
        // todo учитывать $project - лучше не ожидат сразу сущность, а попробовать ждать int и ручами чекнуть есть ли проект, чтоб ошибка не такая жёсткая была

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
