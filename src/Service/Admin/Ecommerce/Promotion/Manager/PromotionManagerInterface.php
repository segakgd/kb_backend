<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\Promotion\Manager;

use App\Controller\Admin\Promotion\DTO\Request\PromotionReqDto;
use App\Entity\Ecommerce\Promotion;
use App\Entity\User\Project;

interface PromotionManagerInterface
{
    public function create(PromotionReqDto $promotionReqDto, Project $project): Promotion;

    public function update(PromotionReqDto $promotionReqDto, Promotion $promotion, Project $project): Promotion;

    public function delete(Promotion $promotion): void;

    public function getAllByProject(Project $project): array;
}
