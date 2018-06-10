<?php

namespace OC\LouvreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use OC\LouvreBundle\Entity\Tticket;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tbooking
 *
 * @ORM\Table(name="tbooking")
 * @ORM\Entity(repositoryClass="OC\LouvreBundle\Repository\TbookingRepository")
 *
 */
class Tbooking
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
     * @ORM\OneToMany(targetEntity="OC\LouvreBundle\Entity\Tticket", mappedBy="tbooking", cascade={"persist"})
     *
     */
    private $ttickets;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="bookingdate", type="date")
     *
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
     *
     */
    private $ticketnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="btoken", type="string", length=24, unique=true)
     */
    private $btoken;

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
    	$this->ttickets = new ArrayCollection();
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
     * @return Tbooking
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
     * @return Tbooking
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
     * @return Tbooking
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
     * @Assert\IsTrue(message="La valeur du nombre de ticket ne correspond pas au nombre de tickets envoyés")
     *
     */
    public function getTicketnumber()
    {
        $nbtickets = $this->ttickets->count();
        return $this->ticketnumber == $nbtickets;
    }

    /**
     * Set btoken
     *
     * @param string $btoken
     *
     * @return Tbooking
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
     * @return Tbooking
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
     * Add tticket
     *
     * @param Tticket $tticket
     *
     * @return Tbooking
     */
    public function addTticket(Tticket $tticket)
    {
        $this->ttickets[] = $tticket;
	    //Liaison des billets
	    $tticket->setTbooking($this);

        return $this;
    }

    /**
     * Remove tticket
     *
     * @param Tticket $tticket
     */
    public function removeTticket(Tticket $tticket)
    {
        $this->ttickets->removeElement($tticket);
    }

    /**
     * Get ttickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTtickets()
    {
        return $this->ttickets;
    }
}
