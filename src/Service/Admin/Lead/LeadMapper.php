<?php

declare(strict_types=1);

namespace App\Service\Admin\Lead;

use App\Controller\Admin\Lead\DTO\Response\Fields\LeadContactsRespDto;
use App\Controller\Admin\Lead\DTO\Response\Fields\LeadFieldRespDto;
use App\Controller\Admin\Lead\DTO\Response\LeadRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\OrderRespDto;
use App\Entity\Lead\Deal;
use Doctrine\ORM\EntityManagerInterface;

class LeadMapper
{
    public function __construct(private readonly EntityManagerInterface $manager,)
    {
    }

    public function mapToResponse(Deal $deal): LeadRespDto
    {
        $leadContactsRespDto = $this->mapContactsToResponse($deal);
        $fieldsRespArray = $this->mapFieldsToResponse($deal);
        $orderDto = $this->mapOrderToResponse($deal);

        return (new LeadRespDto())
            ->setContacts($leadContactsRespDto)
            ->setFields($fieldsRespArray)
            ->setNumber($deal->getId())
            ->setOrder($orderDto);
    }

    private function mapContactsToResponse(Deal $deal): LeadContactsRespDto
    {
        $leadContactsRespDto = new LeadContactsRespDto();
        $contacts = $deal->getContacts();

        if (null === $contacts) {
            return $leadContactsRespDto;
        }

        $this->manager->refresh($contacts); // temporary fix

        if ($contacts->getEmail()) {
            $emailField = (new LeadFieldRespDto())
                ->setName('email')
                ->setType('email')
                ->setValue($contacts->getEmail());

            $leadContactsRespDto->setMail($emailField);
        }

        if ($contacts->getPhone()) {
            $phoneField = (new LeadFieldRespDto())
                ->setName('phone')
                ->setType('phone')
                ->setValue($contacts->getPhone());

            $leadContactsRespDto->setPhone($phoneField);
        }

        if ($contacts->getLastName() || $contacts->getFirstName()) {
            $fullName = ($contacts->getFirstName() ?? '') . ' ' . ($contacts->getLastName() ?? '');

            $fullNameField = (new LeadFieldRespDto())
                ->setName('fullName')
                ->setType('full_name')
                ->setValue($fullName);

            $leadContactsRespDto->setFullName($fullNameField);
        }

        return $leadContactsRespDto;
    }

    private function mapFieldsToResponse(Deal $deal): array
    {
        $fields = $deal->getFields();

        $fieldsArray = [];

        foreach ($fields as $field) {
            $fieldDto = (new LeadFieldRespDto())
                ->setType('string')
                ->setValue($field->getValue())
                ->setName($field->getName());

            $fieldsArray[] = $fieldDto;
        }

        return $fieldsArray;
    }

    private function mapOrderToResponse(Deal $deal
    ): OrderRespDto // надо подумать тут, а то еще непонятно, какое дто лежит в ордере в базе
    {
        return new OrderRespDto();
    }
}
