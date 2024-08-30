<?php

namespace App\Exception\Security;

use Exception;

class UserExistException extends Exception
{
    public function __construct(string $message = 'User already exist')
    {
        parent::__construct($message);
    }
}
