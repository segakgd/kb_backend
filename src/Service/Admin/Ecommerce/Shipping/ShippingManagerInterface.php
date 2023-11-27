<?php

namespace App\Service\Admin\Ecommerce\Shipping;

use App\Dto\Ecommerce\ShippingDto;
use App\Entity\Ecommerce\Shipping;

interface ShippingManagerInterface
{
    public function getOne(int $projectId, int $shippingId): ?Shipping;

    public function getAll(int $projectId): array;

    public function add(ShippingDto $shippingDto, int $projectId): Shipping;

    public function update(ShippingDto $shippingDto, int $projectId, int $shippingId): Shipping;

    public function remove(int $projectId, int $shippingId): bool;
}