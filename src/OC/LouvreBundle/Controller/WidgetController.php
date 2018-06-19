<?php
/**
 * Created by PhpStorm.
 * User: Pruvost
 * Date: 19/06/2018
 * Time: 12:00
 */

namespace OC\LouvreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

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

    /**
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function widgetPurchaseAction(Session $session, Request $request)
    {
        $token = $session->get('token');

        $test = $request->query->get('btoken');
        

        if(empty($token)){
            $listPurchase = array(
                array('qt' => '0', 'title' => ' x Gratuit', 'price' => '0€'),
                array('qt' => '0', 'title' => ' x Enfant', 'price' => '0€'),
                array('qt' => '0', 'title' => ' x Normal', 'price' => '0€'),
                array('qt' => '0', 'title' => ' x Sénior', 'price' => '0€'),
                array('qt' => '0', 'title' => ' x Réduit', 'price' => '0€')
            );

            return $this->render('@OCLouvre/Louvre/widgetPurchase.html.twig', array(
                'listPurchase' => $listPurchase
            ));
        }
        else{

            $em = $this->getDoctrine()->getManager();
            $listPurchase = $em->getRepository('OCLouvreBundle:Booking')->findBy(array('btoken' => $token));

            return $this->render('@OCLouvre/Louvre/widgetPurchase.html.twig', array(
                'listPurchase' => $listPurchase
            ));
        }

    }
}