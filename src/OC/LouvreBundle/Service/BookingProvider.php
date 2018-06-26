<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 05/06/2018
 * Time: 21:31
 */

namespace OC\LouvreBundle\Service;


use Doctrine\ORM\EntityManager;
use Endroid\QrCode\QrCode;
use OC\LouvreBundle\Controller\BookingController;
use OC\LouvreBundle\Entity\Booking;

class BookingProvider
{
    private $entityManager;

    //tableau du nombre de ticket par commande
    private static $_NBTICKETS;

    //Tableau des tarifs par type
    private static $_PRICINGGRATUIT;
    private static $_PRICINGENFANT;
    private static $_PRICINGNORMAL;
    private static $_PRICINGSENIOR;
    private static $_PRICINGREDUCE;

    //tableau détaillé de la commande
    private static $_TABPRICE;


    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->getPricing();

    }

    /**
     * @param $date
     * @return bool
     */
    public function getDispoTicketByDate($date)
    {

        $dates = $this->entityManager->getRepository('OCLouvreBundle:Booking')->findBy(['bookingdate' => $date]);
        $totaltickets = 0;
        foreach ($dates as $date) {
            $totaltickets += $date->getTickets()->count();
        }

        if ($totaltickets >= 8) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Calcul le prix en fonction de l'age
     *
     * @param $dateofbirth
     * @param $reduceprice
     * @return array
     */
    public function getTicketPrice($dateofbirth, $reduceprice)
    {
        $reduce = $reduceprice;
        $ages = $this->calcAge($dateofbirth);

        if ($reduce == true) {
            $type = self::$_PRICINGREDUCE->getTitle();
            $price = round(self::$_PRICINGREDUCE->getPriceht() + (self::$_PRICINGREDUCE->getPriceht() * self::$_PRICINGREDUCE->getTva()));
        } elseif ($ages >= self::$_PRICINGGRATUIT->getMinage() && $ages <= self::$_PRICINGGRATUIT->getMaxage()) {
            $type = self::$_PRICINGGRATUIT->getTitle();
            $price = 0;
        } elseif ($ages >= self::$_PRICINGENFANT->getMinage() && $ages <= self::$_PRICINGENFANT->getMaxage()) {
            $type = self::$_PRICINGENFANT->getTitle();
            $price = round(self::$_PRICINGENFANT->getPriceht() + (self::$_PRICINGENFANT->getPriceht() * self::$_PRICINGENFANT->getTva()));
        } elseif ($ages >= self::$_PRICINGNORMAL->getMinage() && $ages <= self::$_PRICINGNORMAL->getMaxage()) {
            $type = self::$_PRICINGNORMAL->getTitle();
            $price = round(self::$_PRICINGNORMAL->getPriceht() + (self::$_PRICINGNORMAL->getPriceht() * self::$_PRICINGNORMAL->getTva()));
        } elseif ($ages >= self::$_PRICINGSENIOR->getMinage() && $ages <= self::$_PRICINGSENIOR->getMaxage()) {
            $type = self::$_PRICINGNORMAL->getTitle();
            $price = round(self::$_PRICINGSENIOR->getPriceht() + (self::$_PRICINGSENIOR->getPriceht() * self::$_PRICINGSENIOR->getTva()));
        }

        return ['type' => $type, 'price' => $price];
    }

    /**
     * Calcul le prix total
     *
     * @return float
     */
    public function getTotalTicket($booking)
    {
        $this->getNbCatPricing($booking);

        $total =
            round(self::$_NBTICKETS['enfant'] * (self::$_PRICINGENFANT->getPriceht() * (1 + self::$_PRICINGENFANT->getTva()))) +
            round(self::$_NBTICKETS['normal'] * (self::$_PRICINGNORMAL->getPriceht() * (1 + self::$_PRICINGNORMAL->getTva()))) +
            round(self::$_NBTICKETS['senior'] * (self::$_PRICINGSENIOR->getPriceht() * (1 + self::$_PRICINGSENIOR->getTva()))) +
            round(self::$_NBTICKETS['reduce'] * (self::$_PRICINGREDUCE->getPriceht() * (1 + self::$_PRICINGREDUCE->getTva())));

        return $total;
    }

    /**
     *
     * Renvoi un tableau détaillé contenant chaque tarif avec la quantité, la somme des tickets
     * et la somme total des tickets
     *
     * @return array
     */
    public function getTabPrice($booking = null, $dateofbirth = null)
    {
        if (is_null($booking)) {

        } else {
            $this->getNbCatPricing($booking);

            //Caculation tickets price
            $priceGratuit = 0;

            //Princing for child ticket
            if (self::$_NBTICKETS['enfant'] > 0) {
                $priceEnfant = round(self::$_NBTICKETS['enfant'] * (self::$_PRICINGENFANT->getPriceht() * (1 + self::$_PRICINGENFANT->getTva())));
            } else {
                $priceEnfant = 0;
            }

            //Pricing for normal tickets
            if (self::$_NBTICKETS['normal'] > 0) {
                $priceNormal = round(self::$_NBTICKETS['normal'] * (self::$_PRICINGNORMAL->getPriceht() * (1 + self::$_PRICINGNORMAL->getTva())));
            } else {
                $priceNormal = 0;
            }

            //Pricing for senior ticket
            if (self::$_NBTICKETS['senior'] > 0) {
                $priceSenior = round(self::$_NBTICKETS['senior'] * (self::$_PRICINGSENIOR->getPriceht() * (1 + self::$_PRICINGSENIOR->getTva())));
            } else {
                $priceSenior = 0;
            }

            //Pricing for reduce ticket
            if (self::$_NBTICKETS['reduce'] > 0) {
                $priceReduce = round(self::$_NBTICKETS['reduce'] * (self::$_PRICINGREDUCE->getPriceht() * (1 + self::$_PRICINGREDUCE->getTva())));
            } else {
                $priceReduce = 0;
            }

            if (is_null(self::$_TABPRICE)) {
                self::$_TABPRICE = [
                    'details' => [
                        ['id' => 'gratuit', 'quantity' => self::$_NBTICKETS['gratuit'], 'price' => $priceGratuit],
                        ['id' => 'enfant', 'quantity' => self::$_NBTICKETS['enfant'], 'price' => $priceEnfant],
                        ['id' => 'normal', 'quantity' => self::$_NBTICKETS['normal'], 'price' => $priceNormal],
                        ['id' => 'senior', 'quantity' => self::$_NBTICKETS['senior'], 'price' => $priceSenior],
                        ['id' => 'reduit', 'quantity' => self::$_NBTICKETS['reduce'], 'price' => $priceReduce]
                    ],
                    'total' => $this->getTotalTicket($booking)
                ];
                return self::$_TABPRICE;
            } else {
                return self::$_TABPRICE;
            }
        }
    }

    public function getOrderSummary($idclient)
    {
        $order = $this->entityManager->getRepository('OCLouvreBundle:Booking')->find($idclient);
        return $order;
    }

    /**
     *
     * Renvoi le type de ticket
     *
     * @param Booking $booking
     * @return string
     */
    public function getBookingType(Booking $booking)
    {
        if ($booking->getTickettype() === 'day') {
            return $bookingtype = 'journée';
        }
        if ($booking->getTickettype() === 'halfday') {
            return $bookingtype = 'demi-journée';
        }
    }

    /**
     *
     * Calcul l'age à partir d'une date de naissance
     *
     * @param $dateofbirth
     * @return int
     */
    private function calcAge($dateofbirth)
    {
        $date = date_format($dateofbirth, 'Y-m-d');
        $am = explode('-', $date);
        $an = explode('-', date('Y-m-d'));

        if (($am[1] < $an[1]) || (($am[1] == $an[1]) && ($am[2] <= $an[2])))
            return $an[0] - $am[0];

        return $an[0] - $am[0] - 1;
    }

    /**
     * Récupère la table Pricing
     * et hydrate les attribues pour chaque type de prix
     */
    private function getPricing()
    {
        $pricing = $this->entityManager->getRepository('OCLouvreBundle:Pricing')->findAll();

        self::$_PRICINGGRATUIT = $pricing[0];
        self::$_PRICINGENFANT = $pricing[1];
        self::$_PRICINGNORMAL = $pricing[2];
        self::$_PRICINGSENIOR = $pricing[3];
        self::$_PRICINGREDUCE = $pricing[4];
    }

    /**
     * Calcul le nombre de type de ticket et hydrate $nbtickets
     *
     * @param $booking
     * @return array
     */
    private function getNbCatPricing($booking)
    {

        $nbGratuit = 0;
        $nbEnfant = 0;
        $nbNormal = 0;
        $nbSenior = 0;
        $nbReduce = 0;

        $tickets = $booking->getTickets();
        foreach ($tickets as $ticket) {
            $reduce = $ticket->getReduceprice();
            $ages = $this->calcAge($ticket->getDateofbirth());
            if ($reduce == true) {
                $nbReduce++;
            } elseif ($ages >= self::$_PRICINGGRATUIT->getMinage() && $ages <= self::$_PRICINGGRATUIT->getMaxage()) {
                $nbGratuit++;
            } elseif ($ages >= self::$_PRICINGENFANT->getMinage() && $ages <= self::$_PRICINGENFANT->getMaxage()) {
                $nbEnfant++;
            } elseif ($ages >= self::$_PRICINGNORMAL->getMinage() && $ages <= self::$_PRICINGNORMAL->getMaxage()) {
                $nbNormal++;
            } elseif ($ages >= self::$_PRICINGSENIOR->getMinage() && $ages <= self::$_PRICINGSENIOR->getMaxage()) {
                $nbSenior++;
            }
        }

        if (is_null(self::$_NBTICKETS)) {
            self::$_NBTICKETS = ['reduce' => $nbReduce, 'gratuit' => $nbGratuit, 'enfant' => $nbEnfant, 'normal' => $nbNormal, 'senior' => $nbSenior];
        } else {
            return self::$_NBTICKETS;
        }
    }

    public function getQrCode($token)
    {
        $qrCode = new QrCode($token);

        //return $qrCode->writeDataUri();
        return $qrCode->writeString();
    }

}