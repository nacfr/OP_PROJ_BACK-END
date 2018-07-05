<?php

namespace OC\LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use OC\LouvreBundle\Entity\Booking;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="OC\LouvreBundle\Repository\TicketRepository")
 *
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
     *
     */
    private $booking;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "ticket.name.min_length",
     *      maxMessage = "ticket.name.max_length"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     *
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "ticket.firstname.min_length",
     *      maxMessage = "ticket.firstname.max_length"
     * )
     */
    private $firstname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateofbirth", type="date")
     *
     * @Assert\NotBlank(message="ticket.dateofbirth.not_blank")
     */
    private $dateofbirth;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     *
     * @Assert\NotBlank(message="ticket.country.not_blank")
     */
    private $country;

    /**
     * @var bool
     *
     * @ORM\Column(name="handicap", type="boolean")
     */
    private $handicap;

    /**
     * @var bool
     *
     * @ORM\Column(name="reduceprice", type="boolean")
     */
    private $reduceprice;

    /**
     * @var string
     *
     * @ORM\Column(name="typeticket", type="string", length=255)
     */
    private $typeticket;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     *
     */
    private $price;
    
    /**
     * Get id
     *
     * @return integer
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
     * @return boolean
     */
    public function getHandicap()
    {
        return $this->handicap;
    }

    /**
     * Set reduceprice
     *
     * @param boolean $reduceprice
     *
     * @return Ticket
     */
    public function setReduceprice($reduceprice)
    {
        $this->reduceprice = $reduceprice;

        return $this;
    }

    /**
     * Get reduceprice
     *
     * @return boolean
     */
    public function getReduceprice()
    {
        return $this->reduceprice;
    }

    /**
     * Set booking
     *
     * @param Booking $booking
     *
     * @return Ticket
     */
    public function setBooking(Booking $booking)
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * Get booking
     *
     * @return Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }
    
    public function getAge()
    {
        if(!is_null($this->calcAge()))
        {
        	$this->calcAge();
        }
        
        return $this->calcAge();
    }
    
    /**
     * Set price
     *
     * @param float $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set typeticket
     *
     * @param string $typeticket
     *
     * @return Ticket
     */
    public function setTypeticket($typeticket)
    {
        $this->typeticket = $typeticket;

        return $this;
    }

    /**
     * Get typeticket
     *
     * @return string
     */
    public function getTypeticket()
    {
        return $this->typeticket;
    }
}
