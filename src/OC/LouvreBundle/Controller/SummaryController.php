<?php
	/**
	 * Created by PhpStorm.
	 * User: cecile
	 * Date: 06/06/2018
	 * Time: 21:18
	 */
	
	namespace OC\LouvreBundle\Controller;
	
	use OC\LouvreBundle\Service\BookingCalcul;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\Form\Extension\Core\Type\FormType;
    use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use OC\LouvreBundle\Entity\Tbooking;
	
	class SummaryController extends Controller
	{
		
		public function summaryAction(Tbooking $booking)
		{
			$pricing = $this->get('oc_louvre.bookingcalcul');
			$bookingprice = $pricing->GetPrice($booking);
			$bookingtype = $pricing->getBookingType($booking);



			
			//return new Response('');
			return $this->render('@OCLouvre/Louvre/summary.html.twig', array(
				'booking' => $booking,
				'bookingtype' => $bookingtype,
			    'summaries' => $bookingprice
			));
		}
	}