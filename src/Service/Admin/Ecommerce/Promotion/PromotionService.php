<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\Promotion;

use App\Entity\Ecommerce\Promotion;
use App\Repository\Ecommerce\PromotionRepository;

class PromotionService implements PromotionServiceInterface
{
    public function __construct(private readonly PromotionRepository $promotionRepository)
    {

    }

    public function findByIdAndProjectId(int $id, int $projectId): ?Promotion
    {
        return $this->promotionRepository->findOneBy(['id' => $id, 'projectId' => $projectId]);

    }
}
