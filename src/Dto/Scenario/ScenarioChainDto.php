<?php

namespace App\Dto\Scenario;

class ScenarioChainDto
{
    private ?array $before = null;

    private ?array $now = null;

    private ?array $after = null;

    public function getBefore(): ?array
    {
        return $this->before;
    }

    public function addBefore(?ScenarioChainItemDto $before): static
    {
        $this->before[] = $before;

        return $this;
    }

    public function getNow(): ?array
    {
        return $this->now;
    }

    public function addNow(?ScenarioChainItemDto $now): static
    {
        $this->now[] = $now;

        return $this;
    }

    public function getAfter(): ?array
    {
        return $this->after;
    }

    public function addAfter(?ScenarioChainItemDto $after): static
    {
        $this->after[] = $after;

        return $this;
    }
}
