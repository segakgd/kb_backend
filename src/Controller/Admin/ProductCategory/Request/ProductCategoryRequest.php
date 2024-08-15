<?php

namespace App\Controller\Admin\ProductCategory\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ProductCategoryRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
