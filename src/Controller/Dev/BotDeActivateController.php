<?php

namespace App\Controller\Dev;

use App\Entity\User\Project;
use App\Service\Admin\Bot\BotServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class BotDeActivateController extends AbstractController
{
    public function __construct(
        private readonly BotServiceInterface $botService,
    ) {
    }

    #[Route('/dev/project/{project}/bot/{botId}/deactivate/', name: 'dev_bot_deactivate', methods: ['GET'])]
    public function execute(Project $project, int $botId): RedirectResponse
    {
        $this->botService->updateStatus($botId, $project->getId(), false);

        return new RedirectResponse('/admin');
    }
}
