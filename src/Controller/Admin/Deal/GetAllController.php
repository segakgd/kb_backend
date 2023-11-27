<?php

namespace App\Controller\Admin\Deal;

use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Deal\DealManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

class GetAllController extends AbstractController
{
    public function __construct(
        private DealManagerInterface $dealService,
        private SerializerInterface $serializer,
    ) {}

    #[OA\Tag(name: 'Deal')]
    #[Route('/api/admin/project/{project}/deal/', name: 'admin_deal_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->normalize(
                $this->dealService->getAll($project->getId()),
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
