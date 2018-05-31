<?php

namespace OC\LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tticket
 *
 * @ORM\Table(name="tticket")
 * @ORM\Entity(repositoryClass="OC\LouvreBundle\Repository\TticketRepository")
 */
class Tticket
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
     * @ORM\ManyToOne(targetEntity="OC\LouvreBundle\Entity\Tbooking", inversedBy="ttickets")
     */
    private $tbooking;

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
     * @return Tticket
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
     * @return Tticket
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
     * @return Tticket
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
     * @return Tticket
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
     * @return Tticket
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
     * @return Tticket
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
     * Set tbooking
     *
     * @param \OC\LouvreBundle\Entity\Tbooking $tbooking
     *
     * @return Tticket
     */
    public function setTbooking(\OC\LouvreBundle\Entity\Tbooking $tbooking = null)
    {
        $this->tbooking = $tbooking;

        return $this;
    }

    /**
     * Get tbooking
     *
     * @return \OC\LouvreBundle\Entity\Tbooking
     */
    public function getTbooking()
    {
        return $this->tbooking;
    }
}
