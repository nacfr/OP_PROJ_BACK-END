<?php
	/**
	 * Created by PhpStorm.
	 * User: Pruvost
	 * Date: 16/05/2018
	 * Time: 13:47
	 */
	
	namespace OC\LouvreBundle\Controller;
	
	use OC\LouvreBundle\Entity\Booking;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use OC\LouvreBundle\Form\BookingType;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
	
	class BookingController extends Controller
	{
		
		public static $_token;
		
		
		public function bookingAction(Request $request)
		{
			$booking = new Booking();
			
			
			$form = $this->createForm(BookingType::class, $booking);
			$form->handleRequest($request);
			
			$validator = $this->get('validator');
			$errors = $validator->validate($booking);
			
			if ($form->isSubmitted() && $form->isValid()) {
				
				if (count($errors) > 0) {
					return $this->render('@OCLouvre/Louvre/booking.html.twig', array(
							'form' => $form->createView(),
							'errors' => $errors
						)
					);
				}
				
				self::$_token = $booking->getBtoken();
				
				$pricing = $this->get('oc_louvre.bookingprovider');
				
				$availableday = $pricing->getDispoTicketByDate($booking->getBookingdate());
				
				if($availableday){
					$em = $this->getDoctrine()->getManager();
					
					//Hydrate les prix pour chaque tickets
					$tickets = $booking->getTickets();
					foreach ($tickets as $ticket) {
						$dateofbirth = $ticket->getDateofbirth();
						$reduceprice = $ticket->getReduceprice();
						$ticket->setPrice($pricing->getTicketPrice($dateofbirth, $reduceprice));
					}
					
					//Hydrate le prix total
					$booking->setTotalprice($pricing->getTotalTicket($booking));
					
					$em->persist($booking);
					$em->flush();
					
					
					
					//return $this->forward('OCLouvreBundle:Booking:summary', array('btoken' => $booking->getBtoken()));
					return $this->redirectToRoute('oc_louvre_summary', array('btoken' => $booking->getBtoken()));
				}
				else{
					$error = 'Le plafond du nombre de tickets disponible a été atteind. Veuillez choisir une autre journée.';
					return $this->render('@OCLouvre/Louvre/booking.html.twig', array(
							'form' => $form->createView(),
							'error' => $error
						));
				}
			}
			
			return $this->render('@OCLouvre/Louvre/booking.html.twig', array(
					'form' => $form->createView()
				)
			);
		}
		
		
		public function widgetAction()
		{
			$listPurchase = array(
				array('qt' => '0', 'title' => ' x Gratuit', 'price' => '0€'),
				array('qt' => '0', 'title' => ' x Enfant', 'price' => '0€'),
				array('qt' => '0', 'title' => ' x Normal', 'price' => '0€'),
				array('qt' => '0', 'title' => ' x Sénior', 'price' => '0€'),
				array('qt' => '0', 'title' => ' x Réduit', 'price' => '0€')
			);
			
			
			return $this->render('@OCLouvre/Louvre/widgetPurchase.html.twig', array(
				'listPurchase' => $listPurchase
			));
			
		}
	}