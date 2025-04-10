<?php

declare(strict_types=1);

namespace App\Controller\Admin\Product\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ProductCategoryRequest
{
    #[Assert\NotBlank]
    private int $id;

    #[Assert\NotBlank]
    private string $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

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
