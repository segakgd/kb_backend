<?php

namespace App\Controller\Admin\Project\Response;

use App\Controller\AbstractResponse;
use App\Entity\User\Enum\ProjectStatusEnum;
use App\Entity\User\Project;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectResponse extends AbstractResponse
{
    public int $id;

    public string $name;

    #[Assert\Choice([ProjectStatusEnum::Active->value, ProjectStatusEnum::Frozen->value, ProjectStatusEnum::Blocked->value])]
    public string $status;

    public ?string $activeTo;

    public ?string $activeFrom;

    /**
     * @throws Exception
     */
    public static function mapFromEntity(object $entity): static
    {
        if (!$entity instanceof Project) {
            throw new Exception('Entity with undefined type.');
        }

        $response = new static();

        $response->id = $entity->getId();
        $response->name = $entity->getName();
        $response->status = $entity->getStatus()->value;
        $response->activeFrom = $entity->getActiveFrom()?->format('Y-m-d h:i:s');
        $response->activeTo = $entity->getActiveTo()?->format('Y-m-d h:i:s');

        return $response;
    }
}
