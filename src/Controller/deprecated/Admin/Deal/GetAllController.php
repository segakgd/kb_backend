<?php

namespace App\Controller\deprecated\Admin\Deal;

use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Deal\DealManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class GetAllController extends AbstractController
{
    public function __construct(
        private readonly DealManagerInterface $dealService,
        private readonly SerializerInterface $serializer,
    ) {}

//    #[OA\Tag(name: 'Deal')]
//    #[Route('/api/admin/project/{project}/deal/', name: 'admin_deal_get_all', methods: ['GET'])]
//    #[IsGranted('existUser', 'project')]
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
