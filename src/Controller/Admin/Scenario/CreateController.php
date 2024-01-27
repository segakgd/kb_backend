<?php

namespace App\Controller\Admin\Scenario;

use App\Controller\Admin\Scenario\DTO\Request\ScenarioReqDto;
use App\Entity\User\Project;
use App\Service\Admin\Scenario\ScenarioTemplateService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        type: ScenarioReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: '', // todo You need to write a description
)]
class CreateController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly ScenarioTemplateService $scenarioTemplateService,
    ) {
    }

    #[Route('/api/admin/project/{project}/scenario/', name: 'admin_scenario_create', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, ScenarioReqDto::class, 'json');

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $this->scenarioTemplateService->create($requestDto, $project->getId());

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
