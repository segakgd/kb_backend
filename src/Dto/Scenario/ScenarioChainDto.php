<?php

namespace App\Dto\Scenario;

use App\Doctrine\DoctrineMappingInterface;

class ScenarioChainDto implements DoctrineMappingInterface
{
    private string $target;

    private array $requirements;

    private bool $isFinish = false; // todo rename isFinish -> finish

    public function getTarget(): string
    {
        return $this->target;
    }

    public function setTarget(string $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function setRequirements(array $requirements): static
    {
        $this->requirements = $requirements;

        return $this;
    }

    public function isFinish(): bool
    {
        return $this->isFinish;
    }

    public function setIsFinish(bool $isFinish): static
    {
        $this->isFinish = $isFinish;

        return $this;
    }

    public static function fromArray(array $data): static
    {
        $chain = new self();
        $chain->setTarget($data['target'] ?? '');
        $chain->setRequirements($data['requirements'] ?? []);
        $chain->setIsFinish($data['isFinish'] ?? false);

        return $chain;
    }

    public function toArray(): array
    {
        return [
            'target'       => $this->getTarget(),
            'requirements' => $this->getRequirements(),
            'isFinish'     => $this->isFinish(),
        ];
    }
}
