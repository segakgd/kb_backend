<?php

namespace App\Controller\Admin\Shipping;

use App\Entity\User\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RemoveController extends AbstractController
{
    #[Route('/api/admin/project/{project}/shipping/{shippingId}/', name: 'admin_shipping_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $shippingId): JsonResponse
    {
        return new JsonResponse();

    }
}
