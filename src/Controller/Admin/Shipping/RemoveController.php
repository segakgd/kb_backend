<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping;

use App\Entity\Ecommerce\Shipping;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Shipping\Manager\ShippingManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[OA\Tag(name: 'Shipping')]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Если получилось удалить возвращаем 204',
)]
class RemoveController extends AbstractController
{
    public function __construct(private readonly ShippingManagerInterface $shippingManager,)
    {
    }

    /** Удаление доставки */
    #[Route('/api/admin/project/{project}/shipping/{shipping}/', name: 'admin_shipping_remove', methods: ['DELETE'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, Shipping $shipping): JsonResponse
    {
        if ($shipping->getProjectId() !== $project->getId()) {
            throw new AccessDeniedException('Access Denied.');
        }

        try {
            $this->shippingManager->delete($shipping);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
