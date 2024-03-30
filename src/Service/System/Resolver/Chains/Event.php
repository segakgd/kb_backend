<?php

namespace App\Service\System\Resolver\Chains;


use App\Service\System\Resolver\Chains\Items\C1Chain;
use App\Service\System\Resolver\Chains\Items\C2Chain;

class Event
{
    public function getActualChain(): AbstractChain
    {
        return new C1Chain();
    }

    public function getNextChain(): AbstractChain
    {
        return new C2Chain();
    }
}
