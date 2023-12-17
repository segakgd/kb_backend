<?php

namespace App\Tests\Functional\Trait;

use App\Entity\Ecommerce\Product;
use App\Entity\User\Project;
use Doctrine\Persistence\ObjectManager;

trait ProductTrait
{
    public function createProduct(
        ObjectManager $manager,
        Project $project,
        array $price,
    ): Product {
        $entity = (new Product())
            ->setProject($project->getId())
            ->setName('Name ' . uniqid())
            ->setPrice($price)
            ->setImage('')
        ;

        $manager->persist($entity);
        $manager->flush($entity);

        return $entity;
    }
}