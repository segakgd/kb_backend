<?php

namespace App\Service\Reflection\Method;

class GetMethod implements MethodInterface
{
    public function __construct(private string $var)
    {}

    public function apply(object $targetObject): mixed
    {
        $var = $this->var;

        return $targetObject->$var;
    }
}