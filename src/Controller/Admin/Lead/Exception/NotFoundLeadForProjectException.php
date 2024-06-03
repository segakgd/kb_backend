<?php

namespace App\Controller\Admin\Lead\Exception;

use Exception;

class NotFoundLeadForProjectException extends Exception
{
    public function __construct() {
        parent::__construct('Project does not belong to this resource.');
    }
}