<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping;

use App\Controller\Admin\Lead\DTO\Response\Order\Shipping\ShippingRespDto;
use App\Entity\User\Project;
use App\Helper\Ecommerce\Shipping\ShippingHelper;
use App\Service\Admin\Ecommerce\Shipping\Manager\ShippingManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Shipping')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Получение коллекции доставок проекта',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: ShippingRespDto::class
            )
        )
    ),
)]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly ShippingManagerInterface $shippingManager,
    ) {
    }

    /** Получение колекции доставок*/
    #[Route('/api/admin/project/{project}/shipping/', name: 'admin_shipping_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project): JsonResponse
    {
        $shippingCollection = $this->shippingManager->getAllByByProject($project);

        return $this->json(ShippingHelper::mapArrayToResponseDto($shippingCollection), Response::HTTP_OK);
    }
}
