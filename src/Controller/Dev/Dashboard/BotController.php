<?php

namespace App\Controller\Dev\Dashboard;

use App\Entity\User\Project;
use App\Entity\Visitor\Session;
use App\Service\Common\Dashboard\DashboardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BotController extends AbstractController
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    #[Route('/admin/projects/{project}/sessions/{visitorSession}/', name: 'admin_project_sessions')]
    public function session(Project $project, Session $visitorSession): Response
    {
        return $this->render(
            'admin/session.html.twig',
            [
                'projectId' => $project->getId(),
                'botId'     => $visitorSession->getBot()->getId(),
                'messages'  => $this->dashboardService->getMessageHistory($visitorSession),
                'events'    => $this->dashboardService->prepareEvents($visitorSession),
            ]
        );
    }
}
