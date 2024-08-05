<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping;

use App\Controller\Admin\Shipping\DTO\Request\ShippingReqDto;
use App\Controller\Admin\Shipping\Exception\NotFoundShippingForProjectException;
use App\Controller\GeneralAbstractController;
use App\Entity\Ecommerce\Shipping;
use App\Entity\User\Project;
use App\Service\Common\Ecommerce\Shipping\Manager\ShippingManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
class UpdateController extends GeneralAbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly ShippingManagerInterface $shippingManager,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    /** Обновление доставки */
    #[Route('/api/admin/project/{project}/shipping/{shipping}/', name: 'admin_shipping_update', methods: ['PATCH'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, ?Project $project, Shipping $shipping): JsonResponse
    {
        try {
            if ($project->getId() !== $shipping->getProjectId()) {
                throw new NotFoundShippingForProjectException();
            }

            $shippingDto = $this->getValidDtoFromRequest($request, ShippingReqDto::class);

            $this->shippingManager->update($shippingDto, $shipping, $project);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());

            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
