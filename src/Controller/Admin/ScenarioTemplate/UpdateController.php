<?php

namespace App\Controller\Admin\ScenarioTemplate;

use App\Controller\Admin\ScenarioTemplate\Request\ScenarioTemplateUpdateRequest;
use App\Controller\GeneralAbstractController;
use App\Entity\Scenario\ScenarioTemplate;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Scenario')]
#[OA\RequestBody(
    content: new Model(
        type: ScenarioTemplateUpdateRequest::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Обновить сценарий',
)]
class UpdateController extends GeneralAbstractController
{
    #[Route('/api/admin/project/{project}/scenario-template/{scenarioTemplate}/', name: 'admin_scenario_template_update', methods: ['PATCH'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, ScenarioTemplate $scenarioTemplate): JsonResponse
    {
        return $this->json();
    }
}
