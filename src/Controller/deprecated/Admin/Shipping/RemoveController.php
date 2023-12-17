<?php

namespace App\Controller\deprecated\Admin\Shipping;

use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Shipping\ShippingManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RemoveController extends AbstractController
{
    public function __construct(
        private readonly ShippingManagerInterface $shippingService,
    ) {}

//    #[Route('/api/admin/project/{project}/shipping/{shippingId}/', name: 'admin_shipping_remove', methods: ['DELETE'])]
//    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $shippingId): JsonResponse
    {
        $this->shippingService->remove($project->getId(),  $shippingId);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
