<?php

namespace App\Service\Admin\Ecommerce\Promotion;

use App\Dto\Ecommerce\PromotionDto;
use App\Entity\Ecommerce\Promotion;

interface PromotionManagerInterface
{
    public function getOne(int $projectId, int $promotionId): ?Promotion;

    public function getAll(int $projectId): array;

    public function add(PromotionDto $promotionDto, int $projectId): Promotion;

    public function update(PromotionDto $promotionDto, int $projectId, int $promotionId): Promotion;

    public function remove(int $projectId, int $promotionId): bool;
}