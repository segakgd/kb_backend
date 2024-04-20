<?php

declare(strict_types=1);

namespace App\Controller\Admin\Promotion;

use App\Controller\Admin\Promotion\DTO\Request\PromotionReqDto;
use App\Controller\Admin\Promotion\DTO\Response\PromotionRespDto;
use App\Controller\Admin\Promotion\DTO\Response\ViewAllPromotionsRespDto;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Promotion\Manager\PromotionManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Project')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию проектов',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: PromotionRespDto::class
            )
        )
    ),
)]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly PromotionManagerInterface $promotionManager,
    ) {
    }

    /** Вывести все скидки и промокоды */
    #[Route('/api/admin/project/{project}/promotion/', name: 'admin_promotion_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, PromotionReqDto::class, 'json');

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($this->promotionManager->getAllByProject($project));
    }
}
