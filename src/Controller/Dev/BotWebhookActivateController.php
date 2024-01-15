<?php

namespace App\Controller\Dev;

use App\Entity\User\Project;
use App\Event\InitWebhookBotEvent;
use App\Service\Admin\Bot\BotServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class BotWebhookActivateController extends AbstractController
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly BotServiceInterface $botService,
    ) {
    }

    #[Route('/dev/project/{project}/bot/{botId}/webhook/activate/', name: 'dev_bot_webhook_activate', methods: ['GET'])]
    public function execute(Project $project, int $botId): RedirectResponse
    {
        $bot = $this->botService->findOne($botId, $project->getId());

        if (!$bot){
            throw new Exception('Бота не существует');
        }

        $this->eventDispatcher->dispatch(new InitWebhookBotEvent($bot));

        return new RedirectResponse('/');
    }
}
