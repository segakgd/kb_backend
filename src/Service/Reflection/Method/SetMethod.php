<?php

namespace App\Service\Reflection\Method;

class SetMethod implements MethodInterface
{
    public function __construct(private string $var, private array $arg = [])
    {}

    public function apply(object $targetObject): mixed
    {
        $var = $this->var;

        if (!empty($this->arg[0])){
            $targetObject->$var = $this->arg[0];
        }

        return $targetObject;
    }
}