<?php

namespace App\Service\Admin\Ecommerce\Lead;

use App\Entity\Lead\Deal;
use App\Repository\Lead\DealEntityRepository;

class LeadService implements LeadServiceInterface
{
    public function __construct(
        public readonly DealEntityRepository $dealEntityRepository,
    ) {
    }

    public function create()
    {
        $deal = ( new Deal())
            ->setProjectId()
            ->setContacts()
            ->setOrder()
        ;

        dd('asdas');
    }
}
