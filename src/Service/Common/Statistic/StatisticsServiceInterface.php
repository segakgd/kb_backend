<?php

namespace App\Service\Common\Statistic;

use App\Controller\Admin\Project\DTO\Response\Statistic\ProjectStatisticsRespDto;

interface StatisticsServiceInterface
{
    public function getStatisticForProject(): ProjectStatisticsRespDto;
}
