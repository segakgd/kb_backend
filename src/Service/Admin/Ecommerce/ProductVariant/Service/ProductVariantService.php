<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\ProductVariant\Service;

use App\Controller\Admin\Product\DTO\Request\ProductVariantReqDto;
use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductVariant;
use App\Repository\Ecommerce\ProductVariantRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProductVariantService
{
    public function __construct(
        private ProductVariantRepository $productVariantRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function handleBatchUpdate(Product $product, array $variantsRequestDto): Product
    {
        $updatingVariants = $updatingVariantsIds = $existingVariants = [];

        foreach ($product->getVariants()->toArray() as $variant) {
            $existingVariants[$variant->getId()] = $variant;
        }

        /** @var ProductVariantReqDto $variantDto */
        foreach ($variantsRequestDto as $variantDto) {
            if (null === $variantDto->getId()) {
                $variantEntity = (new ProductVariant())
                    ->setName($variantDto->getName())
                    ->setCount($variantDto->getCount())
                    ->setArticle($variantDto->getArticle())
                    ->setPrice($variantDto->getPrice())
                    ->setImage($variantDto->getImages())
                    ->setProduct($product);

                $updatingVariants[] = $variantEntity;
            } else {
                $variantEntity = $existingVariants[$variantDto->getId()] ?? null;

                if (null !== $variantEntity) {
                    $variantEntity
                        ->setName($variantDto->getName())
                        ->setCount($variantDto->getCount())
                        ->setPrice($variantDto->getPrice())
                        ->setArticle($variantDto->getArticle())
                        ->setImage($variantDto->getImages())
                        ->setActive($variantDto->isActive())
                        ->setProduct($product)
                        ->markAsUpdated();

                    $updatingVariants[] = $variantEntity;
                    $updatingVariantsIds[] = $variantEntity->getId();
                }
            }
        }

        $this->batchSave($updatingVariants);

        $removingIds = array_diff(array_keys($existingVariants), $updatingVariantsIds);

        $this->productVariantRepository->removeVariantsByIds($removingIds);

        return $product;
    }

    public function batchSave(array $productVariants): void
    {
        // todo дублирование
        if (empty($productVariants)) {
            return;
        }

        $iterator = 0;
        $batchSize = 20;

        foreach ($productVariants as $productVariant) {
            $this->entityManager->persist($productVariant);

            if ((++$iterator) % $batchSize === 0) {
                $this->entityManager->flush();
            }
        }

        $this->entityManager->flush();
    }

    public function save(ProductVariant $productVariant): ProductVariant
    {
        $this->productVariantRepository->saveAndFlush($productVariant);

        return $productVariant;
    }

    public function getById(int $id): ?ProductVariant
    {
        return $this->productVariantRepository->find($id);
    }
}
