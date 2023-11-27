<?php

namespace App\Controller\Admin\Project;

use App\Entity\User\User;
use App\Service\Common\Project\ProjectServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GetAllController extends AbstractController
{
    public function __construct(
        private readonly ProjectServiceInterface $projectService,
        private readonly SerializerInterface $serializer,
    ) {}

    #[Route('/api/admin/projects/', name: 'admin_project_get_all', methods: ['GET'])]
    public function execute(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return new JsonResponse(
            $this->serializer->normalize(
                $this->projectService->getAll($user),
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
