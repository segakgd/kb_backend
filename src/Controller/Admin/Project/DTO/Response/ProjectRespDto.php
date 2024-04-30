<?php

namespace App\Controller\Admin\Project\DTO\Response;

use App\Controller\Admin\Project\DTO\Response\Statistic\ProjectStatisticsRespDto;
use App\Entity\User\Project;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectRespDto
{
    private int $id;

    private string $name;

    #[Assert\Choice([Project::STATUS_ACTIVE, Project::STATUS_FROZEN, Project::STATUS_BLOCKED])]
    private string $status;

    private ?DateTimeImmutable $activeTo;

    private ?DateTimeImmutable $activeFrom;

    private ProjectStatisticsRespDto $statistic;

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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getActiveTo(): ?DateTimeImmutable
    {
        return $this->activeTo;
    }

    public function getFormatActiveFrom(): ?string
    {
        return $this->activeFrom?->format('Y-m-d h:i:s');
    }

    public function setActiveTo(?DateTimeImmutable $activeTo): self
    {
        $this->activeTo = $activeTo;

        return $this;
    }

    public function getActiveFrom(): ?DateTimeImmutable
    {
        return $this->activeFrom;
    }

    public function getFormatActiveTo(): ?string
    {
        return $this->activeTo?->format('Y-m-d h:i:s');
    }

    public function setActiveFrom(?DateTimeImmutable $activeFrom): self
    {
        $this->activeFrom = $activeFrom;

        return $this;
    }

    public function getStatistic(): ProjectStatisticsRespDto
    {
        return $this->statistic;
    }

    public function setStatistic(ProjectStatisticsRespDto $statistic): self
    {
        $this->statistic = $statistic;

        return $this;
    }
}
