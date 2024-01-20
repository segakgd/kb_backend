<?php

namespace App\Controller\Dev;

use App\Entity\User\Project;
use App\Service\Admin\Bot\BotServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class BotActivateController extends AbstractController
{
    public function __construct(
        private readonly BotServiceInterface $botService,
    ) {
    }

    #[Route('/dev/project/{project}/bot/{botId}/activate/', name: 'dev_bot_activate', methods: ['GET'])]
    public function execute(Project $project, int $botId): RedirectResponse
    {
        $this->botService->updateStatus($botId, $project->getId(), true);

        return new RedirectResponse('/admin');
    }
}
