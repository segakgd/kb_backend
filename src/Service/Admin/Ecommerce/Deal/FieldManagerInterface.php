<?php

namespace App\Service\Admin\Ecommerce\Deal;

use App\Dto\deprecated\Ecommerce\FieldDto;
use App\Entity\Lead\DealField;

interface FieldManagerInterface
{
    public function add(FieldDto $dto): DealField;

    public function update(FieldDto $dto): ?DealField;
}
