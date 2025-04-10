<?php

declare(strict_types=1);

namespace App\Service\Common\Ecommerce\Promotion\Manager;

use App\Controller\Admin\Promotion\Mapper\PromotionMapper;
use App\Controller\Admin\Promotion\Request\PromotionRequest;
use App\Entity\Ecommerce\Promotion;
use App\Entity\User\Project;
use App\Service\Common\Ecommerce\Promotion\Service\PromotionService;

readonly class PromotionManager implements PromotionManagerInterface
{
    public function __construct(
        private PromotionService $promotionService
    ) {}

    public function create(PromotionRequest $promotionReqDto, Project $project): Promotion
    {
        $promotion = PromotionMapper::mapRequestToEntity($promotionReqDto)->setProjectId($project->getId());

        return $this->promotionService->create($promotion);
    }

    public function update(PromotionRequest $promotionReqDto, Promotion $promotion, Project $project): Promotion
    {
        $promotion = PromotionMapper::mapRequestToExistingEntity($promotionReqDto, $promotion)
            ->setProjectId($promotion->getProjectId());

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
