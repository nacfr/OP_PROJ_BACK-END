<?php

namespace OC\LouvreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use OC\LouvreBundle\Entity\Ticket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Booking
 *
 * @ORM\Table(name="oc_booking")
 * @ORM\Entity(repositoryClass="OC\LouvreBundle\Repository\BookingRepository")
 */
class Booking
{
	/**
	 * @ORM\OneToMany(targetEntity="OC\LouvreBundle\Entity\Ticket", mappedBy="booking")
	 */
	private $tickets;
	
	/**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     *
     * @ORM\Column(name="bookingdate", type="date")
     */
    private $bookingdate;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="tickettype", type="string", length=255)
     */
    private $tickettype;

    /**
     * @var int
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="ticketnumber", type="integer")
     */
    private $ticketnumber;

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
     * Set bookingdate
     *
     * @param \DateTime $bookingdate
     *
     * @return Booking
     */
    public function setBookingdate($bookingdate)
    {
        $this->bookingdate = $bookingdate;

        return $this;
    }

    /**
     * Get bookingdate
     *
     * @return \DateTime
     */
    public function getBookingdate()
    {
        return $this->bookingdate;
    }

    /**
     * Set tickettype
     *
     * @param string $tickettype
     *
     * @return Booking
     */
    public function setTickettype($tickettype)
    {
        $this->tickettype = $tickettype;

        return $this;
    }

    /**
     * Get tickettype
     *
     * @return string
     */
    public function getTickettype()
    {
        return $this->tickettype;
    }

    /**
     * Set ticketnumber
     *
     * @param integer $ticketnumber
     *
     * @return Booking
     */
    public function setTicketnumber($ticketnumber)
    {
        $this->ticketnumber = $ticketnumber;

        return $this;
    }

    /**
     * Get ticketnumber
     *
     * @return int
     */
    public function getTicketnumber()
    {
        return $this->ticketnumber;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    /**
     * Add ticket
     *
     * @param Ticket $ticket
     *
     * @return Booking
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param Ticket $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}
