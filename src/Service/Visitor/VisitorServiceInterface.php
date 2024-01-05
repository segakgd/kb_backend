<?php

namespace App\Service\Visitor;

use App\Entity\Visitor\Visitor;

interface VisitorServiceInterface
{
    public function createVisitor(): Visitor;
}
