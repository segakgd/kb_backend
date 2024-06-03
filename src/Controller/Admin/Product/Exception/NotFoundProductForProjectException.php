<?php

namespace App\Controller\Admin\Product\Exception;

use Exception;

class NotFoundProductForProjectException extends Exception
{
    public function __construct() {
        parent::__construct('Project does not belong to this resource.');
    }
}