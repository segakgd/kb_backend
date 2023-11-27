<?php

namespace App\Service\Admin\Ecommerce\ProductVariant;

use App\Dto\Ecommerce\ProductVariantDto;
use App\Entity\Ecommerce\ProductVariant;
use App\Repository\Ecommerce\ProductVariantRepository;

class ProductVariantVariantManager implements ProductVariantManagerInterface
{
    public function __construct(
        private ProductVariantRepository $productVariantRepository
    ) {
    }

    public function add(ProductVariantDto $dto): ProductVariant
    {
        $entity = (new ProductVariant());

        if ($name = $dto->getName()){
            $entity->setName($name);
        }

        if ($article = $dto->getArticle()){
            $entity->setArticle($article);
        }

        if ($image = $dto->getImage()){
            $entity->setImage($image);
        }

        if ($price = $dto->getPrice()){
            $entity->setPrice($price);
        }

        if ($count = $dto->getCount()){
            $entity->setCount($count);
        }

        if ($promotionDistributed = $dto->isPromotionDistributed()){
            $entity->setPromotionDistributed($promotionDistributed);
        }

        if ($percentDiscount = $dto->getPercentDiscount()){
            $entity->setPercentDiscount($percentDiscount);
        }

        if ($active = $dto->isActive()){
            $entity->setActive($active);
        }

        if ($activeFrom = $dto->getActiveFrom()){
            $entity->setActiveFrom($activeFrom);
        }

        if ($activeTo = $dto->getActiveTo()){
            $entity->setActiveTo($activeTo);
        }

        $this->productVariantRepository->saveAndFlush($entity);

        return $entity;
    }

    public function update(ProductVariantDto $dto): ?ProductVariant
    {
        $entity = $this->productVariantRepository->find($dto->getId());

        if (!$entity){
            $entity = (new ProductVariant());
        }

        if ($name = $dto->getName()){
            $entity->setName($name);
        }

        if ($article = $dto->getArticle()){
            $entity->setArticle($article);
        }

        if ($image = $dto->getImage()){
            $entity->setImage($image);
        }

        if ($price = $dto->getPrice()){
            $entity->setPrice($price);
        }

        if ($count = $dto->getCount()){
            $entity->setCount($count);
        }

        if ($promotionDistributed = $dto->isPromotionDistributed()){
            $entity->setPromotionDistributed($promotionDistributed);
        }

        if ($percentDiscount = $dto->getPercentDiscount()){
            $entity->setPercentDiscount($percentDiscount);
        }

        if ($active = $dto->isActive()){
            $entity->setActive($active);
        }

        if ($activeFrom = $dto->getActiveFrom()){
            $entity->setActiveFrom($activeFrom);
        }

        if ($activeTo = $dto->getActiveTo()){
            $entity->setActiveTo($activeTo);
        }

        $this->productVariantRepository->saveAndFlush($entity);

        return $entity;
    }
}
