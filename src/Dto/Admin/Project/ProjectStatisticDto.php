<?php

namespace App\Dto\Admin\Project;

class ProjectStatisticDto
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
