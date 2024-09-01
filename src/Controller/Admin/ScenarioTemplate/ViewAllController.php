<?php

namespace App\Controller\Admin\ScenarioTemplate;

use App\Controller\Admin\ScenarioTemplate\Response\ScenarioTemplateResponse;
use App\Entity\User\Project;
use App\Service\Constructor\Scenario\ScenarioTemplateService;
use Exception;
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
                type: ScenarioTemplateResponse::class
            )
        )
    ),
)]
class ViewAllController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/api/admin/project/{project}/scenario-template/', name: 'admin_scenario_template_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(
        Request $request,
        Project $project,
        ScenarioTemplateService $scenarioTemplateService,
    ): JsonResponse {
        $scenarios = $scenarioTemplateService->all($project);

        return $this->json(
            ScenarioTemplateResponse::mapCollection($scenarios)
        );
    }
}
