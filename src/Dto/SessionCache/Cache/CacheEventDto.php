<?php

namespace App\Dto\SessionCache\Cache;

use App\Dto\Common\AbstractDto;

class CacheEventDto extends AbstractDto
{
    private bool $finished = false;

    /** @var array<CacheStepDto> */
    private array $steps = [];

    private ?CacheDataDto $data = null;

    public function __construct()
    {
        if (!$this->data) {
            $this->data = new CacheDataDto;
        }
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): static
    {
        $this->finished = $finished;

        return $this;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function isEmptySteps(): bool
    {
        return empty($this->steps);
    }

    public function getCurrentStep(): ?CacheStepDto
    {
        if (empty($this->steps)) {
            return null;
        }

        foreach ($this->steps as $step) {
            if (!$step->isFinished()) {
                return $step;
            }
        }

        return null;
    }

    public function setSteps(array $steps): static
    {
        $this->steps = $steps;

        return $this;
    }

    public function addStep(CacheStepDto $step): static
    {
        $this->steps[] = $step;

        return $this;
    }

    public function getData(): CacheDataDto
    {
        return $this->data;
    }

    public function setData(CacheDataDto $data): static
    {
        $this->data = $data;

        return $this;
    }

    public static function fromArray(array $data): static
    {
        $event = new self();
        $event->finished = $data['finished'] ?? false;

        $steps = [];

        foreach ($data['steps'] ?? [] as $stepData) {
            $steps[] = CacheStepDto::fromArray($stepData);
        }

        $event->steps = $steps;

        $event->data = CacheDataDto::fromArray($data['data'] ?? []);

        return $event;
    }

    public function toArray(): array
    {
        $stepsArray = [];

        foreach ($this->steps as $step) {
            $stepsArray[] = $step->toArray();
        }

        return [
            'finished' => $this->finished,
            'steps' => $stepsArray,
            'data' => $this->data->toArray(),
        ];
    }
}
