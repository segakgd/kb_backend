<?php

namespace App\Controller\Admin\Lead;

use App\Controller\Admin\Lead\DTO\Response\Chanel\LeadChanelRespDto;
use App\Controller\Admin\Lead\DTO\Response\Fields\LeadContactsRespDto;
use App\Controller\Admin\Lead\DTO\Response\Fields\LeadFieldRespDto;
use App\Controller\Admin\Lead\DTO\Response\LeadRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\OrderRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\Payment\PaymentRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\Product\ProductCategoryRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\Product\ProductRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\Product\ProductVariantRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\Promotion\PromotionRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\Shipping\ShippingRespDto;
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

#[OA\Tag(name: 'Lead')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает коллекцию заявок',
    content: new Model(
        type: LeadRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/api/admin/project/{project}/lead/{leadId}/', name: 'admin_lead_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, int $leadId): JsonResponse
    {
        // todo ... тут мы должны обратиться к сервису или менеджеру ...

        $productCategory = (new ProductCategoryRespDto())
            ->setName('Category name')
        ;

        $productVariant = (new ProductVariantRespDto())
            ->setName('Variant name')
            ->setCount(2)
            ->setPrice(50000)
        ;

        $product = (new ProductRespDto)
            ->setName('Product name')
            ->setType(ProductRespDto::TYPE_SERVICE)
            ->setImage('image.fake')
            ->setCategory($productCategory)
            ->setVariant($productVariant)
            ->setTotalCount(2)
            ->setTotalAmount(10000)
            ->setTotalAmountWF('100,00')
        ;

        $promotion = (new PromotionRespDto)
            ->setName('Promotion Name')
            ->setCode('PROMO_2024')
            ->setDiscount(30)
            ->setCalculationType(PromotionRespDto::CALCULATION_TYPE_PERCENT)
            ->setTotalAmount(10000)
            ->setTotalAmountWF('100,00')
        ;

        $shipping = (new ShippingRespDto)
            ->setName('Shipping name')
            ->setType(ShippingRespDto::TYPE_COURIER)
            ->setTotalAmount(10000)
            ->setTotalAmountWF('100,00')
        ;

        $payment = (new PaymentRespDto())
            ->setPaymentStatus(true)
            ->setProductPrice(10000)
            ->setPromotionSum(10000)
            ->setShippingPrice(10000)
            ->setTotalAmount(30000)
            ->setTotalAmountWF('300,00')
        ;

        $fakeOrder = (new OrderRespDto())
            ->addProduct($product)
            ->addPromotion($promotion)
            ->setShipping($shipping)
            ->setPayment($payment)
            ->setCreatedAt(new DateTimeImmutable())
        ;

        $fakeField = (new LeadFieldRespDto())
            ->setType('mail')
            ->setName('Почта')
            ->setValue('fake@mail.fake')
        ;

        $fakeContacts = (new LeadContactsRespDto())
            ->setMail($fakeField)
        ;

        $chanel = (new LeadChanelRespDto())
            ->setName('telegram')
            ->setLink('link')
        ;

        $fakeLead = (new LeadRespDto())
            ->setStatus(LeadRespDto::LEAD_STATUS_NEW)
            ->setCreatedAt(new DateTimeImmutable())
            ->setContacts($fakeContacts)
            ->setNumber(111)
            ->setChanel($chanel)
            ->setOrder($fakeOrder)
        ;

        return new JsonResponse(
            $this->serializer->normalize($fakeLead)
        );
    }
}
