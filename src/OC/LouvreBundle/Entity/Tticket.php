<?php

namespace OC\LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use OC\LouvreBundle\Entity\Tbooking;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tticket
 *
 * @ORM\Table(name="tticket")
 * @ORM\Entity(repositoryClass="OC\LouvreBundle\Repository\TticketRepository")
 *
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
     *
     */
    private $tbooking;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\EqualTo("Mary")
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
     * @param Tbooking $tbooking
     *
     * @return Tticket
     */
    public function setTbooking(Tbooking $tbooking)
    {
        $this->tbooking = $tbooking;

        return $this;
    }

    /**
     * Get tbooking
     *
     * @return Tbooking
     */
    public function getTbooking()
    {
        return $this->tbooking;
    }
    
    public function getAge()
    {
        if(!is_null($this->calcAge()))
        {
        	$this->calcAge();
        }
        
        return $this->calcAge();
    }
	
	private function calcAge()
	{
		$dateofbirth = date_format($this->getDateofbirth(), 'Y-m-d');
		$am = explode('-', $dateofbirth);
		$an = explode('-', date('Y-m-d'));
		
		if(($am[1] < $an[1]) || (($am[1] == $an[1]) && ($am[2] <= $an[2])))
			return $an[0] - $am[0];
		
		return $an[0] - $am[0] - 1;
	}



    /**
     * Set price
     *
     * @param float $price
     *
     * @return Tticket
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
}
