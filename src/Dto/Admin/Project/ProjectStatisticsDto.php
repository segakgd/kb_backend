<?php

namespace App\Dto\Admin\Project;

class ProjectStatisticsDto
{
    private ProjectStatisticDto $lead;

    private ProjectStatisticDto $booking;

    private ProjectStatisticDto $chats;

    public function getLead(): ProjectStatisticDto
    {
        return $this->lead;
    }

    public function setLead(ProjectStatisticDto $lead): void
    {
        $this->lead = $lead;
    }

    public function getBooking(): ProjectStatisticDto
    {
        return $this->booking;
    }

    public function setBooking(ProjectStatisticDto $booking): void
    {
        $this->booking = $booking;
    }

    public function getChats(): ProjectStatisticDto
    {
        return $this->chats;
    }

    public function setChats(ProjectStatisticDto $chats): void
    {
        $this->chats = $chats;
    }
}
