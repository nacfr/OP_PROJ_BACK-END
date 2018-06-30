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
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Psr\Log\LoggerInterface;
	
	class BookingProvider extends Controller
	{
		private $entityManager;
		
		//Tableau des tarifs par type
		private static $_PRICINGGRATUIT;
		private static $_PRICINGENFANT;
		private static $_PRICINGNORMAL;
		private static $_PRICINGSENIOR;
		private static $_PRICINGREDUCE;
		
		public function __construct(EntityManager $entityManager)
		{
			$this->entityManager = $entityManager;
			$this->getPricing();
			
		}
		
		/**
		 * @param $date
		 * @return bool
		 */
		public function getDispoTicketByDate($date)
		{
			
			$dates = $this->entityManager->getRepository('OCLouvreBundle:Booking')->findBy(['bookingdate' => $date]);
			$totaltickets = 0;
			foreach ($dates as $date) {
				$totaltickets += $date->getTickets()->count();
			}
			
			if ($totaltickets >= 8) {
				return false;
			} else {
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
				$type = self::$_PRICINGREDUCE->getTitle();
				$price = round(self::$_PRICINGREDUCE->getPriceht() + (self::$_PRICINGREDUCE->getPriceht() * self::$_PRICINGREDUCE->getTva()));
			} elseif ($ages >= self::$_PRICINGGRATUIT->getMinage() && $ages <= self::$_PRICINGGRATUIT->getMaxage()) {
				$type = self::$_PRICINGGRATUIT->getTitle();
				$price = 0;
			} elseif ($ages >= self::$_PRICINGENFANT->getMinage() && $ages <= self::$_PRICINGENFANT->getMaxage()) {
				$type = self::$_PRICINGENFANT->getTitle();
				$price = round(self::$_PRICINGENFANT->getPriceht() + (self::$_PRICINGENFANT->getPriceht() * self::$_PRICINGENFANT->getTva()));
			} elseif ($ages >= self::$_PRICINGNORMAL->getMinage() && $ages <= self::$_PRICINGNORMAL->getMaxage()) {
				$type = self::$_PRICINGNORMAL->getTitle();
				$price = round(self::$_PRICINGNORMAL->getPriceht() + (self::$_PRICINGNORMAL->getPriceht() * self::$_PRICINGNORMAL->getTva()));
			} elseif ($ages >= self::$_PRICINGSENIOR->getMinage() && $ages <= self::$_PRICINGSENIOR->getMaxage()) {
				$type = self::$_PRICINGSENIOR->getTitle();
				$price = round(self::$_PRICINGSENIOR->getPriceht() + (self::$_PRICINGSENIOR->getPriceht() * self::$_PRICINGSENIOR->getTva()));
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
			$total = $this->getPendingOrder($booking)['total'];
			return $total;
		}
		
		/**
		 *
		 * Renvoi un tableau détaillé contenant chaque tarif avec la quantité, la somme des tickets
		 * et la somme total des tickets
		 *
		 * @return array
		 */
		public function getPendingOrder($data)
		{
			$tab = array(
				'details' => array(
					'gratuit' => array('quantity' => 0, 'price' => 0),
					'enfant' => array('quantity' => 0, 'price' => 0),
					'normal' => array('quantity' => 0, 'price' => 0),
					'senior' => array('quantity' => 0, 'price' => 0),
					'reduit' => array('quantity' => 0, 'price' => 0)),
				'total' => 0,
			);
			
			if (is_array($data)) {
				if (empty($data)) {
					return $tab;
				}
				foreach ($data as $info) {
					$out = $this->getTicketPrice($info, false);
					$tab['details'][$out['type']]['quantity']++;
					$tab['details'][$out['type']]['price'] += $out['price'];
					$tab['total'] += $out['price'];
				}
				return $tab;
			}
			
			if (is_object($data)) {
				$tickets = $data->getTickets();
				foreach ($tickets as $ticket) {
					$dateofbirth = $ticket->getDateofbirth();
					$reduce = $ticket->getReduceprice();
					$out = $this->getTicketPrice($dateofbirth, $reduce);
					$tab['details'][$out['type']]['quantity']++;
					$tab['details'][$out['type']]['price'] += $out['price'];
					$tab['total'] += $out['price'];
				}
				return $tab;
			}
			
		}
		
		
		/**
		 * Renvoi un tableau détaillé pour pour l'actualisation jquery dans la page d'accueil booking
		 *
		 * @param $data
		 * @return array
		 */
		public function getPendingOrder2($data)
		{
			$tab = array(
				'details' => array(
					'gratuit' => array('quantity' => 0, 'price' => 0),
					'enfant' => array('quantity' => 0, 'price' => 0),
					'normal' => array('quantity' => 0, 'price' => 0),
					'senior' => array('quantity' => 0, 'price' => 0),
					'reduit' => array('quantity' => 0, 'price' => 0)),
				'total' => 0,
			);
			
			if (empty($data)) {
				return $tab;
			}
			
			foreach ($data as $info) {
				$out = $this->getTicketPrice($info, false);
				$tab['details'][$out['type']]['quantity']++;
				$tab['details'][$out['type']]['price'] += $out['price'];
				$tab['total'] += $out['price'];
			}
			
			return $tab;
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
			if ($dateofbirth instanceof \DateTime) {
				$date = date_format($dateofbirth, 'd-m-Y');
			} else {
				$date = $dateofbirth;
			}
			
			$am = explode('-', $date);
			$an = explode('-', date('d-m-Y'));
			
			if (($am[1] < $an[1]) || (($am[1] == $an[1]) && ($am[1] <= $an[1])))
				return $an[2] - $am[2];
			
			return $an[2] - $am[2] - 1;
		}
		
		/**
		 * Récupère la table Pricing
		 * et hydrate les attribues pour chaque type de prix
		 */
		private function getPricing()
		{
			$pricing = $this->entityManager->getRepository('OCLouvreBundle:Pricing')->findAll();
			
			self::$_PRICINGGRATUIT = $pricing[0];
			self::$_PRICINGENFANT = $pricing[1];
			self::$_PRICINGNORMAL = $pricing[2];
			self::$_PRICINGSENIOR = $pricing[3];
			self::$_PRICINGREDUCE = $pricing[4];
		}
		
		public function getQrCode($token)
		{
			$qrCode = new QrCode($token);
			
			//return $qrCode->writeDataUri();
			return $qrCode->writeString();
		}
		
		
	}