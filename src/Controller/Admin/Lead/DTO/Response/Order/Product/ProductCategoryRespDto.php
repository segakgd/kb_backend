<?php

namespace App\Controller\Admin\Lead\DTO\Response\Order\Product;

class ProductCategoryRespDto
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
