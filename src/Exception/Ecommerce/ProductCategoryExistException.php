<?php

declare(strict_types=1);

namespace App\Exception\Ecommerce;

use Symfony\Component\HttpFoundation\Response;

class ProductCategoryExistException extends EcommerceException
{
    public function __construct(string $message)
    {
        parent::__construct(message: $message, code: Response::HTTP_BAD_REQUEST);
    }
}
