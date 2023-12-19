<?php

namespace App\Controller\Admin\Promotion;

use App\Controller\Admin\Promotion\DTO\Request\PromotionReqDto;
use App\Controller\Admin\Promotion\DTO\Response\PromotionRespDto;
use App\Controller\Admin\Promotion\DTO\Response\ViewAllPromotionsRespDto;
use App\Entity\User\Project;
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

#[OA\Tag(name: 'Promotion')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Выводим скидки и промокоды для главной страницы скидок и промокодов',
    content: new Model(
        type: ViewAllPromotionsRespDto::class,
    ),
)]
class ViewAllController extends AbstractController
{

    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
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
        $fakeDiscount = (new PromotionRespDto())
            ->setName('discount')
            ->setType('percent')
            ->setAmount(10)
            ->setIsActive(true)
            ->setTriggersQuantity(100)
            ->setAmountWithFraction('10');

        $fakePromo = (new PromotionRespDto())
            ->setName('promo')
            ->setType('current')
            ->setAmount(10)
            ->setIsActive(true)
            ->setTriggersQuantity(100)
            ->setAmountWithFraction('10');

        $fakePromotion = (new ViewAllPromotionsRespDto)
            ->addDiscounts($fakeDiscount)
            ->addPromoCodes($fakePromo);
dd($this->serializer->normalize(
    [
        $fakePromotion,
        $fakePromotion,
    ]
));
        return new JsonResponse(
            $this->serializer->normalize(
                [
                    $fakePromotion,
                    $fakePromotion,
                ]
            )
        );
    }
}
