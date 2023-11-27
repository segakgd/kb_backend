<?php

namespace App\Service\Reflection\Model;

use App\Service\Reflection\MethodManager;

abstract class AbstractReflection implements ReflectionInterface
{
    public function __call(string $name, array $arg = []): mixed
    {
        $method = MethodManager::getMethod($name, $arg);

        return $method?->apply($this);
    }

    public function map(string $json)
    {
        $data = $this->decode($json);

        if (!$data){
            return null;
        }

        foreach ($data as $dataKey => $dataValue){
            $this->$dataKey = $dataValue;
        }
    }

    public function decode($string): ?array
    {
        $decodeJson = json_decode($string, true);

        if (json_last_error() === JSON_ERROR_NONE){
            return $decodeJson;
        }

        return null;
    }
}
