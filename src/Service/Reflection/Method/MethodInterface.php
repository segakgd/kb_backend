<?php

namespace App\Service\Reflection\Method;

interface MethodInterface
{
    public function apply(object $targetObject): mixed;
}