<?php

namespace App\Controller\Admin\Scenario;

use App\Controller\Admin\Scenario\Response\ScenarioResponse;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Scenario')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию сценариев',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: ScenarioResponse::class
            )
        )
    ),
)]
class ViewAllController extends AbstractController
{
    #[Route('/api/admin/project/{project}/scenario/', name: 'admin_scenario_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        return $this->json(
            ScenarioResponse::mapCollection(
                [
                    new ScenarioResponse(),
                    new ScenarioResponse(),
                ]
            )
        );
    }
}
