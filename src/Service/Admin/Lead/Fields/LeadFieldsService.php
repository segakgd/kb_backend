<?php

declare(strict_types=1);

namespace App\Service\Admin\Lead\Fields;

use App\Controller\Admin\Lead\DTO\Request\Field\LeadFieldReqDto;
use App\Entity\Lead\Deal;
use App\Entity\Lead\DealField;
use App\Repository\Lead\FieldEntityRepository;

class LeadFieldsService
{
    public function __construct(private readonly FieldEntityRepository $fieldEntityRepository,)
    {
    }

    public function add(Deal $deal, LeadFieldReqDto $leadFieldReqDto): DealField
    {
        $dealField = (new DealField())
            ->setDeal($deal)
            ->setName($leadFieldReqDto->getName())
            ->setValue((string)$leadFieldReqDto->getValue());

        return $this->saveToDb($dealField);
    }

    private function saveToDb(DealField $dealField): DealField
    {
        $this->fieldEntityRepository->saveAndFlush($dealField);

        return $dealField;
    }
}
