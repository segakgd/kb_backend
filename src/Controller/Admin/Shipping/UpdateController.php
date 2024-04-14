<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping;

use App\Controller\Admin\Shipping\DTO\Request\ShippingReqDto;
use App\Entity\Ecommerce\Shipping;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Shipping\Manager\ShippingManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[OA\Tag(name: 'Shipping')]
#[OA\RequestBody(
    content: new Model(
        type: ShippingReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Обновление продукта',
)]
class UpdateController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly ShippingManagerInterface $shippingManager,
    ) {
    }

    /** Обновление доставки */
    #[Route('/api/admin/project/{project}/shipping/{shipping}/', name: 'admin_shipping_update', methods: ['PATCH'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, Shipping $shippingId): JsonResponse
    {
        if ($project->getId() !== $shippingId->getProjectId()) {
            throw new AccessDeniedException('Access Denied.');
        }

        try {
            $shippingDto = $this->serializer->deserialize($request->getContent(), ShippingReqDto::class, 'json');

            $errors = $this->validator->validate($shippingDto);

            if (count($errors)) {
                return $this->json($errors->get(0)->getMessage(), Response::HTTP_BAD_REQUEST);
            }

            $this->shippingManager->update($shippingDto, $shippingId, $project);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
