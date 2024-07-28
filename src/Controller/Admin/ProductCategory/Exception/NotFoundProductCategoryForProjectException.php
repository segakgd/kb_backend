<?php

namespace App\Controller\Admin\ProductCategory\Exception;

use Exception;

class NotFoundProductCategoryForProjectException extends Exception
{
    public function __construct()
    {
        parent::__construct('Project does not belong to this resource.');
    }
}
