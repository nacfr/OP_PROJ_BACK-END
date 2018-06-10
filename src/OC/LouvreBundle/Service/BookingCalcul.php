<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 05/06/2018
 * Time: 21:31
 */

namespace OC\LouvreBundle\Service;


use Doctrine\ORM\EntityManager;
use OC\LouvreBundle\Entity\Tbooking;
use OC\LouvreBundle\Entity\Pricing;

class BookingCalcul
{

    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getTicketPrice($dateofbirth, $reduceprice)
    {
        $pricing = $this->entityManager->getRepository('OCLouvreBundle:Pricing')->findAll();
        $reduce = $reduceprice;
        $ages = $this->calcAge($dateofbirth);
        $pricingGratuit = $pricing[0];
        $pricingEnfant = $pricing[1];
        $pricingNormal = $pricing[2];
        $pricingSenior = $pricing[3];
        $pricingReduce = $pricing[4];

        if ($reduce == true) {
            return round($pricingReduce->getPriceht() + ($pricingReduce->getPriceht() * $pricingReduce->getTva()));
        }
        if ($ages >= $pricingGratuit->getMinage() && $ages <= $pricingGratuit->getMaxage()) {
            return 0;
        }
        if ($ages >= $pricingEnfant->getMinage() && $ages <= $pricingEnfant->getMaxage()) {
            return round($pricingEnfant->getPriceht() + ($pricingEnfant->getPriceht() * $pricingEnfant->getTva()));
        }
        if ($ages >= $pricingNormal->getMinage() && $ages <= $pricingNormal->getMaxage()) {
            return round($pricingNormal->getPriceht() + ($pricingNormal->getPriceht() * $pricingNormal->getTva()));
        }
        if ($ages >= $pricingSenior->getMinage() && $ages <= $pricingSenior->getMaxage()) {
            return round($pricingSenior->getPriceht() + ($pricingSenior->getPriceht() * $pricingSenior->getTva()));
        }
    }


    public function getPrice(Tbooking $booking)
     {
         $pricing = $this->entityManager->getRepository('OCLouvreBundle:Pricing')->findAll();
         $tickets = $booking->getTtickets();
         $pricingGratuit = $pricing[0];
         $nbGratuit = 0;
         $pricingEnfant = $pricing[1];
         $nbEnfant = 0;
         $pricingNormal = $pricing[2];
         $nbNormal = 0;
         $pricingSenior = $pricing[3];
         $nbSenior = 0;
         $pricingReduce = $pricing[4];
         $nbReduce = 0;

         //Caculation tickets quantity
         foreach ($tickets as $ticket) {
             $reduce = $ticket->getReduceprice();
             $ages = $this->calcAge($ticket->getDateofbirth());
             if ($reduce == true) {
                 $nbReduce++;
             } elseif ($ages >= $pricingGratuit->getMinage() && $ages <= $pricingGratuit->getMaxage()) {
                 $nbGratuit++;
             } elseif ($ages >= $pricingEnfant->getMinage() && $ages <= $pricingEnfant->getMaxage()) {
                 $nbEnfant++;
             } elseif ($ages >= $pricingNormal->getMinage() && $ages <= $pricingNormal->getMaxage()) {
                 $nbNormal++;
             } elseif ($ages >= $pricingSenior->getMinage() && $ages <= $pricingSenior->getMaxage()) {
                 $nbSenior++;
             }
         }

         //Caculation tickets price
         $priceTotal = 0;
         $priceGratuit = 0;

         //Princing for child ticket
         if ($nbEnfant > 0) {
             $priceEnfant = round($nbEnfant*($pricingEnfant->getPriceht()*(1+$pricingEnfant->getTva())));
             $priceTotal += $priceEnfant;
         } else {
             $priceEnfant = 0;
         }

         //Pricing for normal tickets
         if ($nbNormal > 0) {
             $priceNormal = round($nbNormal*($pricingNormal->getPriceht()*(1+$pricingNormal->getTva())));
             $priceTotal += $priceNormal;
         } else {
             $priceNormal = 0;
         }

         //Pricing for senior ticket
         if ($nbSenior > 0) {
             $priceSenior = round($nbSenior*($pricingSenior->getPriceht()*(1+$pricingSenior->getTva())));
             $priceTotal += $priceSenior;
         } else {
             $priceSenior = 0;
         }

         //Pricing for reduce ticket
         if ($nbReduce > 0) {
             $priceReduce = round($nbReduce*($pricingReduce->getPriceht()*(1+$pricingReduce->getTva())));
             $priceTotal += $priceReduce;
         } else {
             $priceReduce = 0;
         }

         return [
             'details' => [
                 ['id' => 'gratuit', 'quantity' => $nbGratuit, 'price' => $priceGratuit],
                 ['id' => 'enfant', 'quantity' => $nbEnfant, 'price' => $priceEnfant],
                 ['id' => 'normal', 'quantity' => $nbNormal, 'price' => $priceNormal],
                 ['id' => 'senior', 'quantity' => $nbSenior, 'price' => $priceSenior],
                 ['id' => 'reduit', 'quantity' => $nbReduce, 'price' => $priceReduce]
             ],
             'total' => [
                 ['price' => $priceTotal]
             ]
         ];

     }

    public function getBookingType(Tbooking $booking)
    {
        if ($booking->getTickettype() === 'day') {
            return $bookingtype = 'journée';
        }
        if ($booking->getTickettype() === 'halfday') {
            return $bookingtype = 'demi-journée';
        }
    }

    private function calcAge($dateofbirth)
    {
        $date = date_format($dateofbirth, 'Y-m-d');
        $am = explode('-', $date);
        $an = explode('-', date('Y-m-d'));

        if (($am[1] < $an[1]) || (($am[1] == $an[1]) && ($am[2] <= $an[2])))
            return $an[0] - $am[0];

        return $an[0] - $am[0] - 1;
    }

}