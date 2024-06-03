<?php

namespace App\Controller\Admin\Promotion\Exception;

use Exception;

class NotFoundPromotionForProjectException extends Exception
{
    public function __construct() {
        parent::__construct('Project does not belong to this resource.');
    }
}