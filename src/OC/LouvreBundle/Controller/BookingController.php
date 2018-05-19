<?php
/**
 * Created by PhpStorm.
 * User: Pruvost
 * Date: 16/05/2018
 * Time: 13:47
 */

namespace OC\LouvreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\HttpFoundation\Response;
use OC\LouvreBundle\Form\BookingType;

class BookingController extends Controller
{
    public function indexAction(){

        //Création formulaire
        /*
        $form = $this->createFormBuilder()
            ->add('date', DateType::class)
            ->add('dayPost', RadioType::class)
            ->add('halfdayPost', RadioType::class)
            ->getForm();
        */

       $form = $this->createForm(BookingType::class);

        return $this->render('@OCLouvre/Booking/accueil.html.twig', array('form' => $form->createView()
        ));

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