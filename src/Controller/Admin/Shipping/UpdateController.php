<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping;

use App\Controller\Admin\Shipping\DTO\Request\ShippingReqDto;
use App\Controller\Admin\Shipping\Exception\NotFoundShippingForProjectException;
use App\Controller\GeneralController;
use App\Entity\Ecommerce\Shipping;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Shipping\Manager\ShippingManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
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
    description: 'Обновление доставки проекта',
)]
class UpdateController extends GeneralController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly ShippingManagerInterface $shippingManager,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /**
     * @throws NotFoundShippingForProjectException
     */
    #[Route('/api/admin/project/{project}/shipping/{shipping}/', name: 'admin_shipping_update', methods: ['PATCH'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, ?Project $project, Shipping $shipping): JsonResponse
    {
        if ($project->getId() !== $shipping->getProjectId()) {
            throw new NotFoundShippingForProjectException();
        }

        try {
            $shippingDto = $this->getValidDtoFromRequest($request, ShippingReqDto::class);

            $this->shippingManager->update($shippingDto, $shipping, $project);
        } catch (Throwable $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
