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
        return $this->render('@OCLouvre/Booking/index.html.twig');

    }
}