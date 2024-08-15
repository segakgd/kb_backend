<?php

declare(strict_types=1);

namespace App\Service\Common\Ecommerce\Shipping\Manager;

use App\Controller\Admin\Shipping\Request\ShippingReqDto;
use App\Entity\Ecommerce\Shipping;
use App\Entity\User\Project;

interface ShippingManagerInterface
{
    public function create(ShippingReqDto $shippingReqDto, Project $project);

    public function delete(Shipping $shipping): void;

    public function update(ShippingReqDto $shippingReqDto, Shipping $shipping, Project $project);

    public function getAllByByProject(Project $project): array;
}
