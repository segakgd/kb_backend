<?php

namespace App\Controller\Admin\Scenario;

use App\Controller\Admin\Scenario\DTO\Response\ScenarioRespDto;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[OA\Tag(name: 'Scenario')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: '', // todo You need to write a description
    content: new Model(
        type: ScenarioRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/api/admin/project/{project}/scenario/{scenarioId}/', name: 'admin_scenario_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $promotionId): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->normalize(new ScenarioRespDto())
        );
    }
}
