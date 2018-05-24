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
     * @var float
     *
     * @ORM\Column(name="priceht", type="float")
     */
    private $priceht;

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
}
