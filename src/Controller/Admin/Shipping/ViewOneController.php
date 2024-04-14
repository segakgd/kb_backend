<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping;

use App\Controller\Admin\Shipping\DTO\Response\ShippingRespDto;
use App\Entity\Ecommerce\Shipping;
use App\Entity\User\Project;
use App\Helper\Ecommerce\Shipping\ShippingHelper;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Shipping')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Получение доставки',
    content: new Model(
        type: ShippingRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    /** Получение доставки */
    #[Route('/api/admin/project/{project}/shipping/{shipping}/', name: 'admin_shipping_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, Shipping $shipping): JsonResponse
    {
        if ($project->getId() !== $shipping->getProjectId()) {
            throw new AccessDeniedException('Access Denied.');
        }

        return $this->json(ShippingHelper::MapToResponseDto($shipping), Response::HTTP_OK);
    }
}
