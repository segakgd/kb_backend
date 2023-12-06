<?php

namespace App\Dto\Admin\Project\Response\Statistic;

class ProjectStatisticRespDto
{
    private int $count;

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }
}
