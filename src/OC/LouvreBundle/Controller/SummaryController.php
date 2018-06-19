<?php
	/**
	 * Created by PhpStorm.
	 * User: cecile
	 * Date: 06/06/2018
	 * Time: 21:18
	 */
	
	namespace OC\LouvreBundle\Controller;
	
	use OC\LouvreBundle\Service\BookingProvider;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\Form\Extension\Core\Type\FormType;
	use Symfony\Component\HttpFoundation\Response;
	use OC\LouvreBundle\Entity\Booking;
    use Symfony\Component\HttpFoundation\Session\Session;

    class SummaryController extends Controller
	{

        public function summaryAction(Booking $booking)
		{
		    $pricing = $this->get('oc_louvre.bookingprovider');
			$bookingprice = $pricing->getTabPrice($booking);
			$bookingtype = $pricing->getBookingType($booking);

			//return new Response('');
			return $this->render('@OCLouvre/Louvre/summary.html.twig', array(
				'booking' => $booking,
				'bookingtype' => $bookingtype,
			    'summaries' => $bookingprice
			));
		}
	}