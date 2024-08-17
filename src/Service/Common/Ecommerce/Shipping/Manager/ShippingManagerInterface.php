<?php

declare(strict_types=1);

namespace App\Service\Common\Ecommerce\Shipping\Manager;

use App\Controller\Admin\Shipping\Request\ShippingRequest;
use App\Entity\Ecommerce\Shipping;
use App\Entity\User\Project;

interface ShippingManagerInterface
{
    public function create(ShippingRequest $shippingReqDto, Project $project);

    public function delete(Shipping $shipping): void;

    public function update(ShippingRequest $shippingReqDto, Shipping $shipping, Project $project);

    public function getAllByByProject(Project $project): array;
}
