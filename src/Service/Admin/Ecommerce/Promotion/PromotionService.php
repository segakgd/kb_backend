<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\Promotion;

use App\Entity\Ecommerce\Promotion;
use App\Entity\User\Project;
use App\Repository\Ecommerce\PromotionRepository;

class PromotionService implements PromotionServiceInterface
{
    public function __construct(private readonly PromotionRepository $promotionRepository)
    {
    }

    public function create(Promotion $promotion): Promotion
    {
        $this->promotionRepository->saveAndFlush($promotion);

        return $promotion;
    }

    public function update(Promotion $promotion): Promotion
    {
        $this->promotionRepository->saveAndFlush($promotion);

        return $promotion;
    }

    public function delete(Promotion $promotion): void
    {
        $this->promotionRepository->removeAndFlush($promotion);
    }

    public function getAllByProject(Project $project): array
    {
        return $this->promotionRepository->findBy(['projectId' => $project->getId()]);
    }

    public function getById(int $id): ?Promotion
    {
        return $this->promotionRepository->find($id);
    }

    public function findByIdAndProjectId(int $id, int $projectId): ?Promotion
    {
        return $this->promotionRepository->findOneBy(['id' => $id, 'projectId' => $projectId]);
    }
}
