<?php

namespace App\Controller\Admin\Shipping;

use App\Controller\Admin\Shipping\DTO\Response\ShippingFieldRespDto;
use App\Controller\Admin\Shipping\DTO\Response\ShippingRespDto;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[OA\Tag(name: 'Shipping')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: '', // todo You need to write a description
    content: new Model(
        type: ShippingRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{

    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }
    #[Route('/api/admin/project/{project}/shipping/{shippingId}/', name: 'admin_shipping_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $shippingId): JsonResponse
    {
        $fields = (new ShippingFieldRespDto())
            ->setName('name')
            ->setType('type')
            ->setValue('value');

        $fakeShipping = (new ShippingRespDto())
            ->setName('shipping 1')
            ->setType(\App\Controller\Admin\Lead\DTO\Response\Order\Shipping\ShippingRespDto::TYPE_PICKUP)
            ->setApplyFromAmount(100)
            ->setIsActive(true)
            ->setApplyToAmount(10)
            ->setApplyFromAmountWF('100')
            ->setApplyToAmountWF('10')
            ->setCalculationType('percent')
            ->setDescription('asdasdasd')
            ->setAmount(10)
            ->addFields($fields)
        ;
        return new JsonResponse(
            $this->serializer->normalize($fakeShipping)
        );
    }
}
