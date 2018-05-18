<?php
/**
 * Created by PhpStorm.
 * User: Pruvost
 * Date: 16/05/2018
 * Time: 13:47
 */

namespace OC\LouvreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function indexAction(){
        return $this->render('@OCLouvre/Booking/accueil.html.twig');

    }


    public function widgetAction(){
        $listPurchase = array(
            array('qt' => '0', 'title' => ' x Gratuit','price' => '0€'),
            array('qt' => '0', 'title' => ' x Enfant','price' => '0€'),
            array('qt' => '0', 'title' => ' x Normal','price' => '0€'),
            array('qt' => '0', 'title' => ' x Sénior','price' => '0€'),
            array('qt' => '0', 'title' => ' x Réduit','price' => '0€')
        );


        return $this->render('@OCLouvre/Booking/widgetPurchase.html.twig', array(
        'listPurchase' => $listPurchase
        ));

    }
}