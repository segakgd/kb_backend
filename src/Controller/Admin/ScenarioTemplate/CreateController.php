<?php

namespace App\Controller\Admin\ScenarioTemplate;

use App\Controller\Admin\ScenarioTemplate\Request\ScenarioTemplateRequest;
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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Scenario')]
#[OA\RequestBody(
    content: new Model(
        type: ScenarioTemplateRequest::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Создание сценария',
)]
class CreateController extends GeneralAbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly ScenarioTemplateService $scenarioTemplateService,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /**
     * @throws Exception
     */
    #[Route('/api/admin/project/{project}/scenario-template/', name: 'admin_scenario_template_create', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $requestDto = $this->getValidDtoFromRequest($request, ScenarioTemplateRequest::class);

        $this->scenarioTemplateService->create($requestDto, $project->getId());

        return $this->json('', Response::HTTP_NO_CONTENT);
    }
}
