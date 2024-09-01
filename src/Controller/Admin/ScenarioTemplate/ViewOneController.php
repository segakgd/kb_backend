<?php

namespace App\Controller\Admin\ScenarioTemplate;

use App\Controller\Admin\ScenarioTemplate\Response\ScenarioTemplateResponse;
use App\Entity\Scenario\Scenario;
use App\Entity\User\Project;
use Exception;
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
    description: 'Получение одного сценария',
    content: new Model(
        type: ScenarioTemplateResponse::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {}

    /**
     * @throws Exception
     */
    #[Route('/api/admin/project/{project}/scenario-template/{scenario}/', name: 'admin_scenario_template_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, Scenario $scenario): JsonResponse
    {
        return $this->json(
            $this->serializer->normalize(ScenarioTemplateResponse::mapFromEntity($scenario))
        );
    }
}
