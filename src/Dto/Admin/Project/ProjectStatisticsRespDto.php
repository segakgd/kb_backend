<?php

namespace App\Dto\Admin\Project;

class ProjectStatisticsRespDto
{
    private ProjectStatisticRespDto $lead;

    private ProjectStatisticRespDto $booking;

    private ProjectStatisticRespDto $chats;

    public function getLead(): ProjectStatisticRespDto
    {
        return $this->lead;
    }

    public function setLead(ProjectStatisticRespDto $lead): void
    {
        $this->lead = $lead;
    }

    public function getBooking(): ProjectStatisticRespDto
    {
        return $this->booking;
    }

    public function setBooking(ProjectStatisticRespDto $booking): void
    {
        $this->booking = $booking;
    }

    public function getChats(): ProjectStatisticRespDto
    {
        return $this->chats;
    }

    public function setChats(ProjectStatisticRespDto $chats): void
    {
        $this->chats = $chats;
    }
}
