<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\Shipping\Service;

use App\Entity\Ecommerce\Shipping;
use App\Repository\Ecommerce\ShippingEntityRepository;

readonly class ShippingService implements ShippingServiceInterface
{
    public function __construct(private ShippingEntityRepository $shippingEntityRepository)
    {
    }

    public function create(Shipping $shipping): Shipping
    {
        $this->shippingEntityRepository->saveAndFlush($shipping);

        return $shipping;
    }

    public function save(Shipping $shipping): Shipping
    {
        $this->shippingEntityRepository->saveAndFlush($shipping);

        return $shipping;
    }

    public function remove(Shipping $shipping): void
    {
        $this->shippingEntityRepository->removeAndFlush($shipping);
    }

    public function getById(int $id): ?Shipping
    {
        return $this->shippingEntityRepository->find($id);
    }

    public function findAllByProjectId(int $projectId): array
    {
        return $this->shippingEntityRepository->findBy(['projectId' => $projectId]);
    }

    public function findByIdAndProjectId(int $id, int $projectId): ?Shipping
    {
        return $this->shippingEntityRepository->findOneBy(['id' => $id, 'projectId' => $projectId]);
    }
}
