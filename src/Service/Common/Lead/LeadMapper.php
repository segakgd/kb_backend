<?php

declare(strict_types=1);

namespace App\Service\Common\Lead;

use App\Controller\Admin\Lead\Request\Order\Product\OrderVariantReqDto;
use App\Controller\Admin\Lead\Response\Fields\LeadContactsResponse;
use App\Controller\Admin\Lead\Response\LeadResponse;
use App\Controller\Admin\Lead\Response\Order\OrderResponse;
use App\Controller\Admin\Lead\Response\Order\Product\ProductResponse;
use App\Controller\Admin\Lead\Response\Order\Product\ProductVariantResponse;
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
    public function mapToResponse(Deal $deal): LeadResponse
    {
        $leadContactsRespDto = LeadContactsResponse::mapFromEntity($deal);
        $fieldsRespArray = [];
        $orderDto = $this->mapOrderToResponse($deal);

        return (new LeadResponse())
            ->setContacts($leadContactsRespDto)
            ->setFields($fieldsRespArray)
            ->setNumber($deal->getId())
            ->setOrder($orderDto);
    }

    private function mapOrderToResponse(Deal $deal): OrderResponse
    {
        $order = $deal->getOrder();
        $orderResponseDto = (new OrderResponse());

        if ($order !== null) {
            $orderResponseDto->setCreatedAt($order->getCreatedAt());
        }

        $products = [];

        /** @var OrderVariantReqDto $variantDto */
        foreach ($order?->getProductVariants() ?? [] as $variantDto) {
            $productVariant = $this->productVariantRepository->find($variantDto->getId());

            $name = $productVariant?->getProduct()?->getName();

            $productRespDto = new ProductResponse();

            $productVariantRespDto = (new ProductVariantResponse())
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
