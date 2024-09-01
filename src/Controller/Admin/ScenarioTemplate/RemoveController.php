<?php

namespace App\Controller\Admin\ScenarioTemplate;

use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Project;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Scenario')]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Удаление сценария',
)]
class RemoveController extends AbstractController
{
    #[Route('/api/admin/project/{project}/scenario-template/{scenarioTemplate}/', name: 'admin_scenario_template_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, ScenarioTemplate $scenarioTemplate): JsonResponse
    {
        return $this->json('', Response::HTTP_NO_CONTENT);
    }
}
