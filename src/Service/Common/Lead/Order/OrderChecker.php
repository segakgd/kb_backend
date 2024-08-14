<?php

declare(strict_types=1);

namespace App\Service\Common\Lead\Order;

use App\Controller\Admin\Lead\Request\Order\OrderReqDto;
use App\Controller\Admin\Lead\Request\Order\Product\OrderVariantReqDto;
use App\Controller\Admin\Lead\Request\Order\Promotion\OrderPromotionReqDto;
use App\Controller\Admin\Lead\Request\Order\Shipping\OrderShippingReqDto;
use App\Controller\Admin\Promotion\DTO\Request\PromotionReqDto;
use App\Dto\Ecommerce\Product\Variants\VariantPriceDto;
use App\Entity\Ecommerce\ProductVariant;
use App\Entity\Ecommerce\Shipping;
use App\Entity\User\Project;
use App\Exception\NotFoundResourceException;
use App\Service\Common\Ecommerce\ProductVariant\Service\ProductVariantService;
use App\Service\Common\Ecommerce\Promotion\Service\PromotionService;
use App\Service\Common\Ecommerce\Shipping\Service\ShippingService;
use Exception;

readonly class OrderChecker
{
    public function __construct(
        private ShippingService $shippingService,
        private PromotionService $promotionService,
        private ProductVariantService $productVariantService,
    ) {}

    /**
     * @throws Exception
     */
    public function checkOrderRequestByDtoAndProject(OrderReqDto $reqDto, Project $project): void
    {
        $this->checkShipping($project, $reqDto->getShipping());
        $this->checkPromotions($project, $reqDto->getShipping());
        $this->checkProducts($project, $reqDto->getProductVariants());

        $this->checkOrderTotalSum($reqDto);
    }

    /**
     * @throws Exception
     */
    private function checkOrderTotalSum(OrderReqDto $reqDto): void // todo -> промоушены и доставка как бы еще нормально не сделаны
    {
        $totalAmountShipping = $totalAmountPromotion = $productsTotalPrice = 0;

        /** @var OrderShippingReqDto $shippingDto */
        foreach ($reqDto->getShipping() as $shippingDto) {
            $totalAmountShipping += $shippingDto->getTotalAmount();
        }

        /** @var OrderPromotionReqDto $promotionDto */
        foreach ($reqDto->getPromotions() as $promotionDto) {
            $totalAmountPromotion += $promotionDto->getTotalAmount();
        }

        /** @var OrderVariantReqDto $variantDto */
        foreach ($reqDto->getProductVariants() as $variantDto) {
            $productsTotalPrice += $variantDto->getPrice();
        }

        $totalAmount = max(max($productsTotalPrice - $totalAmountPromotion, 0) + $totalAmountShipping, 0);

        if ($totalAmount !== $reqDto->getTotalAmount()) {
            throw new Exception('Order total amount counted incorrect');
        }
    }

    /**
     * @param  OrderShippingReqDto[] $shippingArray
     * @throws Exception
     */
    private function checkShipping(Project $project, array $shippingArray): void
    {
        foreach ($shippingArray as $shippingDto) {
            /** @var Shipping|null $foundShipping */
            $foundShipping = $this->shippingService->findByIdAndProjectId($shippingDto->getId(), $project->getId());

            if (null === $foundShipping) {
                throw new NotFoundResourceException(sprintf('Shipping with id %d not found', $shippingDto->getId()));
            }

            $price = $foundShipping->getPrice();
            $price = $price['price'] ?? null;

            if ($price !== $shippingDto->getTotalAmount()) {
                throw new Exception('Shipping price does not match requested price');
            }
        }
    }

    /**
     * @param  PromotionReqDto[] $promotions
     * @throws Exception
     */
    private function checkPromotions(Project $project, array $promotions): void
    {
        $trackingPromotions = [];

        /** @var OrderPromotionReqDto $promotionDto */
        foreach ($promotions as $promotionDto) {
            $foundPromotion = $this->promotionService->findByIdAndProjectId($promotionDto->getId(), $project->getId());

            if (null === $foundPromotion) {
                throw new NotFoundResourceException(sprintf('Promotion with id %d not found', $promotionDto->getId()));
            }

            $price = $foundPromotion->getAmount();
            $price = $price['price'] ?? null;

            if ($price !== $promotionDto->getTotalAmount()) {
                throw new Exception('Promotion price does not match requested price');
            }

            if (!isset($trackingPromotions[$promotionDto->getId()]['count'])) {
                $trackingPromotions[$promotionDto->getId()]['count'] = 1;
            } else {
                ++$trackingPromotions[$promotionDto->getId()]['count'];
            }

            if ($trackingPromotions[$promotionDto->getId()]['count'] > $foundPromotion->getCount()) {
                throw new Exception('Requested promotion count exceeds presented one');
            }
        }
    }

    /**
     * @throws Exception
     */
    private function checkProducts(Project $project, array $products): void
    {
        $trackingVariants = [];

        /** @var OrderVariantReqDto $variantReqDto */
        foreach ($products as $variant) {
            $variantEntity = $this->productVariantService->getById($variant->getId());

            if (null === $variantEntity || !$this->isVariantInProject($variantEntity, $project->getId())) {
                throw new NotFoundResourceException(sprintf('Variant with id %d not found', $variant->getId()));
            }

            /** @var VariantPriceDto|null $variantPrice */
            $variantPrice = current($variantEntity->getPrice()); // todo -> need to fix
            $variantPrice = $variantPrice?->getPrice();

            if ($variant->getPrice() !== $variantPrice) {
                throw new Exception('Variant price does not match the requested price');
            }

            $this->checkVariantCount($variantEntity, $variant, $trackingVariants);
        }
    }

    /**
     * @throws Exception
     */
    private function checkVariantCount(
        ProductVariant $variantEntity,
        OrderVariantReqDto $variant,
        array $trackingVariants
    ): void {
        if ($variantEntity->isLimitless()) {
            return;
        }

        if (!isset($trackingVariants[$variant->getId()]['count'])) {
            $trackingVariants[$variant->getId()]['count'] = $variant->getCount();
        } else {
            $trackingVariants[$variant->getId()]['count'] += $variant->getCount();
        }

        if ($trackingVariants[$variant->getId()]['count'] > $variantEntity->getCount()) {
            throw new Exception('Requested variant count exceeds presented one');
        }
    }

    private function isVariantInProject(ProductVariant $variant, int $projectId): bool
    {
        $product = $variant->getProduct();

        return $product?->getProjectId() === $projectId;
    }
}
