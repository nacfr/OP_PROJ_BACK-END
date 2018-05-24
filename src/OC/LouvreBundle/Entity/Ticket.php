<?php

namespace OC\LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="OC\LouvreBundle\Repository\TicketRepository")
 */
class Ticket
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
     * @ORM\ManyToOne(targetEntity="OC\LouvreBundle\Entity\Booking", inversedBy="tickets")
     */
    private $booking;

    /**
     * @ORM\OneToOne(targetEntity="OC\LouvreBundle\Entity\Pricing")
     */
    private $pricing;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateofbirth", type="date")
     */
    private $dateofbirth;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var bool
     *
     * @ORM\Column(name="reducedprice", type="boolean")
     */
    private $reducedprice;

    /**
     * @var bool
     *
     * @ORM\Column(name="handicap", type="boolean")
     */
    private $handicap;

    /**
     * @var float
     *
     * @ORM\Column(name="priceht", type="float")
     */
    private $priceht;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registrationticket", type="datetime")
     */
    private $registrationticket;


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
     * Set name
     *
     * @param string $name
     *
     * @return Ticket
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
     * @return Ticket
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
     * Set dateofbirth
     *
     * @param \DateTime $dateofbirth
     *
     * @return Ticket
     */
    public function setDateofbirth($dateofbirth)
    {
        $this->dateofbirth = $dateofbirth;

        return $this;
    }

    /**
     * Get dateofbirth
     *
     * @return \DateTime
     */
    public function getDateofbirth()
    {
        return $this->dateofbirth;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Ticket
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
     * Set reducedprice
     *
     * @param boolean $reducedprice
     *
     * @return Ticket
     */
    public function setReducedprice($reducedprice)
    {
        $this->reducedprice = $reducedprice;

        return $this;
    }

    /**
     * Get reducedprice
     *
     * @return bool
     */
    public function getReducedprice()
    {
        return $this->reducedprice;
    }

    /**
     * Set handicap
     *
     * @param boolean $handicap
     *
     * @return Ticket
     */
    public function setHandicap($handicap)
    {
        $this->handicap = $handicap;

        return $this;
    }

    /**
     * Get handicap
     *
     * @return bool
     */
    public function getHandicap()
    {
        return $this->handicap;
    }

    /**
     * Set priceht
     *
     * @param float $priceht
     *
     * @return Ticket
     */
    public function setPriceht($priceht)
    {
        $this->priceht = $priceht;

        return $this;
    }

    /**
     * Get priceht
     *
     * @return float
     */
    public function getPriceht()
    {
        return $this->priceht;
    }

    /**
     * Set registrationticket
     *
     * @param \DateTime $registrationticket
     *
     * @return Ticket
     */
    public function setRegistrationticket($registrationticket)
    {
        $this->registrationticket = $registrationticket;

        return $this;
    }

    /**
     * Get registrationticket
     *
     * @return \DateTime
     */
    public function getRegistrationticket()
    {
        return $this->registrationticket;
    }

    /**
     * Set booking
     *
     * @param \OC\LouvreBundle\Entity\Booking $booking
     *
     * @return Ticket
     */
    public function setBooking(\OC\LouvreBundle\Entity\Booking $booking = null)
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * Get booking
     *
     * @return \OC\LouvreBundle\Entity\Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * Set pricing
     *
     * @param \OC\LouvreBundle\Entity\Princing $pricing
     *
     * @return Ticket
     */
    public function setPricing(\OC\LouvreBundle\Entity\Princing $pricing = null)
    {
        $this->pricing = $pricing;

        return $this;
    }

    /**
     * Get pricing
     *
     * @return \OC\LouvreBundle\Entity\Princing
     */
    public function getPricing()
    {
        return $this->pricing;
    }
}
