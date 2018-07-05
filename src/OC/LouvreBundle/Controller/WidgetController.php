<?php
/**
 * Created by PhpStorm.
 * User: Pruvost
 * Date: 19/06/2018
 * Time: 12:00
 */

namespace OC\LouvreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WidgetController extends Controller
{
    public function widgetPriceAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listPrices = $em->getRepository('OCLouvreBundle:Pricing')->findAll();
        return $this->render('@OCLouvre/Louvre/widgetPrice.html.twig', array(
            'listPrices' => $listPrices
        ));
    }
}