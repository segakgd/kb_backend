<?php

declare(strict_types=1);

namespace App\Service\Common\Ecommerce\Promotion\Manager;

use App\Controller\Admin\Promotion\Request\PromotionRequest;
use App\Entity\Ecommerce\Promotion;
use App\Entity\User\Project;

interface PromotionManagerInterface
{
    public function create(PromotionRequest $promotionReqDto, Project $project): Promotion;

    public function update(PromotionRequest $promotionReqDto, Promotion $promotion, Project $project): Promotion;

    public function delete(Promotion $promotion): void;

    public function getAllByProject(Project $project): array;
}
