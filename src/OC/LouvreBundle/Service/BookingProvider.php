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
		
		//tableau du nombre de ticket par commande
		private static $_NBTICKETS;
		
		//Tableau des tarifs par type
		private static $_PRICINGGRATUIT;
		private static $_PRICINGENFANT;
		private static $_PRICINGNORMAL;
		private static $_PRICINGSENIOR;
		private static $_PRICINGREDUCE;
		
		//tableau détaillé de la commande
		private static $_TABPRICE;
		
		
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
			$this->getNbCatPricing($booking);
			
			$total =
				round(self::$_NBTICKETS['enfant'] * (self::$_PRICINGENFANT->getPriceht() * (1 + self::$_PRICINGENFANT->getTva()))) +
				round(self::$_NBTICKETS['normal'] * (self::$_PRICINGNORMAL->getPriceht() * (1 + self::$_PRICINGNORMAL->getTva()))) +
				round(self::$_NBTICKETS['senior'] * (self::$_PRICINGSENIOR->getPriceht() * (1 + self::$_PRICINGSENIOR->getTva()))) +
				round(self::$_NBTICKETS['reduce'] * (self::$_PRICINGREDUCE->getPriceht() * (1 + self::$_PRICINGREDUCE->getTva())));
			
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
			if (is_null($booking)) {
			
			} else {
				$this->getNbCatPricing($booking);
				
				//Caculation tickets price
				$priceGratuit = 0;
				
				//Princing for child ticket
				if (self::$_NBTICKETS['enfant'] > 0) {
					$priceEnfant = round(self::$_NBTICKETS['enfant'] * (self::$_PRICINGENFANT->getPriceht() * (1 + self::$_PRICINGENFANT->getTva())));
				} else {
					$priceEnfant = 0;
				}
				
				//Pricing for normal tickets
				if (self::$_NBTICKETS['normal'] > 0) {
					$priceNormal = round(self::$_NBTICKETS['normal'] * (self::$_PRICINGNORMAL->getPriceht() * (1 + self::$_PRICINGNORMAL->getTva())));
				} else {
					$priceNormal = 0;
				}
				
				//Pricing for senior ticket
				if (self::$_NBTICKETS['senior'] > 0) {
					$priceSenior = round(self::$_NBTICKETS['senior'] * (self::$_PRICINGSENIOR->getPriceht() * (1 + self::$_PRICINGSENIOR->getTva())));
				} else {
					$priceSenior = 0;
				}
				
				//Pricing for reduce ticket
				if (self::$_NBTICKETS['reduce'] > 0) {
					$priceReduce = round(self::$_NBTICKETS['reduce'] * (self::$_PRICINGREDUCE->getPriceht() * (1 + self::$_PRICINGREDUCE->getTva())));
				} else {
					$priceReduce = 0;
				}
				
				if (is_null(self::$_TABPRICE)) {
					self::$_TABPRICE = [
						'details' => [
							'gratuit'=> ['quantity' => self::$_NBTICKETS['gratuit'], 'price' => $priceGratuit],
							'enfant' => ['quantity' => self::$_NBTICKETS['enfant'], 'price' => $priceEnfant],
							'normal' => ['quantity' => self::$_NBTICKETS['normal'], 'price' => $priceNormal],
							'senior' => ['quantity' => self::$_NBTICKETS['senior'], 'price' => $priceSenior],
							'reduit' => ['quantity' => self::$_NBTICKETS['reduce'], 'price' => $priceReduce]
						],
						'total' => $this->getTotalTicket($booking)
					];
					return self::$_TABPRICE;
				} else {
					return self::$_TABPRICE;
				}
			}
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
			if($dateofbirth instanceof \DateTime){
				$date = date_format($dateofbirth, 'd-m-Y');
			}else{
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
		
		/**
		 * Calcul le nombre de type de ticket et hydrate $nbtickets
		 *
		 * @param $booking
		 * @return array
		 */
		private function getNbCatPricing($booking)
		{
			
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
				} elseif ($ages >= self::$_PRICINGGRATUIT->getMinage() && $ages <= self::$_PRICINGGRATUIT->getMaxage()) {
					$nbGratuit++;
				} elseif ($ages >= self::$_PRICINGENFANT->getMinage() && $ages <= self::$_PRICINGENFANT->getMaxage()) {
					$nbEnfant++;
				} elseif ($ages >= self::$_PRICINGNORMAL->getMinage() && $ages <= self::$_PRICINGNORMAL->getMaxage()) {
					$nbNormal++;
				} elseif ($ages >= self::$_PRICINGSENIOR->getMinage() && $ages <= self::$_PRICINGSENIOR->getMaxage()) {
					$nbSenior++;
				}
			}
			
			if (is_null(self::$_NBTICKETS)) {
				self::$_NBTICKETS = ['reduce' => $nbReduce, 'gratuit' => $nbGratuit, 'enfant' => $nbEnfant, 'normal' => $nbNormal, 'senior' => $nbSenior];
			} else {
				return self::$_NBTICKETS;
			}
		}
		
		public function getQrCode($token)
		{
			$qrCode = new QrCode($token);
			
			//return $qrCode->writeDataUri();
			return $qrCode->writeString();
		}
		
		/*public function getTest2($data)
		{
			if (count($data) == 0) {
				$tab = ['details' => [
					['id' => 'gratuit', 'quantity' => 0, 'price' => 0],
					['id' => 'enfant', 'quantity' => 0, 'price' => 0],
					['id' => 'normal', 'quantity' => 0, 'price' => 0],
					['id' => 'senior', 'quantity' => 0, 'price' => 0],
					['id' => 'reduit', 'quantity' => 0, 'price' => 0],
					['id' => 'oxialive', 'quantity' => 0, 'price' => 0],
				]
				];
				return $tab;
			}
			$tab = ['details' => [
				['id' => 'gratuit', 'quantity' => 0, 'price' => 10],
				['id' => 'enfant', 'quantity' => 1, 'price' => 12],
				['id' => 'normal', 'quantity' => 2, 'price' => 13],
				['id' => 'senior', 'quantity' => 3, 'price' => 14],
				['id' => 'reduit', 'quantity' => 4, 'price' => 15],
				['id' => 'oxialive', 'quantity' => 342352, 'price' => 'Erreur trop cher']
			]
			];
			
			return $tab;
		}*/
		
		
		public function getTest($data)
		{
			$tab = array(
				'details' => array(
					'gratuit' => array('quantity' => 0, 'price' => 0),
					'enfant' => array('quantity' => 0, 'price' => 0),
					'normal' => array('quantity' => 0, 'price' => 0),
					'senior' => array('quantity' => 0, 'price' => 0),
					'reduit' => array('quantity' => 0, 'price' => 0),
				)
			);
			
			if (empty($data)) {
				return $tab;
			}
			
			
			foreach ($data as $info) {
				// Appel de fonction qui calcul le prix en fonction de la date retour 1 tableau
				//$out = array( 'type' => 'reduit', 'price' => 16 ); //prix unitaire
				
				$out = $this->getTicketPrice($info, false);
				$tab['details'][$out['type']]['quantity']++;
				$tab['details'][$out['type']]['price'] += $out['price'];
				//$out = $this->getTicketPrice('1985-11-16', false);
				
				
			}
			
			return $tab;
		}
		
	}