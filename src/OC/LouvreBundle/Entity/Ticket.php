<?php

namespace OC\LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="oc_ticket")
 * @ORM\Entity(repositoryClass="OC\LouvreBundle\Repository\TicketRepository")
 */
class Ticket
{
	/**
	 * @ORM\OneToMany(targetEntity="OC\LouvreBundle\Entity\Pricing", mappedBy="ticket")
	 */
	private $prices;
	
	/**
	 * @var int
	 *
	 * @ORM\ManyToOne(targetEntity="OC\LouvreBundle\Entity\Booking", inversedBy="tickets")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $booking;
	
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
     * @var string
     *
     * @ORM\Column(name="ticketinfo", type="string", length=255)
     */
    private $ticketinfo;


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
     * Set ticketinfo
     *
     * @param string $ticketinfo
     *
     * @return Ticket
     */
    public function setTicketinfo($ticketinfo)
    {
        $this->ticketinfo = $ticketinfo;

        return $this;
    }

    /**
     * Get ticketinfo
     *
     * @return string
     */
    public function getTicketinfo()
    {
        return $this->ticketinfo;
    }

    /**
     * Set booking
     *
     * @param \OC\LouvreBundle\Entity\Booking $booking
     *
     * @return Ticket
     */
    public function setBooking(\OC\LouvreBundle\Entity\Booking $booking)
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
     * Constructor
     */
    public function __construct()
    {
        $this->prices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add price
     *
     * @param \OC\LouvreBundle\Entity\Pricing $price
     *
     * @return Ticket
     */
    public function addPrice(\OC\LouvreBundle\Entity\Pricing $price)
    {
        $this->prices[] = $price;

        return $this;
    }

    /**
     * Remove price
     *
     * @param \OC\LouvreBundle\Entity\Pricing $price
     */
    public function removePrice(\OC\LouvreBundle\Entity\Pricing $price)
    {
        $this->prices->removeElement($price);
    }

    /**
     * Get prices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrices()
    {
        return $this->prices;
    }
}
