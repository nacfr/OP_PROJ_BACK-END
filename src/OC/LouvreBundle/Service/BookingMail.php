<?php
/**
 * Created by PhpStorm.
 * User: Pruvost
 * Date: 18/06/2018
 * Time: 15:01
 */

namespace OC\LouvreBundle\Service;

use Endroid\QrCode\QrCode;
use OC\LouvreBundle\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class BookingMail extends Controller
{

    public function mailOrderConfirmation(Booking $booking)
    {
        //Generate QrCode
        $qrCode = new QrCode($booking->getBtoken());
        $resultQrCode = $qrCode->writeDataUri();

        //Email
        $message = (new \Swift_Message('MUSÉE DU LOUVRE PARIS - CONFIRMATION DE COMMANDE'))
            ->setFrom('villamarine.berck@gmail.com')
            ->setTo('ceciletguillaume@gmail.com')
            ->setBody($this->renderView('@OCLouvre/Louvre/mail/orderconfirmation.html.twig',
                array(
                    'booking' => $booking,
                    'qr' => $resultQrCode
                )),
                'text/html');

        $this->get('mailer')->send($message);
    }

}