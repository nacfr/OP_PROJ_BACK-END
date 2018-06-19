<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 07/06/2018
 * Time: 23:11
 */

namespace OC\LouvreBundle\Service;

use OC\LouvreBundle\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class Stripe extends Controller
{
    public function getChecking(Booking $booking, BookingProvider $bookingprovider)
    {
        try {
            \Stripe\Stripe::setApiKey("clé");

            $totalprice = $bookingprovider->getTabPrice($booking)['total'];
            $source = $_POST['stripeSource'];
            $clientname = strip_tags($_POST['name']);
            $clientmail = strip_tags($_POST['email']);

            $customer = \Stripe\Customer::create(array(
                "description" => $clientname,
                "email" => $clientmail,
                "source" => $source
            ));


            $charge = \Stripe\Charge::create(array(
                "amount" => ($totalprice) * 100,
                "currency" => "eur",
                "customer" => $customer['id'],
                "source" => $source,
                "description" => "Paiement Stripe - OpenClassrooms Exemple"
            ));

            return true;

        } catch(\Stripe\Error\Card $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            return $this->getErrorMessage($err['code']);
        }
    }

    /**
     * @param $code_error
     */
    public function getErrorMessage($code_error)
    {
        switch ($code_error){

        }
    }

}