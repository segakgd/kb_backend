<?php

declare(strict_types=1);

namespace App\Service\Common\Lead;

use App\Controller\Admin\Lead\Request\Order\Product\OrderVariantReqDto;
use App\Controller\Admin\Lead\Response\Fields\LeadContactsRespDto;
use App\Controller\Admin\Lead\Response\LeadRespDto;
use App\Controller\Admin\Lead\Response\Order\OrderRespDto;
use App\Controller\Admin\Lead\Response\Order\Product\ProductRespDto;
use App\Controller\Admin\Lead\Response\Order\Product\ProductVariantRespDto;
use App\Entity\Lead\Deal;
use App\Repository\Ecommerce\ProductVariantRepository;
use Exception;

readonly class LeadMapper
{
    public function __construct(
        private ProductVariantRepository $productVariantRepository,
    ) {}

    public function mapArrayToResponse(array $deals): array
    {
        return array_map(function (Deal $deal) {
            return $this->mapToResponse($deal);
        }, $deals);
    }

    /**
     * @throws Exception
     */
    public function mapToResponse(Deal $deal): LeadRespDto
    {
        $leadContactsRespDto = LeadContactsRespDto::mapFromEntity($deal);
        $fieldsRespArray = [];
        $orderDto = $this->mapOrderToResponse($deal);

        return (new LeadRespDto())
            ->setContacts($leadContactsRespDto)
            ->setFields($fieldsRespArray)
            ->setNumber($deal->getId())
            ->setOrder($orderDto);
    }

    private function mapOrderToResponse(Deal $deal): OrderRespDto
    {
        $order = $deal->getOrder();
        $orderResponseDto = (new OrderRespDto());

        if ($order !== null) {
            $orderResponseDto->setCreatedAt($order->getCreatedAt());
        }

        $products = [];

        /** @var OrderVariantReqDto $variantDto */
        foreach ($order?->getProductVariants() ?? [] as $variantDto) {
            $productVariant = $this->productVariantRepository->find($variantDto->getId());

            $name = $productVariant?->getProduct()?->getName();

            $productRespDto = new ProductRespDto();

            $productVariantRespDto = (new ProductVariantRespDto())
                ->setPrice($variantDto->getPrice())
                ->setCount($variantDto->getCount())
                ->setName($productVariant->getName());

            $productRespDto
                ->setVariant($productVariantRespDto)
                ->setName($name);

            $products[] = $productRespDto;
        }

        $orderResponseDto->setProducts($products);

        return $orderResponseDto;
    }
}
