<?php

declare(strict_types=1);

namespace App\Service\Admin\Lead;

use App\Entity\Lead\Deal;
use App\Entity\Lead\DealContacts;
use App\Entity\Lead\DealOrder;
use App\Entity\User\Project;
use App\Repository\Lead\DealEntityRepository;

class LeadService implements LeadServiceInterface
{
    public function __construct(
        public readonly DealEntityRepository $dealEntityRepository,
    ){
    }

    public function remove(Deal $deal): void
    {
        $this->dealEntityRepository->removeAndFlush($deal);
    }

    public function add(Project $project, ?DealContacts $contacts = null, ?DealOrder $order = null): Deal
    {
        $deal = (new Deal())
            ->setProjectId($project->getId())
            ->setContacts($contacts)
            ->setOrder($order)
        ;

        return $this->saveToDb($deal);
    }

    public function getAllByProject(Project $project): array
    {
        return $this->dealEntityRepository->findBy(['projectId' => $project->getId()]);
    }

    private function saveToDb(Deal $deal): Deal
    {
        $this->dealEntityRepository->saveAndFlush($deal);

        return $deal;
    }
}
