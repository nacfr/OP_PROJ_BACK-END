<?php

namespace OC\LouvreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="OC\LouvreBundle\Repository\BookingRepository")
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
     * @ORM\OneToMany(targetEntity="OC\LouvreBundle\Entity\Ticket", mappedBy="booking")
     */
    private $tickets;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="bookingdate", type="date")
     */
    private $bookingdate;

    /**
     * @var string
     *
     * @ORM\Column(name="tickettype", type="string", length=255)
     */
    private $tickettype;

    /**
     * @var int
     *
     * @ORM\Column(name="ticketnumber", type="integer")
     */
    private $ticketnumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registrationbooking", type="datetime")
     */
    private $registrationbooking;

    public function __construct()
    {
        $this->registrationbooking = new \DateTime();
        $this->tickets = new ArrayCollection();
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
     * Set registrationbooking
     *
     * @param \DateTime $registrationbooking
     *
     * @return Booking
     */
    public function setRegistrationbooking($registrationbooking)
    {
        $this->registrationbooking = $registrationbooking;

        return $this;
    }

    /**
     * Get registrationbooking
     *
     * @return \DateTime
     */
    public function getRegistrationbooking()
    {
        return $this->registrationbooking;
    }

    /**
     * Add ticket
     *
     * @param \OC\LouvreBundle\Entity\Ticket $ticket
     *
     * @return Booking
     */
    public function addTicket(\OC\LouvreBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \OC\LouvreBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\OC\LouvreBundle\Entity\Ticket $ticket)
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
