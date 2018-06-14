<?php
	/**
	 * Created by PhpStorm.
	 * User: cecile
	 * Date: 07/06/2018
	 * Time: 22:13
	 */
	
	namespace OC\LouvreBundle\Controller;
	
	use OC\LouvreBundle\Entity\Fbooking;
	use OC\LouvreBundle\Entity\Booking;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class PaymentController extends Controller
	{
		private $successpayment = false;
		
		public function paymentAction(Request $request, Booking $booking)
		{
			
			$stripe = $this->get('oc_louvre.stripe');
			$bookingprovider = $this->get('oc_louvre.bookingprovider');
			
			
			if ($request->isMethod('POST')) {
				
				if ($stripe->getChecking($booking, $bookingprovider)) {
					
					$this->successpayment = true;
					
					return $this->render('@OCLouvre/Louvre/payment.html.twig', array(
						'booking' => $booking,
						'success' => $this->successpayment
					));
				}
			}
			
			return $this->render('@OCLouvre/Louvre/payment.html.twig', array(
				'booking' => $booking,
				'success' => $this->successpayment
			));
		}
		
		
	}