<?php

namespace App\Controller\Admin\Product\DTO\Response;

class AllProductResponse
{
    private int $id;

    private string $name;

    private string $article;

    private bool $visible;

    private string $type;

    // Доступные цены - цена может быть от 100 до 1000, зависит от вариантов. Учитывая что это респонс, то пусть так и будет.
    private string $affordablePrices;

    private int $count;

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

    public function getArticle(): string
    {
        return $this->article;
    }

    public function setArticle(string $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAffordablePrices(): string
    {
        return $this->affordablePrices;
    }

    public function setAffordablePrices(string $affordablePrices): self
    {
        $this->affordablePrices = $affordablePrices;

        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }
}
