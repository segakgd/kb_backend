<?php

namespace App\Service\Reflection;

use App\Service\Reflection\Method\GetMethod;
use App\Service\Reflection\Method\SetMethod;

class MethodManager
{
    const METHOD_LENGTH = 3;

    const GET_METHOD = 'get';

    const SET_METHOD = 'set';

    public static function getMethod(string $name, array $arg = []): GetMethod|SetMethod|null
    {
        $var = self::findVar($name);
        $var = lcfirst($var);

        if (self::isGet($name)){
            return new GetMethod($var);
        }

        if (self::isSet($name)){
            return new SetMethod($var, $arg);
        }

        return null;
    }

    private static function findVar($name): ?string
    {
        return substr($name, self::METHOD_LENGTH) ?? null;
    }

    private static function isSet($name): bool
    {
        return self::isMethod($name, self::SET_METHOD);
    }

    private static function isGet($name): bool
    {
        return self::isMethod($name, self::GET_METHOD);
    }

    private static function isMethod($methodName, $find): bool
    {
        return str_contains($methodName, $find);
    }
}
