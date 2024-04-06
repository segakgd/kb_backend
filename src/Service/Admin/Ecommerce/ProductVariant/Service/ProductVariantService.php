<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\ProductVariant\Service;

use App\Controller\Admin\Product\DTO\Request\ProductVariantReqDto;
use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductVariant;
use App\Repository\Ecommerce\ProductVariantRepository;

class ProductVariantService
{
    public function __construct(private readonly ProductVariantRepository $productVariantRepository)
    {
    }

    public function handleRequestVariantsDto(Product $product, array $variantsRequestDto): Product
    {
        /** @var ProductVariantReqDto $variantDto */
        foreach ($variantsRequestDto as $variantDto) {
            if (null === $variantDto->getId()) {
                $productVariantEntity = (new ProductVariant())
                    ->setName($variantDto->getName())
                    ->setCount($variantDto->getCount())
                    ->setArticle($variantDto->getArticle())
                    ->setPrice($variantDto->getPrice())
                    ->setImage($variantDto->getImages());

                $product->addVariant($productVariantEntity);
            } elseif (null !== $variantDto->getId()) {
                $variantEntity = $this->getByProductAndId(
                    $product->getId(),
                    $variantDto->getId()
                );

                $variantEntity
                    ?->markAsUpdated()
                    ->setName($variantDto->getName())
                    ->setCount($variantDto->getCount())
                    ->setPrice($variantDto->getPrice())
                    ->setArticle($variantDto->getArticle())
                    ->setImage($variantDto->getImages())
                    ->setActive($variantDto->isActive())
                    ->setProduct($product);

                $this->save($variantEntity);
            }
        }

        return $product;
    }

    public function save(ProductVariant $productVariant): ProductVariant
    {
        $this->productVariantRepository->saveAndFlush($productVariant);

        return $productVariant;
    }

    public function getByProductAndId(int $product, int $id): ?ProductVariant
    {
        return $this->productVariantRepository->findOneBy(['id' => $id, 'product' => $product]);
    }

    public function getById(int $id): ?ProductVariant
    {
        return $this->productVariantRepository->find($id);
    }
}
