<?php
namespace App\Services;

use DateTime;

class SearchCalendar 
{
    /**
     * @var DateTime
     */
    private $dateAppointment;

    /**
     * @return \DateTime
     */
    public function getDateAppointment(): ?\DateTime
    {
        return $this->dateAppointment;
    }

    /**
     * @param \DateTime $dateAppointment
     */
    public function setDateAppointment(\DateTime $dateAppointment): void
    {
        $this->dateAppointment = $dateAppointment;
    }

}