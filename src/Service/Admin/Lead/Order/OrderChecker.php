<?php

declare(strict_types=1);

namespace App\Service\Admin\Lead\Order;

use App\Controller\Admin\Lead\DTO\Request\Order\OrderReqDto;
use App\Controller\Admin\Lead\DTO\Request\Order\Product\OrderProductReqDto;
use App\Controller\Admin\Lead\DTO\Request\Order\Product\OrderVariantReqDto;
use App\Controller\Admin\Lead\DTO\Request\Order\Promotion\OrderPromotionReqDto;
use App\Controller\Admin\Lead\DTO\Request\Order\Shipping\OrderShippingReqDto;
use App\Controller\Admin\Product\DTO\Request\ProductVariantReqDto;
use App\Controller\Admin\Promotion\DTO\Request\PromotionReqDto;
use App\Dto\Product\Variants\VariantPriceDto;
use App\Entity\Ecommerce\ProductVariant;
use App\Entity\Ecommerce\Shipping;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\ProductVariant\Service\ProductVariantService;
use App\Service\Admin\Ecommerce\Promotion\PromotionService;
use App\Service\Admin\Ecommerce\Shipping\ShippingService;
use Exception;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class OrderChecker
{
    public function __construct(
        private readonly ShippingService $shippingService,
        private readonly PromotionService $promotionService,
        private readonly ProductVariantService $productVariantService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function checkOrderRequestByDtoAndProject(OrderReqDto $reqDto, Project $project): void
    {
        $this->checkShipping($project, $reqDto->getShipping());
        $this->checkPromotions($project, $reqDto->getShipping());
        $this->checkProducts($project, $reqDto->getProducts());
    }

    /**
     * @param OrderShippingReqDto[] $shippingArray
     * @throws Exception
     */
    private function checkShipping(Project $project, array $shippingArray): void
    {
        foreach ($shippingArray as $shippingDto) {
            /** @var null|Shipping $foundShipping */
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
     * @param PromotionReqDto[] $promotions
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

            $price = $foundPromotion->getPrice();
            $price = $price['price'] ?? null;

            if ($price !== $promotionDto->getTotalAmount()) {
                throw new Exception('Promotion price does not match requested price');
            }

            if (!isset($trackingPromotions[$promotionDto->getId()]['count'])) {
                $trackingPromotions[$promotionDto->getId()]['count'] = 1;
            } else {
                $trackingPromotions[$promotionDto->getId()]['count'] += 1;
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
        /** @var OrderProductReqDto $product */
        foreach ($products as $product) {
            try {
                $this->checkProductVariants($project, $product);
            } catch (\Throwable $exception) {
                dd($exception);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function checkProductVariants(Project $project, OrderProductReqDto $productReqDto): void
    {
        $trackingVariants = [];
        $totalAmount = $productReqDto->getTotalAmount();

        $expectedTotalAmount = 0;

        /** @var OrderVariantReqDto $variant */
        foreach ($productReqDto->getVariants() as $variant) {
            $variantEntity = $this->productVariantService->getById($variant->getId());

            if (null === $variantEntity || $this->isVariantInProject($variantEntity, $project->getId())) {
                throw new NotFoundResourceException(sprintf('Variant with id %d not found', $variant->getId()));
            }

            /** @var null|VariantPriceDto $variantPrice */
            $variantPrice = current($variantEntity->getPrice()); // todo -> need to fix
            $variantPrice = $variantPrice?->getPrice();

            if ($variant->getPrice() !== $variantPrice) {
                throw new Exception('Variant price does not match the requested price');
            }

            $this->checkVariantCount($variantEntity, $variant, $trackingVariants);

            $expectedTotalAmount += $variant->getPrice();
        }

        if ($totalAmount !== $expectedTotalAmount) {
            throw new Exception('Provided count or amount does not match actual');
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
