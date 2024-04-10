<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\Shipping;

use App\Entity\Ecommerce\Shipping;
use App\Repository\Ecommerce\ShippingEntityRepository;

class ShippingService implements ShippingServiceInterface
{
    public function __construct(private readonly ShippingEntityRepository $shippingEntityRepository)
    {
    }

    public function getById(int $id): ?Shipping
    {
        return $this->shippingEntityRepository->find($id);
    }

    public function findByIdAndProjectId(int $id, int $projectId): ?Shipping
    {
        return $this->shippingEntityRepository->findOneBy(['id' => $id, 'projectId' => $projectId]);
    }
}
