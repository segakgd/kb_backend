<?php

declare(strict_types=1);

namespace App\Controller\Admin\Promotion;

use App\Controller\Admin\Promotion\DTO\Request\PromotionReqDto;
use App\Controller\GeneralController;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Promotion\Manager\PromotionManagerInterface;
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

#[OA\Tag(name: 'Promotion')]
#[OA\RequestBody(
    content: new Model(
        type: PromotionReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Создания скидки',
)]
class CreateController extends GeneralController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly PromotionManagerInterface $promotionManager,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct(
            $this->serializer,
            $this->validator,
        );
    }

    #[Route('/api/admin/project/{project}/promotion/', name: 'admin_promotion_create', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        try {
            $requestDto = $this->getValidDtoFromRequest($request, PromotionReqDto::class);

            $this->promotionManager->create($requestDto, $project);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());

            return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
