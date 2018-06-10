<?php
	/**
	 * Created by PhpStorm.
	 * User: Pruvost
	 * Date: 16/05/2018
	 * Time: 13:47
	 */
	
	namespace OC\LouvreBundle\Controller;
	
	
	use OC\LouvreBundle\Entity\Tbooking;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use OC\LouvreBundle\Form\BookingType;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
	
	
	class BookingController extends Controller
	{
		public function bookingAction(Request $request)
		{
			
			$booking = new Tbooking();
			
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

                $em = $this->getDoctrine()->getManager();
                $pricing = $this->get('oc_louvre.bookingcalcul');
                $tickets = $booking->getTtickets();
                foreach ($tickets as $ticket) {
                    $dateofbirth = $ticket->getDateofbirth();
                    $reduceprice = $ticket->getReduceprice();
                    $bookingprice = $pricing->getTicketPrice($dateofbirth, $reduceprice);
                    $ticket->setPrice($bookingprice);
                }
				$em->persist($booking);
				$em->flush();
				
				//return $this->forward('OCLouvreBundle:Booking:summary', array('btoken' => $booking->getBtoken()));
				return $this->redirectToRoute('oc_louvre_summary', array('btoken' => $booking->getBtoken()));
				
			}
			
			return $this->render('@OCLouvre/Louvre/booking.html.twig', array(
					'form' => $form->createView(),
					'errors' => $errors
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
		
		public function sucessAction()
		{
			return $this->render('@OCLouvre/Louvre/sucess.html');
		}
	}