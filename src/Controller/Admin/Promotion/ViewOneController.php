<?php

namespace App\Controller\Admin\Promotion;

use App\Controller\Admin\Promotion\DTO\Response\FullPromotionRespDto;
use App\Entity\User\Project;
use DateTimeImmutable;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[OA\Tag(name: 'Promotion')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: '', // todo You need to write a description
    content: new Model(
        type: FullPromotionRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }
    #[Route('/api/admin/project/{project}/promotion/{promotionId}/', name: 'admin_promotion_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $promotionId): JsonResponse
    {
        $fakeFullPromotion = (new FullPromotionRespDto())
            ->setName('promo')
            ->setType('current')
            ->setCode('2024')
            ->setAmount(1000)
            ->setAmountWithFraction('10,00')
            ->setTriggersQuantity(10000)
            ->setActiveTo(new DateTimeImmutable())
            ->setActiveFrom(new DateTimeImmutable())
            ->setIsActive(true)
        ;

        return new JsonResponse(
            $this->serializer->normalize($fakeFullPromotion)
        );
    }
}
