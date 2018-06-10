<?php
	/**
	 * Created by PhpStorm.
	 * User: cecile
	 * Date: 07/06/2018
	 * Time: 22:13
	 */
	
	namespace OC\LouvreBundle\Controller;
	
	use OC\LouvreBundle\Entity\Tbooking;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class PaymentController extends Controller
	{
		public function paymentAction(Request $request, Tbooking $booking)
		{
		   /* $bookingcalcul = $this->get('oc_louvre.bookingcalcul');
			$stripe = $this->get('oc_louvre.stripe');
			
			if ($stripe->getchecking($booking, $bookingcalcul)){
				return new Response('ok');
			}*/

            return $this->render('@OCLouvre/Louvre/payment.html.twig');
		}
	}