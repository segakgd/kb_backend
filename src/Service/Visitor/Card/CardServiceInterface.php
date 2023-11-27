<?php

namespace App\Service\Visitor\Card;

use App\Dto\CartDto;

interface CardServiceInterface
{
    public function recalculate(CartDto $cartDto): CartDto;
}
