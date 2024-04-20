<?php

declare(strict_types=1);

namespace App\Service\Admin\Lead\Order;

use App\Controller\Admin\Lead\DTO\Request\Order\OrderReqDto;
use App\Controller\Admin\Lead\DTO\Request\Order\Product\OrderVariantReqDto;
use App\Controller\Admin\Lead\DTO\Request\Order\Promotion\OrderPromotionReqDto;
use App\Controller\Admin\Lead\DTO\Request\Order\Shipping\OrderShippingReqDto;
use App\Entity\Lead\DealOrder;
use App\Repository\Lead\OrderEntityRepository;
use App\Service\Admin\Ecommerce\ProductVariant\Service\ProductVariantService;
use App\Service\Admin\Ecommerce\Promotion\Service\PromotionService;
use App\Service\Admin\Ecommerce\Shipping\Service\ShippingService;

class OrderService
{
    public function __construct(
        private readonly OrderEntityRepository $orderEntityRepository,
        private readonly ShippingService $shippingService,
        private readonly PromotionService $promotionService,
        private readonly ProductVariantService $productVariantService,
    ) {
    }

    public function add(OrderReqDto $dto): DealOrder
    {
        $order = new DealOrder();

        /** @var OrderVariantReqDto $productVariant */
        foreach ($dto->getProductVariants() as $productVariant) {
            $product = $this->productVariantService->getById($productVariant->getId());

            if ($product) {
                $order->addProductVariant($productVariant);
            }
        }

        /** @var OrderShippingReqDto $shippingDto */
        foreach ($dto->getShipping() as $shippingDto) {
            $shipping = $this->shippingService->getById($shippingDto->getId());

            if ($shipping) {
                $order->addShipping($shippingDto);
            }
        }

        /** @var OrderPromotionReqDto $promotionReqDto */
        foreach ($dto->getPromotions() as $promotionReqDto) {
            $promotion = $this->promotionService->getById($promotionReqDto->getId());

            if ($promotion) {
                $order->addPromotion($promotionReqDto);
            }
        }

        $order
            ->setTotalAmount($dto->getTotalAmount())
            ->markAsUpdated();

        $this->save($order);

        return $order;
    }

    public function updateOrCreate(OrderReqDto $orderDto, ?DealOrder $dealOrder): ?DealOrder
    {
        if (null === $dealOrder) {
            $dealOrder = (new DealOrder());
        }

        $dealOrder
            ->setShipping($orderDto->getShipping())
            ->setPromotions($orderDto->getShipping())
            ->setProductVariants($orderDto->getProductVariants())
            ->setTotalAmount($orderDto->getTotalAmount())
        ;

        $this->save($dealOrder);

        return $dealOrder;
    }

    private function save(DealOrder $order): void
    {
        $this->orderEntityRepository->saveAndFlush($order);
    }
}
