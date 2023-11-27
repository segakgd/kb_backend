<?php

namespace App\Controller\Admin\Promotion;

use App\Dto\Ecommerce\PromotionDto;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Promotion\PromotionManagerInterface;
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
        private readonly PromotionManagerInterface $promotionService,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/admin/project/{project}/promotion/', name: 'admin_promotion_create', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();
        $promotionDto = $this->serializer->deserialize($content, PromotionDto::class, 'json');

        $errors = $this->validator->validate($promotionDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $promotionEntity = $this->promotionService->add($promotionDto, $project->getId());

        return new JsonResponse(
            $this->serializer->normalize(
                $promotionEntity,
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
