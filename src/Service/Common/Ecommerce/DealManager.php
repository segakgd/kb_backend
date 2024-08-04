<?php

namespace App\Service\Common\Ecommerce;

use App\Dto\Ecommerce\ProductDto;
use App\Dto\SessionCache\Cache\CacheCartDto;
use App\Entity\Lead\Deal;
use App\Entity\Lead\DealContacts;
use App\Entity\Lead\DealOrder;
use App\Entity\User\Project;
use App\Repository\Ecommerce\ProductVariantRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class DealManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProductVariantRepository $productVariantRepository,
    ) {}

    // todo не используется
    public function createDeal(Project $project, CacheCartDto $cart): Deal
    {
        $contactsEntity = $this->enrichContacts($cart);
        $order = $this->enrichOrder($cart);

        $deal = (new Deal())
            ->setContacts($contactsEntity)
            ->setOrder($order)
            ->setProjectId($project->getId());

        $this->entityManager->persist($deal);
        $this->entityManager->flush();

        return $deal;
    }

    private function enrichContacts(CacheCartDto $cart): DealContacts
    {
        $contacts = $cart->getContacts();

        return (new DealContacts())
            ->setFirstName($contacts['full'])
            ->setLastName($contacts['phone']);
    }

    private function enrichOrder(CacheCartDto $cart): DealOrder
    {
        $order = (new DealOrder());

        $totalAmount = 0;
        $productsCart = $cart->getProducts();

        foreach ($productsCart as $productCart) {
            $variant = $this->productVariantRepository->find($productCart['variantId']);

            $price = $variant->getPrice();

            $productDto = (new ProductDto())
                ->setVariantParentId($variant->getId())
                ->setName($variant->getName())
                ->setCount($productCart['count'])
                ->setPrice($price['price']->getPrice());

            // todo тут какой-то косяк с типами
            $order->addProductVariant($productDto);

            $totalByProduct = $price['price'] * $productCart['count'];
            $totalAmount += $totalByProduct;
        }

        $shipping = $cart->getShipping();

        return $order
            ->setShipping($shipping)
            ->setTotalAmount($totalAmount);
    }
}
