<?php

namespace App\Service\Admin\Statistic;

use App\Controller\Admin\Project\DTO\Response\Statistic\ProjectStatisticRespDto;
use App\Controller\Admin\Project\DTO\Response\Statistic\ProjectStatisticsRespDto;

class StatisticsService implements StatisticsServiceInterface
{
    /**
     * Пока что возвращаем фейковые данные
     */
    public function getStatisticForProject(): ProjectStatisticsRespDto
    {
        $fakeStatistic = (new ProjectStatisticRespDto())
            ->setCount(13);

        return (new ProjectStatisticsRespDto())
            ->setChats($fakeStatistic)
            ->setLead($fakeStatistic)
            ->setBooking($fakeStatistic);
    }
}
