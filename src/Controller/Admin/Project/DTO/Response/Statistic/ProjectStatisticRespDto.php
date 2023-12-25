<?php

namespace App\Controller\Admin\Project\DTO\Response\Statistic;

class ProjectStatisticRespDto
{
    private int $count;

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
