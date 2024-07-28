<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping;

use App\Controller\Admin\Shipping\DTO\Response\ShippingRespDto;
use App\Controller\Admin\Shipping\Exception\NotFoundShippingForProjectException;
use App\Controller\Admin\Shipping\Response\ShippingViewOneResponse;
use App\Entity\Ecommerce\Shipping;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[OA\Tag(name: 'Shipping')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Получение одной доставки',
    content: new Model(
        type: ShippingRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {}

    /** Получение конкретной доставки */
    #[Route('/api/admin/project/{project}/shipping/{shipping}/', name: 'admin_shipping_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, Shipping $shipping): JsonResponse
    {
        try {
            if ($project->getId() !== $shipping->getProjectId()) {
                throw new NotFoundShippingForProjectException();
            }

            return $this->json(
                (new ShippingViewOneResponse())->makeResponse($shipping)
            );
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());

            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
