<?php

namespace Louvre\BilletBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Calendar
 *
 * @ORM\Table(name="calendar")
 * @ORM\Entity(repositoryClass="Louvre\BilletBundle\Repository\CalendarRepository")
 */
class Calendar
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Date
     *
     * @ORM\Column(name="day", type="date", unique=true, nullable=false)
     * @Assert\Date()
     */
    private $day;

    /**
     * @var int
     *
     * @ORM\Column(name="bookings", type="integer", nullable=false)
     */
    private $bookings;

    private $bookingType;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set day
     *
     * @param \Date $day
     *
     * @return Calendar
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return \Date
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set bookings
     *
     * @param integer $bookings
     *
     * @return Calendar
     */
    public function setBookings($bookings)
    {
        $this->bookings = $bookings;

        return $this;
    }

    /**
     * Get bookings
     *
     * @return int
     */
    public function getBookings()
    {
        return $this->bookings;
    }

    /**
     * Set bookingType
     *
     * @param string $bookingType
     *
     * @return booking
     */
    public function setBookingType($bookingType)
    {
        $this->bookingType = $bookingType;

        return $this;
    }

    /**
     * Get bookingType
     *
     * @return string
     */
    public function getBookingType()
    {
        return $this->bookingType;
    }
}

