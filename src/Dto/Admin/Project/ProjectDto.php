<?php

namespace App\Dto\Admin\Project;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectDto
{
    private string $name;

    private string $link;

    #[Assert\Choice(['active', 'frozen', 'blocked'])]
    private string $status;

    private DateTimeImmutable $activeTo;

    private DateTimeImmutable $activeFrom;

    private ProjectStatisticsDto $statistic;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getActiveTo(): DateTimeImmutable
    {
        return $this->activeTo;
    }

    public function setActiveTo(DateTimeImmutable $activeTo): void
    {
        $this->activeTo = $activeTo;
    }

    public function getActiveFrom(): DateTimeImmutable
    {
        return $this->activeFrom;
    }

    public function setActiveFrom(DateTimeImmutable $activeFrom): void
    {
        $this->activeFrom = $activeFrom;
    }

    public function getStatistic(): ProjectStatisticsDto
    {
        return $this->statistic;
    }

    public function setStatistic(ProjectStatisticsDto $statistic): void
    {
        $this->statistic = $statistic;
    }
}
