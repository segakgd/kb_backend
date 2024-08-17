<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\Response\Fields;

use App\Controller\AbstractResponse;
use App\Entity\Lead\DealContacts;
use Exception;

class LeadContactsRespDto extends AbstractResponse
{
    public ?string $fullName = null;

    public ?string $phone = null;

    public ?string $mail = null;

    /**
     * @throws Exception
     */
    public static function mapFromEntity(object $entity): static
    {
        if (!$entity instanceof DealContacts) {
            throw new Exception('Entity with undefined type.');
        }

        $response = new static();

        $response->fullName = ($entity->getFirstName() ?? '') . ' ' . ($entity->getLastName() ?? '');
        $response->phone = $entity->getPhone();
        $response->mail = $entity->getEmail();

        return $response;
    }
}
