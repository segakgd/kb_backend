<?php

namespace App\Tests\Functional\Trait\Deal;

use App\Entity\Lead\Deal;
use App\Entity\User\Project;
use Doctrine\Persistence\ObjectManager;

trait DealTrait
{
    public function createDeal(ObjectManager $manager, Project $project): Deal
    {
        $deal = (new Deal())
            ->setProjectId($project->getId())
        ;


        $manager->persist($deal);
        $manager->flush($deal);
//        dd($deal);

        return $deal;
    }
}
