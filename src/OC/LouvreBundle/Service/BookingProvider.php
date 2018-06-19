<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 05/06/2018
 * Time: 21:31
 */

namespace OC\LouvreBundle\Service;


use Doctrine\ORM\EntityManager;
use Endroid\QrCode\QrCode;
use OC\LouvreBundle\Controller\BookingController;
use OC\LouvreBundle\Entity\Booking;

class BookingProvider
{
	private $entityManager;
    private $nbtickets;
    private $pricingGratuit;
    private $pricingEnfant;
    private $pricingNormal;
    private $pricingSenior;
    private $pricingReduce;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->getPricing();
        
    }
    
    public function getDispoTicketByDate($date){
    	
    	$dates = $this->entityManager->getRepository('OCLouvreBundle:Booking')->findBy(['bookingdate' => $date]);
	    $totaltickets = 0;
    	foreach ($dates as $date){
		    $totaltickets += $date->getTickets()->count();
	    }
	    
	    if ($totaltickets >= 8){
    		return false;
	    }
	    else{
    		return true;
	    }
    }
    
    
	/**
	 * Calcul le prix en fonction de l'age
	 *
	 * @param $dateofbirth
	 * @param $reduceprice
	 * @return array
	 */
	public function getTicketPrice($dateofbirth, $reduceprice)
    {
        $reduce = $reduceprice;
        $ages = $this->calcAge($dateofbirth);
        
        if ($reduce == true) {
            $type = $this->pricingReduce->getTitle();
            $price = round($this->pricingReduce->getPriceht() + ($this->pricingReduce->getPriceht() * $this->pricingReduce->getTva()));
        }
        elseif ($ages >= $this->pricingGratuit->getMinage() && $ages <= $this->pricingGratuit->getMaxage()) {
            $type = $this->pricingGratuit->getTitle();
            $price = 0;
        }
        elseif ($ages >= $this->pricingEnfant->getMinage() && $ages <= $this->pricingEnfant->getMaxage()) {
            $type = $this->pricingEnfant->getTitle();
            $price = round($this->pricingEnfant->getPriceht() + ($this->pricingEnfant->getPriceht() * $this->pricingEnfant->getTva()));
        }
        elseif ($ages >= $this->pricingNormal->getMinage() && $ages <= $this->pricingNormal->getMaxage()) {
            $type = $this->pricingNormal->getTitle();
            $price = round($this->pricingNormal->getPriceht() + ($this->pricingNormal->getPriceht() * $this->pricingNormal->getTva()));
        }
        elseif ($ages >= $this->pricingSenior->getMinage() && $ages <= $this->pricingSenior->getMaxage()) {
            $type = $this->pricingNormal->getTitle();
            $price = round($this->pricingSenior->getPriceht() + ($this->pricingSenior->getPriceht() * $this->pricingSenior->getTva()));
        }

        return ['type' => $type, 'price' => $price];
    }
	
	/**
	 * Calcul le prix total
	 *
	 * @return float
	 */
	public function getTotalTicket($booking)
    {
	    $this->getNbCatPricing($booking);
    	
    	$total =
		    round($this->nbtickets['enfant']*($this->pricingEnfant->getPriceht()*(1+$this->pricingEnfant->getTva())))+
		    round($this->nbtickets['normal']*($this->pricingNormal->getPriceht()*(1+$this->pricingNormal->getTva())))+
		    round($this->nbtickets['senior']*($this->pricingSenior->getPriceht()*(1+$this->pricingSenior->getTva())))+
		    round($this->nbtickets['reduce']*($this->pricingReduce->getPriceht()*(1+$this->pricingReduce->getTva())));
		    
    	return $total;
    }
    
	/**
	 *
	 * Renvoi un tableau détaillé contenant chaque tarif avec la quantité, la somme des tickets
	 * et la somme total des tickets
	 *
	 * @return array
	 */
	public function getTabPrice($booking)
     {
         $this->getNbCatPricing($booking);
	     
     	//Caculation tickets price
         $priceGratuit = 0;

         //Princing for child ticket
         if ($this->nbtickets['enfant'] > 0) {
             $priceEnfant = round($this->nbtickets['enfant']*($this->pricingEnfant->getPriceht()*(1+$this->pricingEnfant->getTva())));
         } else {
             $priceEnfant = 0;
         }

         //Pricing for normal tickets
         if ($this->nbtickets['normal'] > 0) {
             $priceNormal = round($this->nbtickets['normal']*($this->pricingNormal->getPriceht()*(1+$this->pricingNormal->getTva())));
         } else {
             $priceNormal = 0;
         }

         //Pricing for senior ticket
         if ($this->nbtickets['senior'] > 0) {
             $priceSenior = round($this->nbtickets['senior']*($this->pricingSenior->getPriceht()*(1+$this->pricingSenior->getTva())));
         } else {
             $priceSenior = 0;
         }

         //Pricing for reduce ticket
         if ($this->nbtickets['reduce'] > 0) {
             $priceReduce = round($this->nbtickets['reduce']*($this->pricingReduce->getPriceht()*(1+$this->pricingReduce->getTva())));
         } else {
             $priceReduce = 0;
         }

         return [
             'details' => [
                 ['id' => 'gratuit', 'quantity' => $this->nbtickets['gratuit'], 'price' => $priceGratuit],
                 ['id' => 'enfant', 'quantity' => $this->nbtickets['enfant'], 'price' => $priceEnfant],
                 ['id' => 'normal', 'quantity' => $this->nbtickets['normal'], 'price' => $priceNormal],
                 ['id' => 'senior', 'quantity' => $this->nbtickets['senior'], 'price' => $priceSenior],
                 ['id' => 'reduit', 'quantity' => $this->nbtickets['reduce'], 'price' => $priceReduce]
             ],
             'total' => $this->getTotalTicket($booking)
         ];
     }

     public function getQrCode($token){
	    $qrCode = new QrCode($token);

	    return $qrCode->writeDataUri();
     }

     public function getOrderSummary($idclient)
     {
         $order = $this->entityManager->getRepository('OCLouvreBundle:Booking')->find($idclient);
         return $order;
     }
	
	/**
	 *
	 * Renvoi le type de ticket
	 *
	 * @param Booking $booking
	 * @return string
	 */
	public function getBookingType(Booking $booking)
    {
        if ($booking->getTickettype() === 'day') {
            return $bookingtype = 'journée';
        }
        if ($booking->getTickettype() === 'halfday') {
            return $bookingtype = 'demi-journée';
        }
    }
    
	/**
	 *
	 * Calcul l'age à partir d'une date de naissance
	 *
	 * @param $dateofbirth
	 * @return int
	 */
	private function calcAge($dateofbirth)
    {
        $date = date_format($dateofbirth, 'Y-m-d');
        $am = explode('-', $date);
        $an = explode('-', date('Y-m-d'));

        if (($am[1] < $an[1]) || (($am[1] == $an[1]) && ($am[2] <= $an[2])))
            return $an[0] - $am[0];

        return $an[0] - $am[0] - 1;
    }
	
	/**
	 * Récupère la table Pricing
	 * et hydrate les attribues pour chaque type de prix
	 */
	private function getPricing()
	{
		$pricing = $this->entityManager->getRepository('OCLouvreBundle:Pricing')->findAll();
		
		$this->pricingGratuit = $pricing[0];
		$this->pricingEnfant = $pricing[1];
		$this->pricingNormal = $pricing[2];
		$this->pricingSenior = $pricing[3];
		$this->pricingReduce = $pricing[4];
	}
	
	/**
	 * Calcul le nombre de type de ticket et hydrate $nbtickets
	 *
	 * @param $booking
	 * @return array
	 */
	private function getNbCatPricing($booking){
		
		$nbGratuit = 0;
		$nbEnfant = 0;
		$nbNormal = 0;
		$nbSenior = 0;
		$nbReduce = 0;
		
		$tickets = $booking->getTickets();
		foreach ($tickets as $ticket) {
			$reduce = $ticket->getReduceprice();
			$ages = $this->calcAge($ticket->getDateofbirth());
			if ($reduce == true) {
				$nbReduce++;
			} elseif ($ages >= $this->pricingGratuit->getMinage() && $ages <= $this->pricingGratuit->getMaxage()) {
				$nbGratuit++;
			} elseif ($ages >= $this->pricingEnfant->getMinage() && $ages <= $this->pricingEnfant->getMaxage()) {
				$nbEnfant++;
			} elseif ($ages >= $this->pricingNormal->getMinage() && $ages <= $this->pricingNormal->getMaxage()) {
				$nbNormal++;
			} elseif ($ages >= $this->pricingSenior->getMinage() && $ages <= $this->pricingSenior->getMaxage()) {
				$nbSenior++;
			}
		}
		
		if(is_null($this->nbtickets)){
			$this->nbtickets = ['reduce' => $nbReduce, 'gratuit' => $nbGratuit, 'enfant' => $nbEnfant, 'normal' => $nbNormal, 'senior' => $nbSenior];
		}
		else{
			return $this->nbtickets;
		}
	}

}