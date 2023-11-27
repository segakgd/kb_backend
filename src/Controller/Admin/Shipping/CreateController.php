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

class CreateController extends AbstractController
{
    public function __construct(
        private ShippingManagerInterface $shippingService,
        private ValidatorInterface $validator,
        private SerializerInterface $serializer
    ) {
    }

    #[Route('/api/admin/project/{project}/shipping/', name: 'admin_shipping_create', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();
        $shippingDto = $this->serializer->deserialize($content, ShippingDto::class, 'json');

        $errors = $this->validator->validate($shippingDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $shippingEntity = $this->shippingService->add($shippingDto, $project->getId());

        return new JsonResponse(
            $this->serializer->normalize(
                $shippingEntity,
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
