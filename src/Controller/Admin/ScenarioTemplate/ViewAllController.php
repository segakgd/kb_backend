<?php

namespace App\Controller\Admin\ScenarioTemplate;

use App\Controller\Admin\ScenarioTemplate\Request\ScenarioTemplateSearchRequest;
use App\Controller\Admin\ScenarioTemplate\Response\ScenarioTemplateResponse;
use App\Controller\GeneralAbstractController;
use App\Entity\User\Project;
use App\Service\Common\Scenario\ScenarioTemplateService;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
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
class ViewAllController extends GeneralAbstractController
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
        $requestDto = $this->getValidDtoFromFormDataRequest($request, ScenarioTemplateSearchRequest::class);

        $scenarioCollection = $scenarioTemplateService->search(
            project: $project,
            requestDto: $requestDto,
        );

        $scenarioTemplateResponse = ScenarioTemplateResponse::mapCollection(
            $scenarioCollection->getItems()
        );

        return $this->json(
            static::makePaginateResponse(
                $scenarioTemplateResponse,
                $scenarioCollection->getPaginate(),
            )
        );
    }
}
