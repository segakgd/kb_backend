<?php

namespace App\Controller\Admin\Scenario;

use App\Entity\Scenario\Scenario;
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
    #[Route('/api/admin/project/{project}/scenario/{scenario}/', name: 'admin_scenario_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, Scenario $scenario): JsonResponse
    {
        return $this->json('', Response::HTTP_NO_CONTENT);
    }
}
