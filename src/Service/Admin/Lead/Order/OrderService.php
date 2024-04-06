<?php

declare(strict_types=1);

namespace App\Service\Admin\Lead\Order;

use App\Controller\Admin\Lead\DTO\Request\Order\OrderReqDto;
use App\Entity\Lead\DealOrder;
use App\Entity\User\Project;
use App\Repository\Lead\OrderEntityRepository;
use Doctrine\Common\Collections\Order;

class OrderService
{
    public function __construct(private readonly OrderEntityRepository $orderEntityRepository)
    {
    }

    public function add(OrderReqDto $dto): DealOrder
    {
        $order = new DealOrder();

        $order
            ->setShipping($dto->getShipping())
            ->setProducts($dto->getProducts())
            ->setPromotions($dto->getPromotions())
            ->markAsUpdated();

        return $this->save($order);
    }

    private function save(DealOrder $order): DealOrder
    {
        $this->orderEntityRepository->saveAndFlush($order);

        return $order;
    }
}
