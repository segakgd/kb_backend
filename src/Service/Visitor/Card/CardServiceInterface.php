<?php

namespace App\Service\Visitor\Card;

use App\Dto\deprecated\CartDto;

interface CardServiceInterface
{
    public function recalculate(CartDto $cartDto): CartDto;
}
