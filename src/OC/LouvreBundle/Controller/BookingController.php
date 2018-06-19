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
    use Symfony\Component\HttpFoundation\Session\Session;


    class BookingController extends Controller
	{
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

				$pricing = $this->get('oc_louvre.bookingprovider');
				
				$availableday = $pricing->getDispoTicketByDate($booking->getBookingdate());
				
				if($availableday){
					$em = $this->getDoctrine()->getManager();
					
					//Hydrate les prix pour chaque tickets
					$tickets = $booking->getTickets();
					foreach ($tickets as $ticket) {
						$dateofbirth = $ticket->getDateofbirth();
						$reduceprice = $ticket->getReduceprice();
						$price = $pricing->getTicketPrice($dateofbirth, $reduceprice);

						$ticket->setTypeticket($price['type']);
						$ticket->setPrice($price['price']);
					}
					
					//Hydrate le prix total
					$booking->setTotalprice($pricing->getTotalTicket($booking));
					
					$em->persist($booking);
					$em->flush();

                    //Mise en session du token
                    $this->get('session')->set('token', $booking->getBtoken());

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
	}