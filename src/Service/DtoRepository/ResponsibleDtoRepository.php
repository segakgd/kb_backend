<?php

namespace App\Service\DtoRepository;

use App\Entity\Visitor\VisitorEvent;
use App\Service\System\Resolver\Dto\Responsible;
use Symfony\Component\Serializer\SerializerInterface;

readonly class ResponsibleDtoRepository
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    public function save(VisitorEvent $visitorEvent, Responsible $dto): void
    {
        $normalizeDto = $this->serializer->normalize($dto);

        $visitorEvent->setResponsible($normalizeDto);
    }
}
