<?php

namespace App\Controller\Admin\Project\DTO\Response\Statistic;

class ProjectStatisticsRespDto
{
    private ProjectStatisticRespDto $lead;

    private ProjectStatisticRespDto $booking;

    private ProjectStatisticRespDto $chats;

    public function getLead(): ProjectStatisticRespDto
    {
        return $this->lead;
    }

    public function setLead(ProjectStatisticRespDto $lead): self
    {
        $this->lead = $lead;

        return $this;
    }

    public function getBooking(): ProjectStatisticRespDto
    {
        return $this->booking;
    }

    public function setBooking(ProjectStatisticRespDto $booking): self
    {
        $this->booking = $booking;

        return $this;
    }

    public function getChats(): ProjectStatisticRespDto
    {
        return $this->chats;
    }

    public function setChats(ProjectStatisticRespDto $chats): self
    {
        $this->chats = $chats;

        return $this;
    }
}
