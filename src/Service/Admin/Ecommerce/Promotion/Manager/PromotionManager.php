<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\Promotion\Manager;

use App\Controller\Admin\Promotion\DTO\Request\PromotionReqDto;
use App\Entity\Ecommerce\Promotion;
use App\Entity\User\Project;
use App\Helper\Ecommerce\Promotion\PromotionHelper;
use App\Service\Admin\Ecommerce\Promotion\PromotionService;

class PromotionManager implements PromotionManagerInterface
{
    public function __construct(
        private readonly PromotionService $promotionService
    ) {
    }

    public function create(PromotionReqDto $promotionReqDto, Project $project): Promotion
    {
        $promotion = PromotionHelper::mapRequestToEntity($promotionReqDto)->setProjectId($project->getId());

        return $this->promotionService->create($promotion);
    }

    public function update(PromotionReqDto $promotionReqDto, Promotion $promotion, Project $project): Promotion
    {
        $promotion = PromotionHelper::mapRequestToExistingEntity($promotionReqDto, $promotion)
            ->setProjectId($promotion->getProjectId())
        ;

        return $this->promotionService->update($promotion);
    }

    public function delete(Promotion $promotion): void
    {
        $this->promotionService->delete($promotion);
    }

    public function getAllByProject(Project $project): array
    {
        return $this->promotionService->getAllByProject($project);
    }
}
