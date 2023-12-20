<?php

namespace App\Controller\Admin\Project\DTO\Response;

use App\Controller\Admin\Project\DTO\Response\Statistic\ProjectStatisticsRespDto;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectRespDto
{
    private string $name;

    #[Assert\Choice(['active', 'frozen', 'blocked'])]
    private string $status;

    private DateTimeImmutable $activeTo;

    private DateTimeImmutable $activeFrom;

    private ProjectStatisticsRespDto $statistic;

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

    public function getActiveTo(): DateTimeImmutable
    {
        return $this->activeTo;
    }

    public function setActiveTo(DateTimeImmutable $activeTo): self
    {
        $this->activeTo = $activeTo;

        return $this;
    }

    public function getActiveFrom(): DateTimeImmutable
    {
        return $this->activeFrom;
    }

    public function setActiveFrom(DateTimeImmutable $activeFrom): self
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
