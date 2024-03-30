<?php

namespace App\Service\System\Resolver\Chains;


use App\Service\System\Resolver\Chains\Items\OneChain;
use App\Service\System\Resolver\Chains\Items\TooChain;

class Event
{
    public function getActualChain(): AbstractChain
    {
        return new OneChain();
    }

    public function getNextChain(): AbstractChain
    {
        return new TooChain();
    }
}
