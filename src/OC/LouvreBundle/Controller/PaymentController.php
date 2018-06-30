<?php
	/**
	 * Created by PhpStorm.
	 * User: cecile
	 * Date: 07/06/2018
	 * Time: 22:13
	 */
	
	namespace OC\LouvreBundle\Controller;
	
	use OC\LouvreBundle\Entity\Booking;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class PaymentController extends Controller
	{
		private $successpayment = false;
		
		public function paymentAction(Request $request, Booking $booking)
		{
			$em = $this->getDoctrine()->getManager()->getRepository('OCLouvreBundle:Booking')->find($booking->getId());
			if ($em->getEtat()) {
				$this->addFlash('info', "Cette commande est clôturée.");
				return $this->redirectToRoute("oc_louvre_homepage");
			}
			
			$stripe = $this->get('oc_louvre.stripe');
			$bookingprovider = $this->get('oc_louvre.bookingprovider');
			$bookingprice = $bookingprovider->getPendingOrder($booking);
			
			if ($request->isMethod('POST')) {
				$clientname = strip_tags($_POST['name']);
				$clientmail = strip_tags($_POST['email']);
				
				if ($stripe->getChecking($booking, $bookingprovider)) {
					if ($this->verifName($clientname)) {
						if ($this->verifEmail($clientmail)) {
							
							$em = $this->getDoctrine()->getManager();
							
							//Hydrate clientname, clientmail and etat
							$booking->setClientname($clientname);
							$booking->setClientmail($clientmail);
							$booking->setEtat(1);
							
							$em->persist($booking);
							$em->flush();
							
							//Contenu Email
							$message = new \Swift_Message();
							$img_logo = $message->embed(\Swift_Image::fromPath('https://www.nacom.fr/openclassroom/proj4/web/lib/img/logo-louvre.jpg'));
							$message->setSubject('MUSÉE DU LOUVRE PARIS - CONFIRMATION DE COMMANDE')
								->setFrom('optest@nacom.fr')
								->setTo($clientmail)
								->setCharset('utf-8')
								->setContentType('text/html')
								->setBody($this->renderView('@OCLouvre/Louvre/mail/orderconfirmation.html.twig',
									array(
										'booking' => $booking,
										
										'logo' => $img_logo))
								);
							
							if ($this->get('mailer')->send($message)) {
								dump('email envoyé !');
								$this->successpayment = true;
								return $this->render('@OCLouvre/Louvre/payment.html.twig', array(
									'success' => $this->successpayment,
									'booking' => $booking,
									'summaries' => $bookingprice
								));
							} else {
								dump('email non envoyé');
							}
							
							//$this->get('mailer')->send($message);
							
							
						}
						$error = 'L\'adresse email spécifiée n\'est pas correcte';
						return $this->render('@OCLouvre/Louvre/payment.html.twig', array(
							'booking' => $booking,
							'summaries' => $bookingprice,
							'error' => $error,
							'success' => $this->successpayment
						));
					}
					
					$error = 'Le nom doit être supérieur à 3 caractères';
					return $this->render('@OCLouvre/Louvre/payment.html.twig', array(
						'booking' => $booking,
						'summaries' => $bookingprice,
						'error' => $error,
						'success' => $this->successpayment
					));
				}
				return $this->redirectToRoute("oc_louvre_summary");
			}
			
			return $this->render('@OCLouvre/Louvre/payment.html.twig', array(
				'booking' => $booking,
				'summaries' => $bookingprice,
				'success' => $this->successpayment
			));
		}
		
		/**
		 * Vérifie taille du nom
		 *
		 * @param $name
		 * @return bool
		 */
		
		private function verifName($name)
		{
			if (strlen($name) >= 3) {
				return true;
			}
		}
		
		/**
		 * Vérifie les adresses emails
		 *
		 * @param $email
		 * @return bool
		 */
		private function verifEmail($email)
		{
			if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $email)) {
				return true;
			}
		}
		
	}