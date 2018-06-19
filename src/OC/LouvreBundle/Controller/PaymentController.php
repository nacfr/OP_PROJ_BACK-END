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

        $stripe = $this->get('oc_louvre.stripe');
        $bookingprovider = $this->get('oc_louvre.bookingprovider');
        
        if ($request->isMethod('POST')) {
            $clientname = strip_tags($_POST['name']);
            $clientmail = strip_tags($_POST['email']);

            if ($stripe->getChecking($booking, $bookingprovider)) {
                if ($this->verifName($clientname)) {
                    if ($this->verifEmail($clientmail)) {
	                    
                    	$em = $this->getDoctrine()->getManager();
	                    
                    	//Hydrate clientname and clientmail
                    	$booking->setClientname($clientname);
	                    $booking->setClientmail($clientmail);
	
	                    $em->persist($booking);
	                    $em->flush();

	                    /*$this->get('oc_louvre.bookingmailer')->MailOrderConfirmation($booking);*/
	                    
	                    
	                    //Email
	                    $message = (new \Swift_Message('MUSÉE DU LOUVRE PARIS - CONFIRMATION DE COMMANDE'))
		                    ->setFrom('villamarine.berck@gmail.com')
		                    ->setTo('ceciletguillaume@gmail.com')
		                    ->setBody($this->renderView('@OCLouvre/Louvre/mail/orderconfirmation.html.twig',
			                    array(
				                    'booking' => $booking,
				                    'qr' => $bookingprovider->getQrCode($booking->getBtoken())
			                    )),
			                    'text/html');
	
	                    $this->get('mailer')->send($message);


	                    
                        $this->successpayment = true;
                        return $this->render('@OCLouvre/Louvre/payment.html.twig', array(
                            'success' => $this->successpayment
                        ));
                    }
                    
                    $error = 'L\'adresse email spécifiée n\'est pas correcte';
                    return $this->render('@OCLouvre/Louvre/payment.html.twig', array(
                        'booking' => $booking,
                        'error' => $error,
                        'success' => $this->successpayment
                    ));
                }
                
                $error = 'Le nom doit être supérieur à 3 caractères';
                return $this->render('@OCLouvre/Louvre/payment.html.twig', array(
                    'booking' => $booking,
                    'error' => $error,
                    'success' => $this->successpayment
                ));
            }
        }

        return $this->render('@OCLouvre/Louvre/payment.html.twig', array(
            'booking' => $booking,
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