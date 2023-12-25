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

    public function identifyUser(int $chatId, string $channel): Visitor
    {
        $visitor = $this->visitorRepository->findOneBy(
            [
                'channelVisitorId' => $chatId,
                'channel' => $channel,
            ]
        );

        if (!$visitor){
            $visitor = $this->createVisitor($chatId, $channel);
        }

        return $visitor;
    }

    private function createVisitor(int $chatId, string $channel): Visitor
    {
        $visitor = (new Visitor())
            ->setName('Default') // todo !
            ->setChannelVisitorId($chatId)
            ->setChannel($channel)
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(new DateTimeImmutable())
        ;

        $this->visitorRepository->saveAndFlush($visitor);

        return $visitor;
    }
}
