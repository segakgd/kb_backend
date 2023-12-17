<?php

namespace App\Controller\Admin\ProductCategory\DTO\Request;

class ProductCategoryReqDto
{
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
