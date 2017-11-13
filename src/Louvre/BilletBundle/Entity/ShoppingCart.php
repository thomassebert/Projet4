<?php

namespace Louvre\BilletBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;

use Louvre\BilletBundle\Entity\User;

/**
 * ShoppingCart
 *
 * @ORM\Table(name="shopping_cart")
 * @ORM\Entity(repositoryClass="Louvre\BilletBundle\Repository\ShoppingCartRepository")
 */
class ShoppingCart
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
     * @var string
     *
     * @ORM\Column(name="bookingKey", type="string", length=255, unique=true, nullable=false)
     */
    private $bookingKey;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="bookingPrice", type="string", length=255, nullable=false)
     */
    private $bookingPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=true)
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="booking_date", type="datetime", nullable=false)
     * @Assert\DateTime()
     */
    private $bookingDate;

    /**
     * @ORM\OneToMany(targetEntity="Booking", mappedBy="shoppingCartId")
     */
    private $bookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->bookingPrice = 0;
    }

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
     * Set bookingKey
     *
     * @param string $bookingKey
     *
     * @return ShoppingCart
     */
    public function setBookingKey($bookingKey)
    {
        $this->bookingKey = $bookingKey;

        return $this;
    }

    /**
     * Get bookingKey
     *
     * @return string
     */
    public function getBookingKey()
    {
        return $this->bookingKey;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return ShoppingCart
     */
    public function setUserId(User $userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set bookingPrice
     *
     * @param string $bookingPrice
     *
     * @return ShoppingCart
     */
    public function setBookingPrice($bookingPrice)
    {
        $this->bookingPrice = $this->bookingPrice + $bookingPrice;

        return $this;
    }

    /**
     * Get bookingPrice
     *
     * @return string
     */
    public function getBookingPrice()
    {
        return $this->bookingPrice;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return ShoppingCart
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set bookingDate
     *
     * @param \DateTime $bookingDate
     *
     * @return booking
     */
    public function setBookingDate($bookingDate)
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    /**
     * Get bookingDate
     *
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
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

    public function newShoppingCart(User $userId) 
    {
        $this->setBookingPrice(0);
        $this->setUserId($userId);
        $this->setState("empty");

        $key = strval(rand(100, 999).chr(rand(97,122)).$userId->getId().date('iGs'));
        $this->setBookingKey($key);
    }
}

