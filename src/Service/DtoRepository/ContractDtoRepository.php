<?php

namespace App\Service\DtoRepository;

use App\Entity\Visitor\VisitorEvent;
use App\Service\System\Contract;
use Symfony\Component\Serializer\SerializerInterface;

class ContractDtoRepository
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function save(VisitorEvent $visitorEvent, Contract $dto): void
    {
        $normalizeDto = $this->serializer->normalize($dto);

        $visitorEvent->setContract($normalizeDto);
    }
}
