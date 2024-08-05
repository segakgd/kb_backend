<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping;

use App\Controller\Admin\Shipping\Exception\NotFoundShippingForProjectException;
use App\Entity\Ecommerce\Shipping;
use App\Entity\User\Project;
use App\Service\Common\Ecommerce\Shipping\Manager\ShippingManagerInterface;
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
    response: Response::HTTP_NO_CONTENT,
    description: 'Если получилось удалить возвращаем 204',
)]
class RemoveController extends AbstractController
{
    public function __construct(
        private readonly ShippingManagerInterface $shippingManager,
        private readonly LoggerInterface $logger,
    ) {}

    /** Удаление доставки */
    #[Route('/api/admin/project/{project}/shipping/{shipping}/', name: 'admin_shipping_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, Shipping $shipping): JsonResponse
    {
        try {
            if ($project->getId() !== $shipping->getProjectId()) {
                throw new NotFoundShippingForProjectException();
            }

            $this->shippingManager->delete($shipping);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());

            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
