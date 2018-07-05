<?php

namespace OC\LouvreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use OC\LouvreBundle\Entity\Ticket;
use Symfony\Component\Validator\Constraints as Assert;
use OC\LouvreBundle\Validator as AcmeAssert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="OC\LouvreBundle\Repository\BookingRepository")
 *
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
     * @ORM\OneToMany(targetEntity="OC\LouvreBundle\Entity\Ticket", mappedBy="booking", cascade={"persist"})
     * @Assert\Valid
     */
    private $tickets;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="btoken", type="string", length=24, unique=true)
	 */
	private $btoken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="bookingdate", type="date")
     *
     * @Assert\NotBlank(message="booking.booking.date.not_blank")
     * @AcmeAssert\Holiday
     *
     */
    private $bookingdate;

    /**
     * @var string
     *
     * @ORM\Column(name="tickettype", type="string", length=255)
     *
     * @Assert\Choice({"day", "halfday"})
     */
    private $tickettype;

    /**
     * @var int
     *
     * @ORM\Column(name="ticketnumber", type="integer")
     *
     * @Assert\LessThan(value = 11, message="booking.booking.ticketnumber.less_than")
     * @Assert\NotBlank(message="booking.booking.ticketnumber.not_blank")
     *
     */
    private $ticketnumber;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="clientname", type="string", length=255, nullable=true)
	 */
	private $clientname;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="clientmail", type="string", length=255, nullable=true)
	 */
	private $clientmail;
 
	/**
	 * @var float
	 *
	 * @ORM\Column(name="totalprice", type="float", nullable=true)
	 *
	 */
    private $totalprice;
	
	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="etat", type="boolean")
	 *
	 */
    private $etat = 0;
	
	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="registrationbooking", type="datetime")
	 */
	private $registrationbooking;
    
    /**
     * Constructor
     */
    public function __construct()
    {
	    $this->registrationbooking = new \DateTime();
    	$this->tickets = new ArrayCollection();
    }
	
	/**
     * Check the ticket type is half day if the selected date is the one of the day and it is after 14h
     *
	 * @Assert\Callback
	 */
	public function getTimeOfDay(ExecutionContextInterface $context){
		
		$newdate = new \DateTime( 'now',  new \DateTimeZone( 'Europe/Paris' ) );
		$date = date_format($newdate, 'Y-m-d');
		$time = date_format($newdate, 'H');
		$selectDate = ($this->getBookingdate() !== null) ? date_format($this->getBookingdate(), 'Y-m-d') : '';
		$typeticket = $this->getTickettype();
		
		if($typeticket === 'day' & $time >= 14 & $selectDate === $date){
			$context
				->buildViolation('Impossible de réserver un billet journée pour cette date')
				->atPath('tickettype')
				->addViolation()
			;
		}
	}

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
     * @return integer
     *
     * @Assert\IsTrue()
     *
     */
    public function getTicketnumber()
    {
        $nbtickets = $this->tickets->count();
        return $this->ticketnumber == $nbtickets;
    }

    /**
     * Set btoken
     *
     * @param string $btoken
     *
     * @return Booking
     */
    public function setBtoken($btoken)
    {
        $this->btoken = $btoken;

        return $this;
    }

    /**
     * Get btoken
     *
     * @return string
     */
    public function getBtoken()
    {
        return $this->btoken;
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
     * @param Ticket $ticket
     *
     * @return Booking
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;
	    //Liaison des billets
	    $ticket->setBooking($this);

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

    /**
     * Set clientname
     *
     * @param string $clientname
     *
     * @return Booking
     */
    public function setClientname($clientname)
    {
        $this->clientname = $clientname;

        return $this;
    }

    /**
     * Get clientname
     *
     * @return string
     */
    public function getClientname()
    {
        return $this->clientname;
    }

    /**
     * Set clientmail
     *
     * @param string $clientmail
     *
     * @return Booking
     */
    public function setClientmail($clientmail)
    {
        $this->clientmail = $clientmail;

        return $this;
    }

    /**
     * Get clientmail
     *
     * @return string
     */
    public function getClientmail()
    {
        return $this->clientmail;
    }

    /**
     * Set totalprice
     *
     * @param float $totalprice
     *
     * @return Booking
     */
    public function setTotalprice($totalprice)
    {
        $this->totalprice = $totalprice;

        return $this;
    }

    /**
     * Get totalprice
     *
     * @return float
     */
    public function getTotalprice()
    {
        return $this->totalprice;
    }

    /**
     * Set etat
     *
     * @param boolean $etat
     *
     * @return Booking
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return boolean
     */
    public function getEtat()
    {
        return $this->etat;
    }
}
