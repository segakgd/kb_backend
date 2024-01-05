<?php

namespace App\Service\Visitor;

use App\Entity\Visitor\Visitor;
use App\Repository\Visitor\VisitorRepository;
use DateTimeImmutable;

class VisitorService implements VisitorServiceInterface
{
    public function __construct(
        private readonly VisitorRepository $visitorRepository
    ) {
    }

    public function createVisitor(): Visitor
    {
        $visitor = (new Visitor())
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(new DateTimeImmutable())
        ;

        $this->visitorRepository->saveAndFlush($visitor);

        return $visitor;
    }
}
