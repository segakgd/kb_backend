<?php

namespace App\Tests\Functional\Trait;

use App\Entity\Visitor\Cart;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

trait CartTrait
{
    public function createCart(
        ObjectManager $manager,
        array $products,
        int $visitorId,
        int $totalAmount,
        string $status = 'new',
    ): Cart {
        $entity = (new Cart())
            ->setVisitorId($visitorId)
            ->setTotalAmount($totalAmount)
            ->setProducts($products)
            ->setStatus($status)
            ->setCreatedAt((new DateTimeImmutable()))
        ;

        $manager->persist($entity);
        $manager->flush($entity);

        return $entity;
    }
}