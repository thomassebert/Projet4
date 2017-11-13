<?php

namespace Louvre\BilletBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use Louvre\BilletBundle\Entity\ShoppingCart;
use Louvre\BilletBundle\Entity\Calendar;

/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="Louvre\BilletBundle\Repository\BookingRepository")
 */
class Booking
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
     * @ORM\ManyToOne(targetEntity="ShoppingCart", inversedBy="bookings")
     * @ORM\JoinColumn(name="shoppingCart_id", referencedColumnName="id")
     */
    private $shoppingCartId;

    /**
     * @var string
     *
     * @ORM\Column(name="ticket_price", type="string", length=255, nullable=false)
     */
    private $ticketPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="booking_type", type="string", length=255, nullable=false)
     * @Assert\NotBlank(
     *     message = "Le champ ne peut pas être vide."
     * )
     */
    private $bookingType;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner un nom."
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=false)
     * @Assert\NotBlank(
     *     message = "Veuillez renseigner un prénom."
     * )
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     * @Assert\NotBlank(
     *     message = "Le champ ne peut pas être vide."
     * )
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="Calendar")
     * @ORM\JoinColumn(name="calendar_id", referencedColumnName="id")
     */
    private $calendarId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date", nullable=false)
     * @Assert\DateTime()
     * 
     */
    private $birthDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="discount_ticket", type="boolean", nullable=true)
     */
    private $discountTicket;


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
     * Set shoppingCartId
     *
     * @param integer $shoppingCartId
     *
     * @return booking
     */
    public function setShoppingCartId(ShoppingCart $shoppingCartId)
    {
        $this->shoppingCartId = $shoppingCartId;

        return $this;
    }

    /**
     * Get shoppingCartId
     *
     * @return integer
     */
    public function getShoppingCartId()
    {
        return $this->shoppingCartId;
    }

    /**
     * Set ticketPrice
     *
     * @param string $ticketPrice
     *
     * @return booking
     */
    public function setTicketPrice($ticketPrice)
    {
        $this->ticketPrice = $ticketPrice;

        return $this;
    }

    /**
     * Get ticketPrice
     *
     * @return string
     */
    public function getTicketPrice()
    {
        return $this->ticketPrice;
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

    /**
     * Set name
     *
     * @param string $name
     *
     * @return booking
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return booking
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return booking
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set calendarId
     *
     * @param \integer $calendarId
     *
     * @return booking
     */
    public function setCalendarId(Calendar $calendarId)
    {
        $this->calendarId = $calendarId;
        $this->setBookingType($calendarId->getBookingType());

        return $this;
    }

    /**
     * Get calendarId
     *
     * @return \integer
     */
    public function getCalendarId()
    {
        return $this->calendarId;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return booking
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set discountTicket
     *
     * @param boolean $discountTicket
     *
     * @return booking
     */
    public function setDiscountTicket($discountTicket)
    {
        $this->discountTicket = $discountTicket;

        return $this;
    }

    /**
     * Get discountTicket
     *
     * @return boolean
     */
    public function getDiscountTicket()
    {
        return $this->discountTicket;
    }
}

