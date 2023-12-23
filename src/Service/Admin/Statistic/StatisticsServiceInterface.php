<?php

namespace App\Service\Admin\Statistic;

use App\Controller\Admin\Project\DTO\Response\Statistic\ProjectStatisticsRespDto;

interface StatisticsServiceInterface
{
    public function getStatisticForProject(): ProjectStatisticsRespDto;
}
