<?php

namespace App\Exception\Reflection;

use Exception;
use Throwable;

class UndefinedReflectionException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
