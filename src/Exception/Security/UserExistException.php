<?php

namespace App\Exception\Security;

use Exception;

class UserExistException extends Exception
{
    public function __construct()
    {
        parent::__construct('User already exist');
    }
}
