<?php

namespace App\Dto\Admin\Product;

class AllProductRespDto
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

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getArticle(): string
    {
        return $this->article;
    }

    public function setArticle(string $article): void
    {
        $this->article = $article;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getAffordablePrices(): string
    {
        return $this->affordablePrices;
    }

    public function setAffordablePrices(string $affordablePrices): void
    {
        $this->affordablePrices = $affordablePrices;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }
}
