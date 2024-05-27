<?php

namespace App\Controller\Admin\Bot\Exception;

use Exception;

class NotFoundBotForProjectException extends Exception
{
    public function __construct() {
        parent::__construct('Project does not belong to this resource.');
    }
}