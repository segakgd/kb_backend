<?php

declare(strict_types=1);

namespace App\Service\Admin\Lead;

use App\Controller\Admin\Lead\DTO\Request\LeadReqDto;
use App\Entity\Lead\Deal;
use App\Entity\User\Project;
use App\Service\Admin\Lead\Contacts\LeadContactService;
use App\Service\Admin\Lead\Fields\LeadFieldsService;
use App\Service\Admin\Lead\Order\OrderChecker;
use App\Service\Admin\Lead\Order\OrderService;
use Exception;

class LeadManager
{
    public function __construct(
        private readonly LeadService $leadService,
        private readonly LeadContactService $contactService,
        private readonly LeadFieldsService $fieldsService,
        private readonly OrderService $orderService,
        private readonly OrderChecker $orderChecker,
    ) {
    }

    public function getAllByProject(Project $project): array
    {
        return $this->leadService->getAllByProject($project);
    }

    /**
     * @throws Exception
     */
    public function create(LeadReqDto $leadDto, Project $project): Deal
    {
        $orderDto = $leadDto->getOrder();

        if ($orderDto) {
            $this->orderChecker->checkOrderRequestByDtoAndProject($orderDto, $project);

            $orderEntity = $this->orderService->add($orderDto);
        }

        if ($leadDto->getContacts()) {
            $contactsEntity = $this->contactService->add($leadDto->getContacts());
        }

        $deal = $this->leadService->add($project, $contactsEntity ?? null, $orderEntity ?? null);

        foreach ($leadDto->getFields() as $fieldDto) {
            $this->fieldsService->add($deal, $fieldDto);
        }

        return $deal;
    }

    /**
     * @throws Exception
     */
    public function update(LeadReqDto $leadDto, Deal $deal, Project $project): Deal
    {
        $orderDto = $leadDto->getOrder();

        if ($orderDto) {
            $this->orderChecker->checkOrderRequestByDtoAndProject($orderDto, $project);
            $this->orderService->updateOrCreate($orderDto, $deal->getOrder());
        }

        if ($leadDto->getContacts()) {
            $this->contactService->updateOrCreate($leadDto->getContacts(), $deal->getContacts());
        }

        $this->fieldsService->handleBatchUpdate($deal, $leadDto->getFields());

        $this->leadService->save($deal);

        return $deal;
    }

    public function remove(Deal $deal): void
    {
        $this->leadService->remove($deal);
    }
}
