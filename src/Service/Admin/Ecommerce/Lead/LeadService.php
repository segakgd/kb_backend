<?php

namespace App\Service\Admin\Ecommerce\Lead;

use App\Repository\Lead\DealEntityRepository;

class LeadService implements LeadServiceInterface
{
    public function __construct(
        public readonly DealEntityRepository $dealEntityRepository,
    ) {
    }
}
