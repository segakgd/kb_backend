<?php

namespace App\Controller\Admin\Shipping;

use App\Dto\Ecommerce\ShippingDto;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Shipping\ShippingManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateController extends AbstractController
{
    public function __construct(
        private readonly ShippingManagerInterface $shippingService,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/admin/project/{project}/shipping/{shippingId}/', name: 'admin_shipping_update', methods: ['PUT'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, int $shippingId): JsonResponse
    {
        $content = $request->getContent();
        $shippingDto = $this->serializer->deserialize($content, ShippingDto::class, 'json');

        $errors = $this->validator->validate($shippingDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $shippingEntity = $this->shippingService->update($shippingDto, $project->getId(), $shippingId);

        return new JsonResponse(
            $this->serializer->normalize(
                $shippingEntity,
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
