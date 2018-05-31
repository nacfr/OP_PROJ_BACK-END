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
			
			if ($form->isSubmitted() && $form->isValid()) {

				$em = $this->getDoctrine()->getManager();
				$em->persist($booking);
				$em->flush();

                //return $this->forward('OCLouvreBundle:Booking:summary', array('btoken' => $booking->getBtoken()));
                return $this->redirectToRoute('oc_louvre_summary', array('btoken' => $booking->getBtoken()));
			}


			
			return $this->render('@OCLouvre/Booking/accueil.html.twig', array('form' => $form->createView()));
			

		}


        /**
         * @ParamConverter("booking", class="OCLouvreBundle:Tbooking" , options={"repository_method" = "findBookingByToken"}))
         */
        public function summaryAction(Tbooking $booking)
        {
            //$token = $booking->getBtoken();
            //$date = $booking->getBookingdate();
            $token = $booking->getBtoken();

            $list = $this->getDoctrine()
                ->getManager()
                ->getRepository('OCLouvreBundle:Tbooking')
                ->getTest($token);

            var_dump($list);

            return new Response('ok');
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
			
			
			return $this->render('@OCLouvre/Booking/widgetPurchase.html.twig', array(
				'listPurchase' => $listPurchase
			));
			
		}
		
		public function sucessAction()
		{
			return $this->render('@OCLouvre/Booking/sucess.html');
		}
		
		public function pricingAction()
		{
		
		
		
		}
	}