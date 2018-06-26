<?php
/**
 * Created by PhpStorm.
 * User: Pruvost
 * Date: 18/06/2018
 * Time: 15:01
 */

namespace OC\LouvreBundle\Service;

use Endroid\QrCode\QrCode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class BookingMail extends Controller
{
	
	private $successpayment = false;
	
	public function mailOrderConfirmation($booking)
    {
    	
    	//Generate QrCode
        /*$qrCode = new QrCode($booking->getBtoken());
        $resultQrCode = $qrCode->writeDataUri();*/

        //Email
	    dump($booking);
	    $message = new \Swift_Message();
	    $message->setSubject('MUSÉE DU LOUVRE PARIS - CONFIRMATION DE COMMANDE')
		    ->setFrom('optest@nacom.fr')
		    ->setTo($booking->getClientmail())
		    ->setCharset('utf-8')
		    ->setContentType('text/html')
		    ->setBody($this->renderView('@OCLouvre/Louvre/mail/orderconfirmation.html.twig',
			    array(
				    'booking' => $booking))
		    );
	    return $message;
    }

}