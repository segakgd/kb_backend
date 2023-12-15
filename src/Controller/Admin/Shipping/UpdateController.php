<?php

namespace App\Controller\Admin\Shipping;

use App\Controller\Admin\Shipping\DTO\Request\ShippingReqDto;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Shipping')]
#[OA\RequestBody(
    content: new Model(
        type: ShippingReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: '', // todo You need to write a description
)]
class UpdateController extends AbstractController
{
    #[Route('/api/admin/project/{project}/shipping/{shippingId}/', name: 'admin_shipping_update', methods: ['PUT'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, int $shippingId): JsonResponse
    {
        return new JsonResponse();
    }
}
