<?php

namespace App\Controller\Admin\Scenario;

use App\Controller\Admin\Scenario\DTO\Request\ScenarioUpdateReqDto;
use App\Controller\GeneralController;
use App\Entity\Scenario\Scenario;
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
        type: ScenarioUpdateReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Обновить сценарий',
)]
class UpdateController extends GeneralController
{
    #[Route('/api/admin/project/{project}/scenario/{scenario}/', name: 'admin_scenario_update', methods: ['PATCH'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, Scenario $scenario): JsonResponse
    {
        return new JsonResponse();
    }
}
