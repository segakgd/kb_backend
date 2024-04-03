<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\Product\Manager;

use App\Controller\Admin\Product\DTO\Request\ProductReqDto;
use App\Entity\Ecommerce\Product;
use App\Entity\User\Project;

interface ProductManagerInterface
{
    public function create(ProductReqDto $productReqDto, Project $project): Product;

    public function remove(Product $product): void;
}
