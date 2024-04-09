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

        return $this->save($dealField);
    }

    /**
     * @param Deal $deal
     * @param LeadFieldReqDto[] $leadDtoFields
     * @return void
     */
    public function handleUpdate(Deal $deal, array $leadDtoFields): void
    {
        $existingFields = $updatingFields = $updatingFieldIds = [];

        foreach ($deal->getFields()->toArray() as $dealField) {
            $existingFields[$dealField->getId()] = $dealField;
        }

        foreach ($leadDtoFields as $dtoField) {
            if (null === $dtoField->getId()) {
                $fieldEntity = (new DealField())
                    ->setDeal($deal)
                    ->setName($dtoField->getName())
                    ->setValue($dtoField->getValue());

                $updatingFields[] = $fieldEntity;
            } else {
                $updatingFieldIds[] = $dtoField->getId();
                $fieldEntity = $existingFields[$dtoField->getId()] ?? null;

                if (null !== $fieldEntity) {
                    $fieldEntity
                        ->setName($dtoField->getName())
                        ->setValue($dtoField->getValue());

                    $updatingFields[] = $fieldEntity;
                }
            }
        }

        $removingIds = array_diff(array_keys($existingFields), $updatingFieldIds);

        foreach ($updatingFields as $field) { // todo -> change
            $this->save($field);
        }

        $this->fieldEntityRepository->removeFieldsByIds($removingIds);
    }

    private function save(DealField $dealField): DealField
    {
        $this->fieldEntityRepository->saveAndFlush($dealField);

        return $dealField;
    }
}
