<?php

namespace OC\LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pricing
 *
 * @ORM\Table(name="pricing")
 * @ORM\Entity(repositoryClass="OC\LouvreBundle\Repository\PricingRepository")
 */
class Pricing
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;
	
	/**
	 * @var int
	 *
	 * @ORM\Column(name="minage", type="integer")
	 */
    private $minage;
	
	/**
	 * @var int
	 *
	 * @ORM\Column(name="maxage", type="integer")
	 */
    private $maxage;

    /**
     * @var float
     *
     * @ORM\Column(name="priceht", type="float")
     */
    private $priceht;
	
	
	/**
	 * @var float
	 *
	 * @ORM\Column(name="tva", type="float")
	 */
    private $tva;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registrationpricing", type="datetime")
     */
    private $registrationpricing;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modificationpricing", type="datetime")
     */
    private $modificationpricing;

    public function __construct()
    {
        $this->modificationpricing = new \DateTime();
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
     * Set priceht
     *
     * @param float $priceht
     *
     * @return Pricing
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
     * Set registrationpricing
     *
     * @param \DateTime $registrationpricing
     *
     * @return Pricing
     */
    public function setRegistrationpricing($registrationpricing)
    {
        $this->registrationpricing = $registrationpricing;

        return $this;
    }

    /**
     * Get registrationpricing
     *
     * @return \DateTime
     */
    public function getRegistrationpricing()
    {
        return $this->registrationpricing;
    }

    /**
     * Set modificationpricing
     *
     * @param \DateTime $modificationpricing
     *
     * @return Pricing
     */
    public function setModificationpricing($modificationpricing)
    {
        $this->modificationpricing = $modificationpricing;

        return $this;
    }

    /**
     * Get modificationpricing
     *
     * @return \DateTime
     */
    public function getModificationpricing()
    {
        return $this->modificationpricing;
    }

    /**
     * Set minage
     *
     * @param integer $minage
     *
     * @return Pricing
     */
    public function setMinage($minage)
    {
        $this->minage = $minage;

        return $this;
    }

    /**
     * Get minage
     *
     * @return integer
     */
    public function getMinage()
    {
        return $this->minage;
    }

    /**
     * Set maxage
     *
     * @param integer $maxage
     *
     * @return Pricing
     */
    public function setMaxage($maxage)
    {
        $this->maxage = $maxage;

        return $this;
    }

    /**
     * Get maxage
     *
     * @return integer
     */
    public function getMaxage()
    {
        return $this->maxage;
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
}
