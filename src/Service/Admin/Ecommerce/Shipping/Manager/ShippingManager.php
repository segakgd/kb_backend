<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\Shipping\Manager;

use App\Controller\Admin\Shipping\DTO\Request\ShippingReqDto;
use App\Entity\Ecommerce\Shipping;
use App\Entity\User\Project;
use App\Helper\Ecommerce\Shipping\ShippingHelper;
use App\Service\Admin\Ecommerce\Shipping\Service\ShippingService;

readonly class ShippingManager implements ShippingManagerInterface
{
    public function __construct(private ShippingService $shippingService) {}

    public function create(ShippingReqDto $shippingReqDto, Project $project): Shipping
    {
        $this->adjustShippingValues($shippingReqDto);

        $shipping = ShippingHelper::mapRequestToEntity($shippingReqDto)->setProjectId($project->getId());

        $this->shippingService->create($shipping);

        return $shipping;
    }

    public function delete(Shipping $shipping): void
    {
        $this->shippingService->remove($shipping);
    }

    public function update(ShippingReqDto $shippingReqDto, Shipping $shipping, Project $project): void
    {
        $this->adjustShippingValues($shippingReqDto);

        ShippingHelper::mapRequestToExistingEntity($shippingReqDto, $shipping);

        $this->shippingService->save($shipping);
    }

    public function getAllByByProject(Project $project): array
    {
        return $this->shippingService->findAllByProjectId($project->getId());
    }

    private function adjustShippingValues(ShippingReqDto $shippingReqDto): void
    {
        if (false !== $shippingReqDto->isNotFixed()) {
            $shippingReqDto
                ->setApplyToAmount(null)
                ->setApplyFromAmount(null)
                ->setPrice(null)
                ->setFreeFrom(null);
        }
    }
}
