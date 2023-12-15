<?php

namespace App\Controller\Admin\Shipping;

use App\Entity\User\Project;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Shipping')]
class UpdateController extends AbstractController
{
    #[Route('/api/admin/project/{project}/shipping/{shippingId}/', name: 'admin_shipping_update', methods: ['PUT'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, int $shippingId): JsonResponse
    {
        return new JsonResponse();
    }
}
