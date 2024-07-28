<?php

namespace App\Controller\Admin\Shipping\Exception;

use Exception;

class NotFoundShippingForProjectException extends Exception
{
    public function __construct()
    {
        parent::__construct('Project does not belong to this resource.');
    }
}
