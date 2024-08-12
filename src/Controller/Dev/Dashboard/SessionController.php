<?php

namespace App\Controller\Dev\Dashboard;

use App\Entity\User\Bot;
use App\Entity\User\Project;
use App\Entity\Visitor\Session;
use App\Service\Common\Dashboard\DashboardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SessionController extends AbstractController
{
    public function __construct(
        private readonly DashboardService $dashboardService,
        private readonly SerializerInterface $serializer,
    ) {}

    #[Route('/admin/projects/{project}/bot/{bot}/', name: 'admin_project_bot_dashboard')]
    public function bot(Project $project, Bot $bot): Response
    {
        $response = [
            'sessions'  => $this->dashboardService->getSessions($bot),
            'bot'       => $this->dashboardService->prepareBot($bot, $project),
            'scenarios' => $this->dashboardService->prepareScenario($project),
            'projectId' => $project->getId(),
        ];

        return $this->render(
            'admin/user/bot.html.twig',
            $this->serializer->normalize($response)
        );
    }

    #[Route('/admin/projects/{project}/sessions/{visitorSession}/', name: 'admin_project_sessions')]
    public function session(Project $project, Session $visitorSession): Response
    {
        $response = [
            'projectId' => $project->getId(),
            'botId'     => $visitorSession->getBot()->getId(),
            'messages'  => $this->dashboardService->getMessageHistory($visitorSession),
            'events'    => $this->dashboardService->prepareEvents($visitorSession),
            'sessions'  => $this->dashboardService->prepareSession($visitorSession),
            'contract'  => $visitorSession->getCache()->getEvent()->getContract(),
        ];

        return $this->render(
            'admin/session.html.twig',
            $this->serializer->normalize($response)
        );
    }
}
