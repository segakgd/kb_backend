<?php

declare(strict_types=1);

namespace App\Service\Common\Lead;

use App\Entity\Lead\Deal;
use App\Entity\Lead\DealContacts;
use App\Entity\Lead\DealOrder;
use App\Entity\User\Project;
use App\Repository\Lead\DealEntityRepository;

readonly class LeadService implements LeadServiceInterface
{
    public function __construct(
        public DealEntityRepository $dealEntityRepository,
    ) {}

    public function remove(Deal $deal): void
    {
        $this->dealEntityRepository->removeAndFlush($deal);
    }

    public function add(Project $project, ?DealContacts $contacts = null, ?DealOrder $order = null): Deal
    {
        $deal = (new Deal())
            ->setProjectId($project->getId())
            ->setContacts($contacts)
            ->setOrder($order);

        return $this->save($deal);
    }

    public function getAllByProject(Project $project): array
    {
        return $this->dealEntityRepository->findBy(['projectId' => $project->getId()]);
    }

    public function save(Deal $deal): Deal
    {
        $this->dealEntityRepository->saveAndFlush($deal);

        return $deal;
    }
}
