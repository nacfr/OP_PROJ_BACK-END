<?php

namespace OC\LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pricing
 *
 * @ORM\Table(name="oc_pricing")
 * @ORM\Entity(repositoryClass="OC\LouvreBundle\Repository\PricingRepository")
 */
class Pricing
{
	/**
	 * @var int
	 *
	 * @ORM\ManyToOne(targetEntity="OC\LouvreBundle\Entity\Ticket")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $ticket;
	
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var float
     *
     * @ORM\Column(name="htprice", type="float")
     */
    private $htprice;
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="tva", type="float")
	 */
    private $tva;


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
     * Set title
     *
     * @param string $title
     *
     * @return Pricing
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set htprice
     *
     * @param float $htprice
     *
     * @return Pricing
     */
    public function setHtprice($htprice)
    {
        $this->htprice = $htprice;

        return $this;
    }

    /**
     * Get htprice
     *
     * @return float
     */
    public function getHtprice()
    {
        return $this->htprice;
    }

    /**
     * Set tva
     *
     * @param float $tva
     *
     * @return Pricing
     */
    public function setTva($tva)
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get tva
     *
     * @return float
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * Set ticket
     *
     * @param \OC\LouvreBundle\Entity\Ticket $ticket
     *
     * @return Pricing
     */
    public function setTicket(\OC\LouvreBundle\Entity\Ticket $ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return \OC\LouvreBundle\Entity\Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }
}
