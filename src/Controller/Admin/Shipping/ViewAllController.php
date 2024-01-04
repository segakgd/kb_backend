<?php

namespace App\Controller\Admin\Shipping;

use App\Controller\Admin\Lead\DTO\Response\Order\Shipping\ShippingRespDto;
use App\Controller\Admin\Shipping\DTO\Request\ShippingFieldReqDto;
use App\Controller\Admin\Shipping\DTO\Response\ViewAllShippingRespDto;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Shipping')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: '', // todo You need to write a description
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: ViewAllShippingRespDto::class
            )
        )
    ),
)]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/api/admin/project/{project}/shipping/', name: 'admin_shipping_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, ShippingFieldReqDto::class, 'json');

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        // todo ... тут мы должны обратиться к сервису или менеджеру ...

        $fakeShipping = (new ViewAllShippingRespDto())
            ->setName('Доставка до самого дома')
            ->setType(ShippingRespDto::TYPE_PICKUP)
            ->setApplyFromAmount(10000)
            ->setApplyToAmount(1000)
            ->setApplyFromAmountWF('100,00')
            ->setApplyToAmountWF('10,00')
            ->setIsActive(true)
        ;
        return new JsonResponse(
            $this->serializer->normalize(
                [
                    $fakeShipping,
                    $fakeShipping,
                ]
            )
        );
    }
}
